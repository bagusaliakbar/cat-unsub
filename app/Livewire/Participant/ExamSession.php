<?php

namespace App\Livewire\Participant;

use Livewire\Component;
use App\Models\Exam;
use App\Models\ExamSession as ExamSessionModel;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamSession extends Component
{
    public $exam;
    public $session;
    
    public $currentQuestionIndex = 0;
    public $questionOrder = [];
    
    public $answers = [];
    public $doubtful = [];
    
    public $endTime;
    
    public function mount($examId)
    {
        $this->exam = Exam::with('questions.options')->findOrFail($examId);
        $user = Auth::user();

        if (!$this->exam->is_active) {
            abort(403, 'Ujian tidak aktif.');
        }

        // Check if there is an existing session
        $this->session = ExamSessionModel::where('user_id', $user->id)
            ->where('exam_id', $this->exam->id)
            ->first();

        if (!$this->session) {
            // First time starting the exam
            $questions = $this->exam->questions;
            
            // Randomize questions if configured
            if ($this->exam->randomize_questions) {
                $questions = $questions->shuffle();
            }
            
            $this->questionOrder = $questions->pluck('id')->toArray();
            
            $this->session = ExamSessionModel::create([
                'user_id' => $user->id,
                'exam_id' => $this->exam->id,
                'started_at' => now(),
                'status' => 'started',
                'question_order' => $this->questionOrder,
            ]);

            // Pre-populate answers and randomize options if needed
            foreach ($questions as $question) {
                $optionsOrder = null;
                if ($this->exam->randomize_options && $question->type === 'multiple_choice') {
                    $optionsOrder = $question->options->shuffle()->pluck('id')->toArray();
                }

                UserAnswer::create([
                    'exam_session_id' => $this->session->id,
                    'question_id' => $question->id,
                    'options_order' => $optionsOrder,
                ]);
                
                $this->answers[$question->id] = null;
                $this->doubtful[$question->id] = false;
            }
        } else {
            if ($this->session->status === 'completed') {
                // If already completed, redirect to results or dashboard
                return redirect()->route('dashboard')->with('message', 'Anda sudah menyelesaikan ujian ini.');
            }

            $this->questionOrder = $this->session->question_order;
            
            // Load existing answers
            $userAnswers = UserAnswer::where('exam_session_id', $this->session->id)->get();
            foreach ($userAnswers as $ua) {
                $this->answers[$ua->question_id] = $ua->option_id ?? $ua->answer_text;
                $this->doubtful[$ua->question_id] = $ua->is_doubtful;
            }
        }

        // Calculate end time for JS countdown
        $startedAt = Carbon::parse($this->session->started_at);
        $this->endTime = $startedAt->addMinutes($this->exam->duration_minutes)->toIso8601String();
    }

    public function updatedAnswers($value, $questionId)
    {
        $this->saveAnswer($questionId);
    }

    public function toggleDoubtful($questionId)
    {
        $this->doubtful[$questionId] = !($this->doubtful[$questionId] ?? false);
        $this->saveAnswer($questionId);
    }

    protected function saveAnswer($questionId)
    {
        $answer = UserAnswer::where('exam_session_id', $this->session->id)
            ->where('question_id', $questionId)
            ->first();

        if ($answer) {
            $question = $this->exam->questions->where('id', $questionId)->first();
            
            if ($question && $question->type === 'multiple_choice') {
                $answer->option_id = $this->answers[$questionId] ?: null;
            } else {
                $answer->answer_text = $this->answers[$questionId] ?: null;
            }
            
            $answer->is_doubtful = $this->doubtful[$questionId] ?? false;
            $answer->save();
        }
    }

    public function goToQuestion($index)
    {
        if (isset($this->questionOrder[$index])) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questionOrder) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function finishExam()
    {
        // Calculate score for auto-gradable questions
        $score = 0;
        $totalPoints = 0;
        
        $userAnswers = UserAnswer::with('question', 'option')
            ->where('exam_session_id', $this->session->id)
            ->get();
            
        foreach ($userAnswers as $ua) {
            if ($ua->question) {
                $totalPoints += $ua->question->points;
                
                if ($ua->question->type === 'multiple_choice') {
                    if ($ua->option && $ua->option->is_correct) {
                        $score += $ua->question->points;
                    }
                }
            }
        }

        // Calculate final score as percentage if totalPoints > 0, else 0
        $finalScore = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;

        $this->session->update([
            'completed_at' => now(),
            'status' => 'completed',
            'score' => $finalScore,
        ]);
        
        \App\Services\LogService::record('finish_exam', 'Peserta menyelesaikan ujian: ' . $this->exam->title . ' dengan nilai ' . round($finalScore, 2));

        return redirect()->route('dashboard')->with('message', 'Ujian selesai. Skor Anda: ' . round($finalScore, 2));
    }

    public function recordViolation($message = null)
    {
        if ($this->session && $this->session->status !== 'completed') {
            $this->session->increment('violation_count');
            \App\Services\LogService::record('violation', 'Peserta ' . Auth::user()->name . ' (NIK: ' . Auth::user()->nik . ') melakukan pelanggaran: ' . ($message ?? 'Aktivitas mencurigakan'));
        }
    }

    public function render()
    {
        $currentQuestionId = $this->questionOrder[$this->currentQuestionIndex] ?? null;
        $currentQuestion = null;
        $currentOptions = [];

        if ($currentQuestionId) {
            $currentQuestion = $this->exam->questions->where('id', $currentQuestionId)->first();
            
            // Get randomized options if applicable
            if ($currentQuestion && $currentQuestion->type === 'multiple_choice') {
                $ua = UserAnswer::where('exam_session_id', $this->session->id)
                    ->where('question_id', $currentQuestionId)
                    ->first();
                    
                if ($ua && $ua->options_order) {
                    $order = $ua->options_order;
                    // Sort options based on the saved options_order array
                    $currentOptions = $currentQuestion->options->sortBy(function($option) use ($order) {
                        return array_search($option->id, $order);
                    })->values();
                } else {
                    $currentOptions = $currentQuestion->options;
                }
            }
        }

        return view('livewire.participant.exam-session', [
            'currentQuestion' => $currentQuestion,
            'currentOptions' => $currentOptions,
            'totalQuestions' => count($this->questionOrder)
        ])->layout('layouts.app');
    }
}
