<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Validation\Rule;

class CategoryForm extends Component
{
    public $categoryId;
    public $name;
    public $slug;
    public $description;
    public $parent_id;
    public $sort_order = 0;
    public $active = true;

    public $categories = [];

    public function mount($category = null)
    {
        $this->categories = Category::whereNull('parent_id')->orderBy('name')->get();
        if ($category) {
            $c = Category::findOrFail($category);
            $this->categoryId = $c->id;
            $this->name = $c->name;
            $this->slug = $c->slug;
            $this->description = $c->description;
            $this->parent_id = $c->parent_id;
            $this->sort_order = $c->sort_order;
            $this->active = $c->active;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('categories', 'slug')->ignore($this->categoryId)],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();
        $data = $this->only(['name', 'slug', 'description', 'parent_id', 'sort_order', 'active']);
        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($data);
            session()->flash('message', 'Category updated successfully.');
        } else {
            $category = Category::create($data);
            session()->flash('message', 'Category created successfully.');
        }
        return redirect()->route('categories.index');
    }

    public function render()
    {
        return view('livewire.category-form', [
            'categories' => $this->categories,
        ]);
    }
}
