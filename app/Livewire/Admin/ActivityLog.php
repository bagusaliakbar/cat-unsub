<?php

namespace App\Livewire\Admin;

use App\Models\SystemLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLog extends Component
{
    use WithPagination;

    public $search = '';
    public $action = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingAction()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SystemLog::with('user')->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('nik', 'like', '%' . $this->search . '%');
                })->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->action) {
            $query->where('action', $this->action);
        }

        $logs = $query->paginate(15);
        $actions = SystemLog::select('action')->distinct()->pluck('action');

        return view('livewire.admin.activity-log', compact('logs', 'actions'))->layout('layouts.app');
    }
}
