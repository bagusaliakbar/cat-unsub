<?php

namespace App\Livewire\Participant;

use App\Models\Exam;
use App\Models\ExamSession;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $tokenInputs = [];

    public function render()
    {
        $exams = Exam::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_time')
                      ->orWhere('start_time', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_time')
                      ->orWhere('end_time', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's sessions to see which exams are already started or completed
        $userSessions = ExamSession::where('user_id', Auth::id())
            ->get()
            ->keyBy('exam_id');

        return view('livewire.participant.dashboard', compact('exams', 'userSessions'))
            ->layout('layouts.app');
    }

    public function startExam($examId)
    {
        $exam = Exam::findOrFail($examId);

        // Check token if required
        if (!empty($exam->token)) {
            $inputToken = $this->tokenInputs[$examId] ?? '';
            if ($inputToken !== $exam->token) {
                session()->flash('error_'.$examId, 'Token ujian tidak valid.');
                return;
            }
        }

        $currentSessionId = request()->session()->getId();

        $session = ExamSession::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->first();

        if ($session) {
            if ($session->session_token && $session->session_token !== $currentSessionId) {
                session()->flash('error_'.$examId, 'Ujian ini sedang dikerjakan di perangkat lain! Minta panitia mereset kunci perangkat Anda.');
                return;
            }
            if (!$session->session_token) {
                $session->update(['session_token' => $currentSessionId]);
            }
        } else {
            ExamSession::create([
                'user_id' => Auth::id(),
                'exam_id' => $exam->id,
                'status' => 'started',
                'started_at' => now(),
                'session_token' => $currentSessionId,
            ]);
        }

        \App\Services\LogService::record('start_exam', 'Peserta memulai ujian: ' . $exam->title);

        return $this->redirectRoute('participant.exam.execute', ['examId' => $exam->id], navigate: true);
    }
}
