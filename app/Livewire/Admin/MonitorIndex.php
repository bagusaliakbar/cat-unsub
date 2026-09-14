<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use Livewire\Component;

class MonitorIndex extends Component
{
    public function render()
    {
        // Get active exams that are currently running or upcoming
        $exams = Exam::where('is_active', true)
            ->with('wave')
            ->withCount(['participants', 'sessions', 'sessions as active_sessions_count' => function ($query) {
                $query->where('status', 'started');
            }, 'sessions as completed_sessions_count' => function ($query) {
                $query->whereIn('status', ['completed', 'finished']);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.monitor-index', [
            'exams' => $exams
        ])->layout('layouts.app');
    }
}
