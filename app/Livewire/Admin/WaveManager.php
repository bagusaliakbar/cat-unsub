<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class WaveManager extends Component
{
    public $waves;
    public $showModal = false;
    public $isEdit = false;
    public $wave_id, $name, $description, $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadWaves();
    }

    public function loadWaves()
    {
        $this->waves = \App\Models\Wave::withCount('users')->get();
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $this->isEdit = true;
        
        $wave = \App\Models\Wave::findOrFail($id);
        $this->wave_id = $wave->id;
        $this->name = $wave->name;
        $this->description = $wave->description;
        $this->is_active = $wave->is_active;
        
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        \App\Models\Wave::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        $this->loadWaves();
    }

    public function update()
    {
        $this->validate();

        $wave = \App\Models\Wave::findOrFail($this->wave_id);
        $wave->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        $this->loadWaves();
    }

    public function delete($id)
    {
        $wave = \App\Models\Wave::withCount('users')->findOrFail($id);
        
        if ($wave->users_count > 0) {
            // Cannot delete wave with users
            return;
        }

        $wave->delete();
        $this->loadWaves();
    }

    public function toggleActive($id)
    {
        $wave = \App\Models\Wave::findOrFail($id);
        $wave->update(['is_active' => !$wave->is_active]);
        $this->loadWaves();
    }

    public function resetFields()
    {
        $this->wave_id = null;
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    public function render()
    {
        return view('livewire.admin.wave-manager')->layout('layouts.app');
    }
}
