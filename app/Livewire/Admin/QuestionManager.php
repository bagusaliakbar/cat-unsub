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
    
    // Filters
    public $filter_category = '';
    public $filter_difficulty = '';
    public $filter_type = '';
    
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

        $questions = $query->latest()->paginate(10);

        return view('livewire.admin.question-manager', compact('questions', 'categories'))
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
    
    public function import()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv'
        ]);
        
        Excel::import(new QuestionsImport, $this->importFile);
        
        \App\Services\LogService::record('import_questions', 'Admin mengimpor soal dari file Excel: ' . $this->importFile->getClientOriginalName());
        
        session()->flash('message', 'Soal berhasil diimpor dari file Excel.');
        $this->closeImportModal();
    }
}
