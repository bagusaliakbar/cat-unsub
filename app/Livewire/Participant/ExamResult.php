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

    public function retakeSimulation()
    {
        if ($this->exam->is_simulation) {
            // Hapus jawaban sebelumnya
            \App\Models\UserAnswer::where('exam_session_id', $this->session->id)->delete();
            // Hapus sesi sebelumnya
            $this->session->delete();
            
            // Buat sesi baru
            \App\Models\ExamSession::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'exam_id' => $this->exam->id,
                ],
                [
                    'started_at' => now(),
                    'status' => 'started',
                    'session_token' => request()->session()->getId(),
                ]
            );
            
            return redirect()->route('participant.exam.execute', ['examId' => $this->exam->id]);
        }
    }

    public function render()
    {
        return view('livewire.participant.exam-result')->layout('layouts.participant');
    }
}
