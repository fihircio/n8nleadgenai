<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search'];

    protected $listeners = ['productDeleted' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        $this->emit('productDeleted');
        session()->flash('message', 'Product deleted successfully.');
    }

    public function render()
    {
        $products = Product::with('category')
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('sku', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.product-index', [
            'products' => $products,
        ]);
    }
}
