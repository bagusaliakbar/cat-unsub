<?php

namespace App\Livewire\Participant;

use App\Models\ExamSession;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class History extends Component
{
    use WithPagination;

    public function render()
    {
        $sessions = ExamSession::with('exam')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->paginate(10);

        return view('livewire.participant.history', compact('sessions'))
            ->layout('layouts.app');
    }
}
