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
    public $questions;
    public $currentQuestionIndex = 0;
    public $remainingSeconds = 0;
    
    // To hold current answers
    public $answers = [];
    public $doubtful = [];
    
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
        $this->exam = Exam::with('questions.options')->findOrFail($examId);
        
        $this->session = ExamSession::where('user_id', Auth::id())
            ->where('exam_id', $this->exam->id)
            ->firstOrFail();

        // Redirect if already completed
        if ($this->session->status === 'completed') {
            return redirect()->route('participant.exam.result', ['examId' => $this->exam->id]);
        }

        // Initialize remaining time correctly
        $this->checkStatus();
        
        // Auto submit if time already passed and not paused
        if ($this->remainingSeconds <= 0 && !$this->session->is_paused) {
            $this->finishExam();
            return;
        }

        $this->questions = $this->exam->questions;

        // Load existing answers
        $existingAnswers = UserAnswer::where('exam_session_id', $this->session->id)->get();
        foreach ($existingAnswers as $ans) {
            if ($ans->option_id) {
                $this->answers[$ans->question_id] = $ans->option_id;
            } elseif ($ans->answer_text) {
                $this->answers[$ans->question_id] = $ans->answer_text;
            }
            $this->doubtful[$ans->question_id] = (bool)$ans->is_doubtful;
        }
    }

    public function render()
    {
        $this->checkStatus();
        
        $currentQuestion = $this->questions[$this->currentQuestionIndex] ?? null;
        
        // Randomize options if the question type is multiple choice
        $currentOptions = collect();
        if ($currentQuestion && $currentQuestion->type === 'multiple_choice') {
            // Usually options should be cached per session or seed, but for now we'll just get them
            $currentOptions = $currentQuestion->options;
        }

        $totalQuestions = count($this->questions);
        $questionOrder = $this->questions->pluck('id')->toArray();

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
        
        if ($this->session->status === 'completed') {
            return redirect()->route('participant.dashboard');
        }

        if (!$this->session->is_paused) {
            $startedAt = \Carbon\Carbon::parse($this->session->started_at);
            $endTime = $startedAt->copy()->addMinutes($this->exam->duration_minutes);
            
            // Auto submit if time already passed and not paused
            if ($this->remainingSeconds <= 0 && !$this->session->is_paused) {
                $this->finishExam();
                return;
            }
            
            $this->remainingSeconds = max(0, now()->diffInSeconds($endTime, false));
            \Log::info("Participant checkStatus (Active) - Session {$this->session->id} - duration: {$this->exam->duration_minutes}m - startedAt: {$startedAt} - endTime: {$endTime} - now: " . now() . " - remainingSeconds: {$this->remainingSeconds}s");
        } else {
            $this->remainingSeconds = $this->session->leftover_seconds ?? 0;
            \Log::info("Participant checkStatus (Paused) - Session {$this->session->id} - leftover: {$this->session->leftover_seconds}s - remainingSeconds: {$this->remainingSeconds}s");
        }
    }

    public function updatedAnswers($value, $questionId)
    {
        if ($this->session->is_paused) return;
        
        $question = $this->questions->where('id', $questionId)->first();
        if ($question) {
            $this->saveAnswer($questionId, $value, $question->type);
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
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
        $total = count($this->questions);
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
            \App\Services\LogService::record('violation', 'Peserta ' . Auth::user()->name . ' (NIK: ' . Auth::user()->nik . ') melakukan pelanggaran: ' . ($message ?? 'Aktivitas mencurigakan'));
        }
    }
}
