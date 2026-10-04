<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithPagination;

class ExamManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $exam_id, $title, $description, $duration_minutes, $passing_grade, $start_time, $end_time, $token, $randomize_questions = false, $randomize_options = false, $is_active = true, $is_simulation = false, $rules, $wave_id, $location;

    protected $rulesArray = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'duration_minutes' => 'required|integer|min:1',
        'passing_grade' => 'required|numeric|min:0|max:100',
        'start_time' => 'nullable|date',
        'end_time' => 'nullable|date|after_or_equal:start_time',
        'token' => 'nullable|string|max:50',
        'randomize_questions' => 'boolean',
        'randomize_options' => 'boolean',
        'is_active' => 'boolean',
        'is_simulation' => 'boolean',
        'rules' => 'nullable|string',
        'wave_id' => 'nullable|exists:waves,id',
        'location' => 'nullable|string|max:255',
    ];

    public $isQuestionModalOpen = false;
    public $managing_exam_id = null;
    public $managing_exam_title = '';
    public $selected_questions = [];
    public $filter_category = '';
    public $filter_type = '';
    public $search_question = '';

    public $isAssignModalOpen = false;
    public $assigning_exam_id = null;
    public $assigning_exam_title = '';
    public $selected_participants = [];
    public $assigning_filter_wave = '';
    public $assigning_filter_institution = '';
    public $assigning_filter_desa = '';
    public $assigning_filter_kecamatan = '';
    public $assigning_search = '';

    // Archive and Filter Tabs
    public $tab = 'active'; // 'active', 'archived', 'all'
    public $search_exam = '';
    public $filter_wave_exam = '';

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function archiveExam($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->update([
            'is_archived' => true,
            'is_active' => false,
        ]);
        \App\Services\LogService::record('archive_exam', 'Admin mengarsipkan ujian: ' . $exam->title);
        session()->flash('message', 'Ujian "' . $exam->title . '" berhasil diarsipkan.');
    }

    public function unarchiveExam($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->update([
            'is_archived' => false,
        ]);
        \App\Services\LogService::record('unarchive_exam', 'Admin mengembalikan ujian dari arsip: ' . $exam->title);
        session()->flash('message', 'Ujian "' . $exam->title . '" berhasil dikeluarkan dari arsip.');
    }

    public function toggleActive($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->update([
            'is_active' => !$exam->is_active,
        ]);
        \App\Services\LogService::record('toggle_exam_status', 'Admin mengubah status aktif ujian: ' . $exam->title);
        session()->flash('message', 'Status ujian "' . $exam->title . '" berhasil diubah.');
    }

    public function render()
    {
        $countActive = Exam::where('is_archived', false)->count();
        $countArchived = Exam::where('is_archived', true)->count();
        $countAll = Exam::count();

        $examQuery = Exam::with('wave')
            ->withCount([
                'participants',
                'sessions as completed_sessions_count' => function ($q) {
                    $q->where('status', 'completed')->orWhereNotNull('completed_at');
                }
            ])
            ->orderBy('created_at', 'desc');

        if ($this->tab === 'active') {
            $examQuery->where('is_archived', false);
        } elseif ($this->tab === 'archived') {
            $examQuery->where('is_archived', true);
        }

        if ($this->search_exam) {
            $search = $this->search_exam;
            $examQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($this->filter_wave_exam) {
            $examQuery->where('wave_id', $this->filter_wave_exam);
        }

        $exams = $examQuery->paginate(10);
        
        $categories = \App\Models\QuestionCategory::all();
        $query = \App\Models\Question::with('category');
        
        if ($this->filter_category) {
            $query->where('category_id', $this->filter_category);
        }

        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }

        if ($this->search_question) {
            $query->where('text', 'like', '%' . $this->search_question . '%');
        }

        $bank_questions = $query->orderBy('created_at', 'desc')->get();

        $total_points = empty($this->selected_questions) 
            ? 0 
            : \App\Models\Question::whereIn('id', $this->selected_questions)->sum('points');

        $participantQuery = \App\Models\User::whereIn('role', ['peserta', 'participant'])
            ->with('wave')
            ->orderByRaw('CASE WHEN no_meja IS NULL OR no_meja = "" THEN 1 ELSE 0 END, CAST(no_meja AS UNSIGNED) ASC, no_meja ASC, name ASC');
        
        if ($this->assigning_filter_wave) {
            $participantQuery->where('wave_id', $this->assigning_filter_wave);
        }

        $effectiveDesa = $this->assigning_filter_desa ?: $this->assigning_filter_institution;
        if ($effectiveDesa) {
            $participantQuery->where('desa', $effectiveDesa);
        }

        if ($this->assigning_filter_kecamatan) {
            $participantQuery->where('kecamatan', $this->assigning_filter_kecamatan);
        }

        if ($this->assigning_search) {
            $participantQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('nik', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('desa', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('no_meja', 'like', '%' . $this->assigning_search . '%');
            });
        }
        
        $all_participants = $participantQuery->get();
        $current_participant_ids = $all_participants->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $is_all_participants_selected = !empty($current_participant_ids) && 
            count(array_intersect($current_participant_ids, $this->selected_participants)) === count($current_participant_ids);
        
        $waves = \App\Models\Wave::where('is_active', true)->get();

        $desas = \App\Models\User::whereIn('role', ['peserta', 'participant'])
            ->whereNotNull('desa')
            ->where('desa', '!=', '')
            ->distinct()
            ->orderBy('desa')
            ->pluck('desa');

        $kecamatans = \App\Models\User::whereIn('role', ['peserta', 'participant'])
            ->whereNotNull('kecamatan')
            ->where('kecamatan', '!=', '')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $institutions = $desas;

        return view('livewire.admin.exam-manager', compact('exams', 'countActive', 'countArchived', 'countAll', 'categories', 'bank_questions', 'total_points', 'all_participants', 'waves', 'institutions', 'desas', 'kecamatans', 'is_all_participants_selected'))
            ->layout('layouts.app'); // Assuming breeze layout
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isQuestionModalOpen = false;
        $this->isAssignModalOpen = false;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->exam_id = null;
        $this->title = '';
        $this->description = '';
        $this->duration_minutes = '';
        $this->passing_grade = 0;
        $this->start_time = null;
        $this->end_time = null;
        $this->token = '';
        $this->randomize_questions = false;
        $this->randomize_options = false;
        $this->is_active = true;
        $this->is_simulation = false;
        $this->rules = '';
        $this->wave_id = null;
        $this->location = '';
    }

    public function store()
    {
        $this->validate($this->rulesArray);

        Exam::updateOrCreate(['id' => $this->exam_id], [
            'title' => $this->title,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'passing_grade' => $this->passing_grade,
            'start_time' => $this->start_time ?: null,
            'end_time' => $this->end_time ?: null,
            'token' => $this->token,
            'randomize_questions' => $this->randomize_questions ? 1 : 0,
            'randomize_options' => $this->randomize_options ? 1 : 0,
            'is_active' => $this->is_active ? 1 : 0,
            'is_simulation' => $this->is_simulation ? 1 : 0,
            'rules' => $this->rules,
            'wave_id' => $this->wave_id ?: null,
            'location' => $this->location,
        ]);

        \App\Services\LogService::record($this->exam_id ? 'edit_exam' : 'create_exam', 'Admin ' . ($this->exam_id ? 'mengubah' : 'membuat') . ' ujian: ' . $this->title);

        session()->flash('message', $this->exam_id ? 'Ujian berhasil diperbarui.' : 'Ujian berhasil dibuat.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $this->exam_id = $id;
        $this->title = $exam->title;
        $this->description = $exam->description;
        $this->duration_minutes = $exam->duration_minutes;
        $this->passing_grade = $exam->passing_grade;
        $this->start_time = $exam->start_time ? $exam->start_time->format('Y-m-d\TH:i') : null;
        $this->end_time = $exam->end_time ? $exam->end_time->format('Y-m-d\TH:i') : null;
        $this->token = $exam->token;
        $this->randomize_questions = (bool) $exam->randomize_questions;
        $this->randomize_options = $exam->randomize_options;
        $this->is_active = $exam->is_active;
        $this->is_simulation = $exam->is_simulation;
        $this->rules = $exam->rules;
        $this->wave_id = $exam->wave_id;
        $this->location = $exam->location;

        $this->openModal();
    }

    // --- Question Management for Exam ---

    public function manageQuestions($id)
    {
        $this->isModalOpen = false;
        $this->isAssignModalOpen = false;
        \Log::info("manageQuestions called for ID: " . $id);
        $exam = Exam::with('questions')->findOrFail($id);
        $this->managing_exam_id = $exam->id;
        $this->managing_exam_title = $exam->title;
        $this->selected_questions = $exam->questions->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->isQuestionModalOpen = true;
        \Log::info("isQuestionModalOpen set to true, selected questions: " . count($this->selected_questions));
    }

    public function resetSelectedQuestions()
    {
        $this->selected_questions = [];
    }

    public function closeQuestionModal()
    {
        $this->isQuestionModalOpen = false;
        $this->managing_exam_id = null;
        $this->selected_questions = [];
    }

    public function syncQuestions()
    {
        $exam = Exam::findOrFail($this->managing_exam_id);
        $exam->questions()->sync($this->selected_questions);
        $exam->update(['total_questions' => count($this->selected_questions)]);
        
        \App\Services\LogService::record('sync_questions', 'Admin mengatur ulang daftar soal pada ujian: ' . $exam->title);
        
        session()->flash('message', 'Soal berhasil ditautkan ke ujian.');
        $this->closeQuestionModal();
    }

    public function toggleQuestion($id)
    {
        $id = (string) $id;
        if (in_array($id, $this->selected_questions)) {
            $this->selected_questions = array_values(array_diff($this->selected_questions, [$id]));
        } else {
            $this->selected_questions[] = $id;
        }
    }

    // --- Participant Management for Exam ---

    public function manageParticipants($id)
    {
        $this->isModalOpen = false;
        $this->isQuestionModalOpen = false;
        $exam = Exam::with('participants')->findOrFail($id);
        $this->assigning_exam_id = $exam->id;
        $this->assigning_exam_title = $exam->title;
        $this->selected_participants = $exam->participants->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->assigning_filter_wave = '';
        $this->assigning_filter_institution = '';
        $this->assigning_search = '';
        $this->isAssignModalOpen = true;
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

    public function toggleSelectAllParticipants()
    {
        $participantQuery = \App\Models\User::whereIn('role', ['peserta', 'participant']);
        if ($this->assigning_filter_wave) {
            $participantQuery->where('wave_id', $this->assigning_filter_wave);
        }
        $effectiveDesa = $this->assigning_filter_desa ?: $this->assigning_filter_institution;
        if ($effectiveDesa) {
            $participantQuery->where('desa', $effectiveDesa);
        }
        if ($this->assigning_filter_kecamatan) {
            $participantQuery->where('kecamatan', $this->assigning_filter_kecamatan);
        }
        if ($this->assigning_search) {
            $participantQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('nik', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('participant_number', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('desa', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $this->assigning_search . '%')
                  ->orWhere('no_meja', 'like', '%' . $this->assigning_search . '%');
            });
        }
        $currentIds = $participantQuery->pluck('id')->map(fn($id) => (string)$id)->toArray();

        if (empty($currentIds)) {
            return;
        }

        $allSelected = count(array_intersect($currentIds, $this->selected_participants)) === count($currentIds);

        if ($allSelected) {
            $this->selected_participants = array_values(array_diff($this->selected_participants, $currentIds));
        } else {
            $this->selected_participants = array_values(array_unique(array_merge($this->selected_participants, $currentIds)));
        }
    }

    public function resetAssigningFilters()
    {
        $this->assigning_filter_wave = '';
        $this->assigning_filter_institution = '';
        $this->assigning_filter_desa = '';
        $this->assigning_filter_kecamatan = '';
        $this->assigning_search = '';
    }

    public function closeAssignModal()
    {
        $this->isAssignModalOpen = false;
        $this->assigning_exam_id = null;
        $this->selected_participants = [];
        $this->assigning_filter_wave = '';
        $this->assigning_filter_institution = '';
        $this->assigning_filter_desa = '';
        $this->assigning_filter_kecamatan = '';
        $this->assigning_search = '';
    }

    public function syncParticipants()
    {
        $exam = Exam::findOrFail($this->assigning_exam_id);
        $exam->participants()->sync($this->selected_participants);
        
        \App\Services\LogService::record('sync_participants', 'Admin menugaskan peserta pada ujian: ' . $exam->title);
        
        session()->flash('message', 'Peserta berhasil ditugaskan ke ujian.');
        $this->closeAssignModal();
    }

    public function delete($id)
    {
        $exam = Exam::find($id);
        if ($exam) {
            \App\Services\LogService::record('delete_exam', 'Admin menghapus ujian: ' . $exam->title);
            $exam->delete();
        }
        session()->flash('message', 'Ujian berhasil dihapus.');
    }
}
