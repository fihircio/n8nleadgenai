<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search'];

    protected $listeners = ['categoryDeleted' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $this->emit('categoryDeleted');
        session()->flash('message', 'Category deleted successfully.');
    }

    public function render()
    {
        $categories = Category::with('parent')
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.category-index', [
            'categories' => $categories,
        ]);
    }
}
