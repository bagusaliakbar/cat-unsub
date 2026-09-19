<?php

namespace App\Livewire\Participant;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\UserAnswer;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ExamExecution extends Component
{
    public $exam;
    public $session;
    public $questionIds = [];
    public $currentQuestionIndex = 0;
    public $remainingSeconds = 0;
    public $violationCount = 0;
    
    public $answers = [];
    public $doubtful = [];
    public $optionsOrder = [];
    
    // Summary Modal Properties
    public $showSummaryModal = false;
    public $summaryData = [
        'total' => 0,
        'answered' => 0,
        'unanswered' => 0,
        'doubtful' => 0,
    ];

    public function mount($examId)
    {
        $this->exam = Exam::findOrFail($examId);
        
        $this->session = ExamSession::where('user_id', Auth::id())
            ->where('exam_id', $this->exam->id)
            ->firstOrFail();

        $this->violationCount = $this->session->violation_count;

        // Redirect if already completed
        if ($this->session->status === 'completed') {
            return redirect()->route('participant.exam.result', ['examId' => $this->exam->id]);
        }

        // Initialize remaining time correctly
        $statusCheck = $this->checkStatus();
        if ($statusCheck) {
            return $statusCheck;
        }
        
        // Auto submit if time already passed and not paused
        if ($this->remainingSeconds <= 0 && !$this->session->is_paused) {
            $this->finishExam();
            return;
        }

        if (empty($this->session->question_order)) {
            $questions = $this->exam->questions()->with('options')->get();
            
            if ($this->exam->randomize_questions) {
                $questions = $questions->shuffle();
            }
            
            $this->questionIds = $questions->pluck('id')->toArray();
            $this->session->update(['question_order' => $this->questionIds]);
            
            // Pre-populate user answers to store options_order and prevent race conditions
            $answersData = [];
            $now = now();
            foreach ($questions as $question) {
                $optionsOrder = null;
                if ($this->exam->randomize_options && $question->type === 'multiple_choice') {
                    $optionsOrder = json_encode($question->options->pluck('id')->shuffle()->toArray());
                }
                
                $answersData[] = [
                    'exam_session_id' => $this->session->id,
                    'question_id' => $question->id,
                    'options_order' => $optionsOrder,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            // Use insertOrIgnore to prevent duplicate key errors if two requests initialize concurrently
            UserAnswer::insertOrIgnore($answersData);
        } else {
            $this->questionIds = $this->session->question_order;
        }

        // Load existing answers
        $existingAnswers = UserAnswer::where('exam_session_id', $this->session->id)->get();
        foreach ($existingAnswers as $ans) {
            if ($ans->option_id) {
                $this->answers[$ans->question_id] = $ans->option_id;
            } elseif ($ans->answer_text) {
                $this->answers[$ans->question_id] = $ans->answer_text;
            }
            $this->doubtful[$ans->question_id] = (bool)$ans->is_doubtful;
            
            if ($ans->options_order) {
                // Ensure it's treated as an array (cast takes care of it, but just in case)
                $this->optionsOrder[$ans->question_id] = is_string($ans->options_order) ? json_decode($ans->options_order, true) : $ans->options_order;
            }
        }
    }

    public function render()
    {
        $this->checkStatus();
        
        $currentQuestionId = $this->questionIds[$this->currentQuestionIndex] ?? null;
        $currentQuestion = $currentQuestionId ? Question::with('options')->find($currentQuestionId) : null;
        
        // Randomize options if the question type is multiple choice
        $currentOptions = collect();
        if ($currentQuestion && $currentQuestion->type === 'multiple_choice') {
            $currentOptions = $currentQuestion->options;
            
            if (isset($this->optionsOrder[$currentQuestion->id])) {
                $order = $this->optionsOrder[$currentQuestion->id];
                $currentOptions = $currentOptions->sortBy(function($option) use ($order) {
                    $pos = array_search($option->id, $order);
                    return $pos === false ? 999 : $pos;
                })->values();
            }
        }

        $totalQuestions = count($this->questionIds);
        $questionOrder = $this->questionIds;

        return view('livewire.participant.exam-session', compact(
            'currentQuestion',
            'currentOptions',
            'totalQuestions',
            'questionOrder'
        ))->layout('layouts.app');
    }

    public function goToQuestion($index)
    {
        if ($this->session->is_paused) return;
        $this->currentQuestionIndex = $index;
    }

    public function checkStatus()
    {
        $this->session->refresh();
        $this->violationCount = $this->session->violation_count;
        
        $currentSessionId = request()->session()->getId();
        if ($this->session->session_token && $this->session->session_token !== $currentSessionId) {
            \Illuminate\Support\Facades\Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('home')->with('error', 'Sesi ujian Anda diambil alih oleh perangkat lain!');
        }

        if ($this->session->status === 'completed') {
            return redirect()->route('participant.dashboard');
        }

        $startedAt = \Carbon\Carbon::parse($this->session->started_at);
        $totalSeconds = $this->exam->duration_minutes * 60;
        
        if ($this->session->is_paused) {
            $leftover = $this->session->leftover_seconds ?? 0;
            $passed = max(0, $totalSeconds - $leftover);
            $startedAt = now()->subSeconds($passed);
        }

        $endTimeByDuration = $startedAt->copy()->addMinutes($this->exam->duration_minutes);
        $endTime = $endTimeByDuration;

        if ($this->exam->end_time) {
            $examEndTime = \Carbon\Carbon::parse($this->exam->end_time);
            if ($examEndTime->lessThan($endTimeByDuration)) {
                $endTime = $examEndTime;
            }
        }

        $this->remainingSeconds = max(0, now()->diffInSeconds($endTime, false));

        if (!$this->session->is_paused) {
            // Auto submit if time already passed and not paused
            if ($this->remainingSeconds <= 0) {
                $this->finishExam();
                return;
            }
        }
    }

    public function updatedAnswers($value, $questionId)
    {
        if ($this->session->is_paused) return;
        
        $question = Question::find($questionId);
        if ($question) {
            $this->saveAnswer($questionId, $value, $question->type);
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questionIds) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function saveAnswer($questionId, $value, $type = 'multiple_choice')
    {
        if ($this->session->is_paused) return;
        
        $data = [
            'exam_session_id' => $this->session->id,
            'question_id' => $questionId,
        ];

        if ($type === 'multiple_choice') {
            $data['option_id'] = $value;
            $data['answer_text'] = null;
        } else {
            $data['option_id'] = null;
            $data['answer_text'] = $value;
        }

        UserAnswer::updateOrCreate(
            ['exam_session_id' => $this->session->id, 'question_id' => $questionId],
            $data
        );
    }

    public function toggleDoubtful($questionId)
    {
        $current = $this->doubtful[$questionId] ?? false;
        $this->doubtful[$questionId] = !$current;

        UserAnswer::updateOrCreate(
            ['exam_session_id' => $this->session->id, 'question_id' => $questionId],
            ['is_doubtful' => $this->doubtful[$questionId] ? 1 : 0]
        );
    }

    public function showSummary()
    {
        $total = count($this->questionIds);
        $answered = count(array_filter($this->answers, fn($val) => !empty($val)));
        $unanswered = $total - $answered;
        $doubtfulCount = count(array_filter($this->doubtful, fn($val) => $val === true));

        $this->summaryData = [
            'total' => $total,
            'answered' => $answered,
            'unanswered' => $unanswered,
            'doubtful' => $doubtfulCount,
        ];

        $this->showSummaryModal = true;
    }

    public function closeSummary()
    {
        $this->showSummaryModal = false;
    }

    public function finishExam()
    {
        // Auto-Grading Logic
        $score = 0;
        
        $userAnswers = UserAnswer::where('exam_session_id', $this->session->id)->get();
        $questions = $this->exam->questions->keyBy('id');

        foreach ($userAnswers as $answer) {
            $question = $questions->get($answer->question_id);
            if ($question && $question->type === 'multiple_choice' && $answer->option_id) {
                $option = $question->options->where('id', $answer->option_id)->first();
                if ($option && $option->is_correct) {
                    $score += $question->points;
                }
            }
        }

        $this->session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $score,
        ]);

        \App\Services\LogService::record('finish_exam', 'Peserta menyelesaikan ujian: ' . $this->exam->title . ' dengan nilai ' . round($score, 2));

        return redirect()->route('participant.exam.result', ['examId' => $this->exam->id]);
    }

    public function recordViolation($message = null)
    {
        if ($this->session && $this->session->status !== 'completed') {
            $this->session->increment('violation_count');
            $this->violationCount = $this->session->refresh()->violation_count;
            \App\Services\LogService::record('violation', 'Peserta ' . Auth::user()->name . ' (NIK: ' . Auth::user()->nik . ') melakukan pelanggaran: ' . ($message ?? 'Aktivitas mencurigakan'));
        }
    }
}
