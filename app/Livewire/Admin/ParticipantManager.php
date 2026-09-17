<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ParticipantManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $isModalOpen = false;
    public $user_id, $name, $nik, $email, $password, $institution, $participant_number, $wave_id;
    public $birth_place, $birth_date, $latest_education, $address;
    public $photo; // For uploading new photo
    public $existing_photo; // To show existing photo
    public $filter_wave = '';
    public $search = '';

    public function render()
    {
        $query = User::with('wave')->whereIn('role', ['peserta', 'participant']);

        if ($this->filter_wave) {
            $query->where('wave_id', $this->filter_wave);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->search . '%');
            });
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $waves = \App\Models\Wave::where('is_active', true)->get();

        return view('livewire.admin.participant-manager', compact('participants', 'waves'))
            ->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->user_id = null;
        $this->name = '';
        $this->nik = '';
        $this->email = '';
        $this->password = '';
        $this->institution = '';
        $this->participant_number = '';
        $this->wave_id = null;
        $this->birth_place = '';
        $this->birth_date = null;
        $this->latest_education = '';
        $this->address = '';
        $this->photo = null;
        $this->existing_photo = null;
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nik' => [
                'nullable', 'string', 'max:50',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'participant_number' => [
                'nullable', 'string', 'max:50',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'institution' => 'nullable|string|max:255',
            'wave_id' => 'nullable|exists:waves,id',
            'email' => [
                'nullable', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'latest_education' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ];

        // Password is required only on create
        if (!$this->user_id) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'nik' => $this->nik ?: null,
            'participant_number' => $this->participant_number ?: null,
            'institution' => $this->institution ?: null,
            'wave_id' => $this->wave_id ?: null,
            'email' => $this->email ?: null,
            'birth_place' => $this->birth_place ?: null,
            'birth_date' => $this->birth_date ?: null,
            'latest_education' => $this->latest_education ?: null,
            'address' => $this->address ?: null,
            'role' => 'participant',
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->photo) {
            // Delete old photo if it exists
            if ($this->user_id) {
                $user = User::find($this->user_id);
                if ($user && $user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
            }
            $data['profile_photo_path'] = $this->photo->store('profile-photos', 'public');
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message', $this->user_id ? 'Peserta berhasil diperbarui.' : 'Peserta berhasil ditambahkan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->nik = $user->nik;
        $this->participant_number = $user->participant_number;
        $this->institution = $user->institution;
        $this->wave_id = $user->wave_id;
        $this->email = $user->email;
        $this->birth_place = $user->birth_place;
        $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : null;
        $this->latest_education = $user->latest_education;
        $this->address = $user->address;
        $this->password = ''; // Leave password blank on edit
        $this->existing_photo = $user->profile_photo_path;

        $this->openModal();
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->delete();
        }
        session()->flash('message', 'Peserta berhasil dihapus.');
    }
}
