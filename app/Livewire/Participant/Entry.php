<?php

namespace App\Livewire\Participant;

use Livewire\Component;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Entry extends Component
{
    public $participant_number = '';

    public $isValidated = false;
    public $participant = null;
    public $assignedExams = [];
    public $confirmingExam = null;
    public $viewingRulesExam = null;
    public $input_token = '';

    public function mount()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['participant', 'peserta'])) {
            $this->participant = Auth::user();
            $this->participant_number = $this->participant->nik ?? $this->participant->participant_number;
            $this->assignedExams = $this->participant->assignedExams()
                ->where('is_active', true)
                ->with(['sessions' => function($q) {
                    $q->where('user_id', $this->participant->id);
                }])
                ->get();
            $this->isValidated = true;
        }
    }

    public function validateParticipant()
    {
        $this->validate([
            'participant_number' => 'required|string',
        ]);

        $this->participant = User::where(function($q) {
                $q->where('participant_number', $this->participant_number)
                  ->orWhere('nik', $this->participant_number);
            })
            ->whereIn('role', ['participant', 'peserta'])
            ->first();

        if (!$this->participant) {
            $this->addError('participant_number', 'ID Peserta tidak ditemukan.');
            return;
        }

        // Get assigned active exams
        $this->assignedExams = $this->participant->assignedExams()
            ->where('is_active', true)
            ->with(['sessions' => function($q) {
                $q->where('user_id', $this->participant->id);
            }])
            ->get();

        if ($this->assignedExams->isEmpty()) {
            $this->addError('participant_number', 'Anda tidak memiliki ujian aktif saat ini. Silakan hubungi panitia.');
            return;
        }

        // Check if there is an active session on another device
        $currentSessionId = request()->session()->getId();
        $activeSession = \App\Models\ExamSession::where('user_id', $this->participant->id)
            ->whereIn('status', ['started', 'in_progress'])
            ->whereNotNull('session_token')
            ->where('session_token', '!=', $currentSessionId)
            ->first();

        if ($activeSession) {
            $this->addError('participant_number', 'Akun ini sedang aktif mengerjakan ujian di perangkat lain. Hubungi panitia untuk Buka Kunci Perangkat jika Anda berpindah perangkat.');
            return;
        }



        Auth::login($this->participant);
        return redirect()->route('home');
    }

    public function showRules($examId)
    {
        if ($this->isValidated && $this->participant) {
            $this->confirmingExam = $this->assignedExams->where('id', $examId)->first();
        }
    }

    public function viewRules($examId)
    {
        if ($this->isValidated && $this->participant) {
            $this->viewingRulesExam = $this->assignedExams->where('id', $examId)->first();
        }
    }

    public function closeRules()
    {
        $this->viewingRulesExam = null;
    }

    public function cancelStart()
    {
        $this->confirmingExam = null;
        $this->input_token = '';
    }

    public function startExam($examId)
    {
        if ($this->isValidated && $this->participant) {
            $exam = $this->assignedExams->where('id', $examId)->first();
            if ($exam) {
                if (!empty($exam->token)) {
                    if ($this->input_token !== $exam->token) {
                        $this->addError('input_token', 'Token ujian tidak valid.');
                        return;
                    }
                }

                $currentSessionId = request()->session()->getId();

                $session = \App\Models\ExamSession::where('user_id', $this->participant->id)
                    ->where('exam_id', $exam->id)
                    ->first();

                if ($session) {
                    if ($session->session_token && $session->session_token !== $currentSessionId) {
                        $this->addError('general_error', 'Ujian ini sedang dikerjakan di perangkat lain! Jika perangkat sebelumnya bermasalah, silakan minta panitia untuk mereset kunci perangkat Anda.');
                        return;
                    }
                    if (!$session->session_token) {
                        $session->update(['session_token' => $currentSessionId]);
                    }
                } else {
                    \App\Models\ExamSession::create([
                        'user_id' => $this->participant->id,
                        'exam_id' => $exam->id,
                        'started_at' => now(),
                        'status' => 'started',
                        'session_token' => $currentSessionId,
                    ]);
                }

                $this->redirectRoute('participant.exam.execute', ['examId' => $exam->id], navigate: true);
            }
        }
    }

    public function cancel()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('home');
    }

    public function retakeSimulation($examId)
    {
        if ($this->isValidated && $this->participant) {
            $exam = $this->assignedExams->where('id', $examId)->first();
            if ($exam && $exam->is_simulation) {
                $session = \App\Models\ExamSession::where('user_id', $this->participant->id)
                    ->where('exam_id', $exam->id)
                    ->first();
                    
                if ($session) {
                    // Delete answers
                    \App\Models\UserAnswer::where('exam_session_id', $session->id)->delete();
                    // Delete session
                    $session->delete();
                    
                    // Refresh data
                    $this->mount();
                }
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        $this->isValidated = false;
        $this->participant = null;
        $this->assignedExams = [];
        $this->participant_number = '';
        
        return redirect()->route('home');
    }

    public function render()
    {
        $layout = $this->isValidated ? 'layouts.participant' : 'layouts.guest';
        return view('livewire.participant.entry')->layout($layout);
    }
}
