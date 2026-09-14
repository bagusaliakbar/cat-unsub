<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use Livewire\Component;

class ExamPreview extends Component
{
    public $exam;
    public $questions;
    public $currentQuestionIndex = 0;
    
    // To hold current dummy answers
    public $answers = [];
    public $doubtful = [];

    public function mount($examId)
    {
        $this->exam = Exam::with('questions.options')->findOrFail($examId);
        // We do not randomize questions for preview so admin can easily trace it
        $this->questions = $this->exam->questions;
    }

    public function goToQuestion($index)
    {
        $this->currentQuestionIndex = $index;
    }

    public function updatedAnswers($value, $questionId)
    {
        // This is just a preview, so answers are updated locally in memory
        // No DB update
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

    public function toggleDoubtful($questionId)
    {
        $current = $this->doubtful[$questionId] ?? false;
        $this->doubtful[$questionId] = !$current;
    }

    public function finishExam()
    {
        session()->flash('message', 'Mode Preview telah ditutup. Tidak ada jawaban yang disimpan.');
        return redirect()->route('admin.exams');
    }

    public function render()
    {
        $currentQuestion = $this->questions[$this->currentQuestionIndex] ?? null;
        
        // Do not randomize options for preview
        $currentOptions = collect();
        if ($currentQuestion && $currentQuestion->type === 'multiple_choice') {
            $currentOptions = $currentQuestion->options;
        }

        $totalQuestions = count($this->questions);
        $questionOrder = $this->questions->pluck('id')->toArray();

        return view('livewire.admin.exam-preview', compact(
            'currentQuestion',
            'currentOptions',
            'totalQuestions',
            'questionOrder'
        ))->layout('layouts.app');
    }
}
