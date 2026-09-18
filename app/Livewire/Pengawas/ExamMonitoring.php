<?php

namespace App\Livewire\Pengawas;

use App\Models\Exam;
use App\Models\ExamSession;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamMonitoringExport;

class ExamMonitoring extends Component
{
    public $exam;
    
    // We will use wire:poll in the view to refresh data automatically.

    public $showResultModal = false;
    public $selectedSession = null;
    public $filter_wave = '';

    public function mount($examId)
    {
        $this->exam = Exam::findOrFail($examId);
    }

    public function viewResult($sessionId)
    {
        $this->selectedSession = ExamSession::with(['user', 'answers.question', 'answers.option'])
            ->findOrFail($sessionId);
            
        $this->showResultModal = true;
    }

    public function closeResultModal()
    {
        $this->showResultModal = false;
        $this->selectedSession = null;
    }

    public $showViolationModal = false;
    public $violationLogs = [];
    public $violationSession = null;

    public function viewViolations($sessionId)
    {
        $this->violationSession = ExamSession::with('user')->findOrFail($sessionId);
        
        $this->violationLogs = \App\Models\SystemLog::where('user_id', $this->violationSession->user_id)
            ->where('action', 'violation')
            ->where('created_at', '>=', $this->violationSession->created_at)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $this->showViolationModal = true;
    }

    public function closeViolationModal()
    {
        $this->showViolationModal = false;
        $this->violationSession = null;
        $this->violationLogs = [];
    }

    public function pauseSession($sessionId)
    {
        $session = ExamSession::findOrFail($sessionId);
        if ($session->status !== 'completed' && !$session->is_paused) {
            $passed = now()->diffInSeconds($session->started_at);
            $total = $this->exam->duration_minutes * 60;
            $leftover = max(0, $total - $passed);
            
            \Log::info("Pengawas PAUSE Session {$sessionId} - duration: {$this->exam->duration_minutes}m ({$total}s) - started_at: {$session->started_at} - now: " . now() . " - passed: {$passed}s - leftover: {$leftover}s");

            $session->is_paused = true;
            $session->leftover_seconds = $leftover;
            $session->save();
        }
    }

    public function resumeSession($sessionId)
    {
        $session = ExamSession::findOrFail($sessionId);
        if ($session->status !== 'completed' && $session->is_paused) {
            $total = $this->exam->duration_minutes * 60;
            $passed = $total - $session->leftover_seconds;
            
            $newStartedAt = now()->subSeconds($passed);
            \Log::info("Pengawas RESUME Session {$sessionId} - duration: {$this->exam->duration_minutes}m ({$total}s) - leftover: {$session->leftover_seconds}s - passed: {$passed}s - new_started_at: {$newStartedAt}");

            $session->started_at = $newStartedAt;
            $session->is_paused = false;
            $session->leftover_seconds = null;
            $session->save();
        }
    }

    public function pauseAllSessions()
    {
        $sessions = ExamSession::where('exam_id', $this->exam->id)
            ->where('status', '!=', 'completed')
            ->where('is_paused', false)
            ->get();
            
        foreach ($sessions as $session) {
            $passed = now()->diffInSeconds($session->started_at);
            $total = $this->exam->duration_minutes * 60;
            $leftover = max(0, $total - $passed);
            
            $session->is_paused = true;
            $session->leftover_seconds = $leftover;
            $session->save();
        }
    }

    public function resumeAllSessions()
    {
        $sessions = ExamSession::where('exam_id', $this->exam->id)
            ->where('status', '!=', 'completed')
            ->where('is_paused', true)
            ->get();
            
        foreach ($sessions as $session) {
            $total = $this->exam->duration_minutes * 60;
            $passed = $total - $session->leftover_seconds;
            
            $session->started_at = now()->subSeconds($passed);
            $session->is_paused = false;
            $session->leftover_seconds = null;
            $session->save();
        }
    }

    public function export()
    {
        return Excel::download(new ExamMonitoringExport($this->exam->id), 'hasil_ujian_' . \Str::slug($this->exam->title) . '.xlsx');
    }

    public function render()
    {
        $query = ExamSession::with(['user', 'answers.question', 'answers.option'])
            ->where('exam_id', $this->exam->id);
            
        if ($this->filter_wave) {
            $query->whereHas('user', function($q) {
                $q->where('wave_id', $this->filter_wave);
            });
        }
        
        $sessions = $query->get();

        $totalPoints = $this->exam->questions()->sum('points');
        
        $waves = \App\Models\Wave::where('is_active', true)->get();

        $sessions = $sessions->map(function ($session) use ($totalPoints) {
            $score = 0;
            $correct = 0;
            $wrong = 0;
            $answered = 0;

            foreach ($session->answers as $ans) {
                if ($ans->option_id || $ans->answer_text) {
                    $answered++;
                }

                if ($ans->question && $ans->question->type === 'multiple_choice') {
                    if ($ans->option && $ans->option->is_correct) {
                        $score += $ans->question->points;
                        $correct++;
                    } elseif ($ans->option_id) {
                        $wrong++;
                    }
                }
            }

            if ($session->status === 'completed') {
                $session->live_score = $session->score;
            } else {
                $session->live_score = $totalPoints > 0 ? round(($score / $totalPoints) * 100, 2) : 0;
            }
            
            $session->stat_correct = $correct;
            $session->stat_wrong = $wrong;
            $session->stat_answered = $answered;

            return $session;
        })->sortByDesc(function ($session) {
            return [$session->live_score, $session->started_at];
        })->values();

        return view('livewire.pengawas.exam-monitoring', compact('sessions', 'waves'))
            ->layout('layouts.app');
    }
}
