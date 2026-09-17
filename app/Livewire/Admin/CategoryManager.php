<?php

namespace App\Livewire\Admin;

use App\Models\QuestionCategory;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $category_id, $name;
    public $search = '';

    public function render()
    {
        $query = QuestionCategory::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $categories = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.admin.category-manager', compact('categories'))
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
        $this->category_id = null;
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:question_categories,name,' . $this->category_id,
        ]);

        QuestionCategory::updateOrCreate(
            ['id' => $this->category_id],
            ['name' => $this->name]
        );

        session()->flash('message', $this->category_id ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = QuestionCategory::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;

        $this->openModal();
    }

    public function delete($id)
    {
        $category = QuestionCategory::findOrFail($id);
        
        // Prevent deletion if questions are attached
        if ($category->questions()->count() > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki soal.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }
}
