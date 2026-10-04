<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Exports\ParticipantsExport;
use App\Exports\ParticipantTemplateExport;
use App\Imports\ParticipantsImport;
use Maatwebsite\Excel\Facades\Excel;
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
    public $isImportModalOpen = false;
    public $importFile;
    public $user_id, $name, $nik, $email, $password, $institution, $participant_number, $wave_id;
    public $birth_place, $birth_date, $latest_education, $address;
    public $photo; // For uploading new photo
    public $existing_photo_url; // To show existing photo
    public $filter_wave = '';
    public $filter_institution = '';
    public $search = '';

    // Batch Deletion
    public $selected_participants = [];
    public $isDeleteBatchModalOpen = false;

    public function updatedFilterWave()
    {
        $this->resetPage();
    }

    public function updatedFilterInstitution()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('wave')->whereIn('role', ['peserta', 'participant']);

        if ($this->filter_wave) {
            $query->where('wave_id', $this->filter_wave);
        }

        if ($this->filter_institution) {
            $query->where('institution', $this->filter_institution);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->search . '%')
                  ->orWhere('institution', 'like', '%' . $this->search . '%')
                  ->orWhere('birth_place', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $waves = \App\Models\Wave::where('is_active', true)->get();

        $institutions = User::whereIn('role', ['peserta', 'participant'])
            ->whereNotNull('institution')
            ->where('institution', '!=', '')
            ->distinct()
            ->orderBy('institution')
            ->pluck('institution')
            ->toArray();

        $currentPageIds = $participants->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $isAllSelected = !empty($currentPageIds) && count(array_intersect($currentPageIds, $this->selected_participants)) === count($currentPageIds);

        // Stats for batch delete modal
        $selectedCount = count($this->selected_participants);
        $withExamsCount = 0;
        $safeCount = 0;
        if ($this->isDeleteBatchModalOpen && $selectedCount > 0) {
            $withExamsCount = \App\Models\ExamSession::whereIn('user_id', $this->selected_participants)->distinct('user_id')->count('user_id');
            $safeCount = max(0, $selectedCount - $withExamsCount);
        }

        return view('livewire.admin.participant-manager', compact(
            'participants',
            'waves',
            'institutions',
            'isAllSelected',
            'selectedCount',
            'withExamsCount',
            'safeCount'
        ))->layout('layouts.app');
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
        $this->existing_photo_url = null;
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
            'photo' => 'nullable', 
        ];

        // Password is required only on create
        if (!$this->user_id) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        $this->validate($rules);

        $participantNumber = $this->participant_number ?: null;
        if (!$this->user_id && empty($this->nik) && empty($this->email) && empty($participantNumber)) {
            $participantNumber = 'PST-' . date('ymd') . rand(100, 999);
        }

        $data = [
            'name' => $this->name,
            'nik' => $this->nik ?: null,
            'participant_number' => $participantNumber,
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
            try {
                // Delete old photo if it exists
                if ($this->user_id) {
                    $user = User::find($this->user_id);
                    if ($user && $user->profile_photo_path) {
                        Storage::disk('public')->delete($user->profile_photo_path);
                    }
                }
                
                // Upload new photo
                $data['profile_photo_path'] = $this->photo->store('profile-photos', 'public');
            } catch (\Exception $e) {
                // If storage fails, log it and proceed without updating the photo
                \Log::error('Photo upload failed: ' . $e->getMessage());
                session()->flash('error', 'Data tersimpan, tapi foto gagal diunggah: ' . $e->getMessage());
            }
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
        
        $this->existing_photo_url = $user->profile_photo_path ? route('storage.file', $user->profile_photo_path) : null;
        $this->photo = null;

        $this->openModal();
    }

    public function toggleParticipant($id)
    {
        $id = (string) $id;
        if (in_array($id, $this->selected_participants)) {
            $this->selected_participants = array_values(array_diff($this->selected_participants, [$id]));
        } else {
            $this->selected_participants[] = $id;
        }
    }

    public function toggleSelectAll()
    {
        $query = User::whereIn('role', ['peserta', 'participant']);
        if ($this->filter_wave) {
            $query->where('wave_id', $this->filter_wave);
        }
        if ($this->filter_institution) {
            $query->where('institution', $this->filter_institution);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->search . '%')
                  ->orWhere('institution', 'like', '%' . $this->search . '%')
                  ->orWhere('birth_place', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $currentPageIds = $query->orderBy('created_at', 'desc')->paginate(10)->pluck('id')->map(fn($id) => (string)$id)->toArray();

        if (empty($currentPageIds)) {
            return;
        }

        $allSelected = count(array_intersect($currentPageIds, $this->selected_participants)) === count($currentPageIds);

        if ($allSelected) {
            $this->selected_participants = array_values(array_diff($this->selected_participants, $currentPageIds));
        } else {
            $this->selected_participants = array_values(array_unique(array_merge($this->selected_participants, $currentPageIds)));
        }
    }

    public function selectAllFiltered()
    {
        $query = User::whereIn('role', ['peserta', 'participant']);
        if ($this->filter_wave) {
            $query->where('wave_id', $this->filter_wave);
        }
        if ($this->filter_institution) {
            $query->where('institution', $this->filter_institution);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->search . '%')
                  ->orWhere('institution', 'like', '%' . $this->search . '%')
                  ->orWhere('birth_place', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $this->selected_participants = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }

    public function deselectAll()
    {
        $this->selected_participants = [];
    }

    public function openDeleteBatchModal()
    {
        if (empty($this->selected_participants)) {
            return;
        }
        $this->isDeleteBatchModalOpen = true;
    }

    public function closeDeleteBatchModal()
    {
        $this->isDeleteBatchModalOpen = false;
    }

    public function deleteBatch()
    {
        if (empty($this->selected_participants)) {
            $this->closeDeleteBatchModal();
            return;
        }

        $users = User::whereIn('id', $this->selected_participants)->get();
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($users as $user) {
            // Check if user has an exam session (nilai / riwayat ujian)
            $hasExamSessions = \App\Models\ExamSession::where('user_id', $user->id)->exists();
            if ($hasExamSessions) {
                $skippedCount++;
                continue; // Protect participants with exam results!
            }

            // Delete profile photo
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Detach from exams if assigned
            $user->exams()->detach();

            // Delete user
            $user->delete();
            $deletedCount++;
        }

        \App\Services\LogService::record(
            'delete_batch_participants',
            "Admin menghapus massal peserta: {$deletedCount} berhasil dihapus, {$skippedCount} dilewati (karena memiliki riwayat ujian)."
        );

        $msg = "Berhasil menghapus {$deletedCount} data peserta.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} peserta dilewati karena sudah memiliki riwayat ujian/nilai).";
        }

        session()->flash('message', $msg);
        $this->selected_participants = [];
        $this->closeDeleteBatchModal();
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $hasExamSessions = \App\Models\ExamSession::where('user_id', $user->id)->exists();
            if ($hasExamSessions) {
                session()->flash('error', "Peserta '{$user->name}' tidak dapat dihapus karena sudah memiliki riwayat ujian/nilai.");
                return;
            }

            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->exams()->detach();
            $user->delete();
        }
        $this->selected_participants = array_values(array_diff($this->selected_participants, [(string)$id]));
        session()->flash('message', 'Peserta berhasil dihapus.');
    }

    public function export()
    {
        \App\Services\LogService::record('export_participants', 'Admin mengekspor data peserta ke Excel.');
        $filename = 'data_peserta_cat_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ParticipantsExport($this->filter_wave, $this->search), $filename);
    }

    public function downloadTemplate()
    {
        return Excel::download(new ParticipantTemplateExport, 'template_import_peserta.xlsx');
    }

    public function openImportModal()
    {
        $this->importFile = null;
        $this->resetValidation('importFile');
        $this->isImportModalOpen = true;
    }

    public function closeImportModal()
    {
        $this->isImportModalOpen = false;
        $this->importFile = null;
        $this->resetValidation('importFile');
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'importFile.required' => 'Silakan pilih file Excel/CSV terlebih dahulu.',
            'importFile.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'importFile.max' => 'Ukuran file maksimal adalah 10 MB.',
        ]);

        try {
            $import = new ParticipantsImport();
            Excel::import($import, $this->importFile);

            \App\Services\LogService::record(
                'import_participants',
                "Admin mengimpor data peserta: {$import->importedCount} berhasil, {$import->skippedCount} dilewati."
            );

            $msg = "Berhasil mengimpor {$import->importedCount} data peserta.";
            if ($import->skippedCount > 0) {
                $previewSkips = implode('; ', array_slice($import->messages, 0, 3));
                $more = count($import->messages) > 3 ? ' dan ' . (count($import->messages) - 3) . ' lainnya.' : '.';
                $msg .= " ({$import->skippedCount} baris dilewati: {$previewSkips}{$more})";
            }

            session()->flash('message', $msg);
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->addError('importFile', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
