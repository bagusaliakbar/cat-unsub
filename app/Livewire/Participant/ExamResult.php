<?php

namespace App\Livewire\Participant;

use App\Models\Exam;
use App\Models\ExamSession;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ExamResult extends Component
{
    public $exam;
    public $session;

    public function mount($examId)
    {
        $this->exam = Exam::findOrFail($examId);
        
        // Ensure only the owner can see their result and it's completed
        $this->session = ExamSession::where('user_id', Auth::id())
            ->where('exam_id', $this->exam->id)
            ->where('status', 'completed')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.participant.exam-result')->layout('layouts.participant');
    }
}
