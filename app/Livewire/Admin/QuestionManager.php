<?php

namespace App\Livewire\Admin;

use App\Models\QuestionCategory;
use App\Models\Question;
use App\Models\Option;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionsExport;
use App\Imports\QuestionsImport;

class QuestionManager extends Component
{
    use WithPagination, WithFileUploads;

    public $importFile;
    public $isImportModalOpen = false;

    public $isModalOpen = false;
    public $isCategoryModalOpen = false;

    // Preview Modal
    public $isPreviewModalOpen = false;
    public $previewQuestionId = null;
    public $previewQuestionIndex = 0;
    public $previewQuestionIds = [];
    
    // Filters
    public $filter_category = '';
    public $filter_difficulty = '';
    public $filter_type = '';
    public $perPage = 10;

    // Multi-Select Batch Delete
    public $selectedQuestions = [];
    public $selectAllOnPage = false;

    // Delete All Modal
    public $isDeleteAllModalOpen = false;
    public $deleteAllConfirmationText = '';
    public $deleteAllScope = 'all'; // 'all' or 'category'
    
    // UI State
    public $viewMode = 'grid';

    // Question form
    public $question_id, $category_id, $text, $type = 'multiple_choice', $difficulty = 'medium', $points = 1, $is_active = true;
    
    // Category form
    public $category_name, $category_description;

    // For multiple choice options
    public $options = [
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
    ];

    protected $rules = [
        'text' => 'required|string',
        'category_id' => 'required|exists:question_categories,id',
        'type' => 'required|string|in:multiple_choice,essay',
        'difficulty' => 'required|string|in:easy,medium,hard',
        'points' => 'required|integer|min:1',
        'is_active' => 'boolean',
        'options.*.text' => 'required_if:type,multiple_choice|string',
        'options.*.is_correct' => 'boolean',
    ];

    public function render()
    {
        $categories = QuestionCategory::all();
        
        $query = Question::with('options', 'category');
        
        if ($this->filter_category) {
            $query->where('category_id', $this->filter_category);
        }
        
        if ($this->filter_difficulty) {
            $query->where('difficulty', $this->filter_difficulty);
        }

        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }

        if ($this->perPage === 'all' || $this->perPage === 'semua' || $this->perPage == -1) {
            $totalCount = (clone $query)->count();
            $questions = $query->latest()->paginate(max(1, $totalCount));
        } else {
            $perPageInt = (int)$this->perPage;
            $validPerPage = in_array($perPageInt, [5, 10, 20, 50, 100]) ? $perPageInt : 10;
            $questions = $query->latest()->paginate($validPerPage);
        }

        $previewQuestion = null;
        if ($this->isPreviewModalOpen && $this->previewQuestionId) {
            $previewQuestion = Question::with('options', 'category')->find($this->previewQuestionId);
        }

        return view('livewire.admin.question-manager', compact('questions', 'categories', 'previewQuestion'))
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

    public function preview($id)
    {
        $this->loadPreviewIds();
        $this->previewQuestionId = (int)$id;
        $this->previewQuestionIndex = array_search((int)$id, $this->previewQuestionIds);
        if ($this->previewQuestionIndex === false) {
            $this->previewQuestionIndex = 0;
        }

        $this->isPreviewModalOpen = true;
    }

    private function loadPreviewIds()
    {
        $query = Question::query();
        if ($this->filter_category) {
            $query->where('category_id', $this->filter_category);
        }
        if ($this->filter_difficulty) {
            $query->where('difficulty', $this->filter_difficulty);
        }
        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }
        $this->previewQuestionIds = $query->latest()->pluck('id')->map(fn($id) => (int)$id)->toArray();
    }

    public function nextPreviewQuestion()
    {
        if ($this->previewQuestionIndex !== false && $this->previewQuestionIndex < count($this->previewQuestionIds) - 1) {
            $this->previewQuestionIndex++;
            $this->previewQuestionId = $this->previewQuestionIds[$this->previewQuestionIndex];
        }
    }

    public function previousPreviewQuestion()
    {
        if ($this->previewQuestionIndex !== false && $this->previewQuestionIndex > 0) {
            $this->previewQuestionIndex--;
            $this->previewQuestionId = $this->previewQuestionIds[$this->previewQuestionIndex];
        }
    }

    public function closePreviewModal()
    {
        $this->isPreviewModalOpen = false;
        $this->previewQuestionId = null;
        $this->previewQuestionIds = [];
        $this->previewQuestionIndex = 0;
    }

    public function editFromPreview($id)
    {
        $this->closePreviewModal();
        $this->edit($id);
    }
    
    public function openCategoryModal()
    {
        $this->isCategoryModalOpen = true;
    }
    
    public function closeCategoryModal()
    {
        $this->isCategoryModalOpen = false;
        $this->category_name = '';
        $this->category_description = '';
    }
    
    public function storeCategory()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
        ]);
        
        QuestionCategory::create([
            'name' => $this->category_name,
            'description' => $this->category_description
        ]);
        
        \App\Services\LogService::record('create_category', 'Admin menambahkan kategori soal baru: ' . $this->category_name);
        
        session()->flash('message', 'Mata Ujian / Kategori berhasil ditambahkan.');
        $this->closeCategoryModal();
    }

    private function resetInputFields()
    {
        $this->question_id = null;
        $this->category_id = $this->filter_category ?: null;
        $this->text = '';
        $this->type = 'multiple_choice';
        $this->difficulty = 'medium';
        $this->points = 1;
        $this->is_active = true;
        
        $this->options = [
            ['text' => '', 'is_correct' => true],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ];
    }

    public function store()
    {
        $this->validate();

        if ($this->type === 'multiple_choice') {
            $correctCount = collect($this->options)->filter(function($opt) {
                return $opt['is_correct'];
            })->count();

            if ($correctCount < 1) {
                $this->addError('options', 'Harus ada minimal satu jawaban yang benar.');
                return;
            }
        }

        $question = Question::updateOrCreate(['id' => $this->question_id], [
            'category_id' => $this->category_id,
            'text' => $this->text,
            'type' => $this->type,
            'difficulty' => $this->difficulty,
            'points' => $this->points,
            'is_active' => $this->is_active ? 1 : 0,
        ]);

        if ($this->type === 'multiple_choice') {
            if ($this->question_id) {
                $question->options()->delete();
            }

            foreach ($this->options as $opt) {
                if (!empty(trim($opt['text']))) {
                    $question->options()->create([
                        'text' => $opt['text'],
                        'is_correct' => $opt['is_correct'] ? 1 : 0,
                    ]);
                }
            }
        }

        \App\Services\LogService::record($this->question_id ? 'edit_question' : 'create_question', 'Admin ' . ($this->question_id ? 'mengubah' : 'membuat') . ' soal ID: ' . $question->id);

        session()->flash('message', $this->question_id ? 'Soal berhasil diperbarui.' : 'Soal berhasil ditambahkan ke Bank Soal.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $question = Question::with('options')->findOrFail($id);
        $this->question_id = $id;
        $this->category_id = $question->category_id;
        $this->text = $question->text;
        $this->type = $question->type;
        $this->difficulty = $question->difficulty;
        $this->points = $question->points;
        $this->is_active = $question->is_active;

        if ($this->type === 'multiple_choice') {
            $this->options = $question->options->map(function($opt) {
                return [
                    'text' => $opt->text,
                    'is_correct' => (bool)$opt->is_correct,
                ];
            })->toArray();
            
            while(count($this->options) < 4) {
                $this->options[] = ['text' => '', 'is_correct' => false];
            }
        }

        $this->openModal();
    }

    public function delete($id)
    {
        Question::find($id)->delete();
        \App\Services\LogService::record('delete_question', 'Admin menghapus soal ID: ' . $id);
        session()->flash('message', 'Soal berhasil dihapus dari Bank Soal.');
    }

    public function export()
    {
        \App\Services\LogService::record('export_questions', 'Admin mengekspor bank soal ke Excel.');
        return Excel::download(new QuestionsExport, 'bank_soal.xlsx');
    }
    
    public function openImportModal()
    {
        $this->isImportModalOpen = true;
    }
    
    public function closeImportModal()
    {
        $this->isImportModalOpen = false;
        $this->importFile = null;
        $this->resetValidation('importFile');
    }
    
    public function updatedFilterCategory()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedFilterDifficulty()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedSelectAllOnPage($value)
    {
        $currentPageIds = $this->getCurrentPageQuestionIds();
        if ($value) {
            $this->selectedQuestions = array_values(array_unique(array_merge($this->selectedQuestions, $currentPageIds)));
        } else {
            $this->selectedQuestions = array_values(array_diff($this->selectedQuestions, $currentPageIds));
        }
    }

    public function selectAllFiltered()
    {
        $query = Question::query();
        if ($this->filter_category) {
            $query->where('category_id', $this->filter_category);
        }
        if ($this->filter_difficulty) {
            $query->where('difficulty', $this->filter_difficulty);
        }
        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }
        $this->selectedQuestions = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selectAllOnPage = true;
    }

    public function clearSelection()
    {
        $this->selectedQuestions = [];
        $this->selectAllOnPage = false;
    }

    private function getCurrentPageQuestionIds(): array
    {
        $query = Question::query();
        if ($this->filter_category) {
            $query->where('category_id', $this->filter_category);
        }
        if ($this->filter_difficulty) {
            $query->where('difficulty', $this->filter_difficulty);
        }
        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }

        if ($this->perPage === 'all' || $this->perPage === 'semua' || $this->perPage == -1) {
            $totalCount = (clone $query)->count();
            return $query->latest()->paginate(max(1, $totalCount))->pluck('id')->map(fn($id) => (string)$id)->toArray();
        }

        $perPageInt = (int)$this->perPage;
        $validPerPage = in_array($perPageInt, [5, 10, 20, 50, 100]) ? $perPageInt : 10;
        return $query->latest()->paginate($validPerPage)->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }

    public function deleteSelected()
    {
        $count = count($this->selectedQuestions);
        if ($count === 0) {
            return;
        }

        $usedCount = \DB::table('user_answers')
            ->whereIn('question_id', $this->selectedQuestions)
            ->distinct()
            ->count('question_id');

        Question::whereIn('id', $this->selectedQuestions)->delete();

        \App\Services\LogService::record('bulk_delete_questions', "Admin menghapus {$count} soal terpilih dari Bank Soal.");

        $msg = "Berhasil menghapus {$count} soal terpilih dari Bank Soal.";
        if ($usedCount > 0) {
            $msg .= " (Catatan: {$usedCount} soal di antaranya memiliki riwayat pengerjaan peserta).";
        }
        session()->flash('message', $msg);

        $this->clearSelection();
    }

    public function openDeleteAllModal($scope = 'all')
    {
        $this->deleteAllScope = $scope;
        $this->deleteAllConfirmationText = '';
        $this->resetErrorBag();
        $this->isDeleteAllModalOpen = true;
    }

    public function closeDeleteAllModal()
    {
        $this->isDeleteAllModalOpen = false;
        $this->deleteAllConfirmationText = '';
        $this->resetErrorBag();
    }

    public function executeDeleteAll()
    {
        if (trim(strtoupper($this->deleteAllConfirmationText)) !== 'HAPUS') {
            $this->addError('deleteAllConfirmationText', 'Ketik kata HAPUS dengan tepat untuk konfirmasi.');
            return;
        }

        if ($this->deleteAllScope === 'category' && $this->filter_category) {
            $cat = QuestionCategory::find($this->filter_category);
            $catName = $cat ? $cat->name : 'Kategori Terpilih';
            $count = Question::where('category_id', $this->filter_category)->count();
            
            Question::where('category_id', $this->filter_category)->delete();

            \App\Services\LogService::record('delete_all_category_questions', "Admin menghapus seluruh soal ({$count} soal) pada kategori '{$catName}'.");
            session()->flash('message', "Seluruh soal ({$count} soal) pada kategori '{$catName}' berhasil dihapus.");
        } else {
            $count = Question::count();
            Question::query()->delete();

            \App\Services\LogService::record('delete_all_questions', "Admin mengosongkan Bank Soal (menghapus {$count} soal).");
            session()->flash('message', "Seluruh Bank Soal ({$count} soal) berhasil dikosongkan.");
        }

        $this->clearSelection();
        $this->closeDeleteAllModal();
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv'
        ]);
        
        try {
            Excel::import(new QuestionsImport, $this->importFile);
            
            \App\Services\LogService::record('import_questions', 'Admin mengimpor soal dari file Excel: ' . $this->importFile->getClientOriginalName());
            
            session()->flash('message', 'Soal berhasil diimpor dari file Excel.');
            $this->closeImportModal();
        } catch (\Throwable $e) {
            $this->addError('importFile', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }
}
