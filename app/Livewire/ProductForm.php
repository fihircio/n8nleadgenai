<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Validation\Rule;

class ProductForm extends Component
{
    public $productId;
    public $sku;
    public $name;
    public $description;
    public $category_id;
    public $price;
    public $cost;
    public $barcode;
    public $unit = 'piece';
    public $track_serial = false;
    public $track_expiry = false;
    public $min_stock = 0;
    public $max_stock;
    public $active = true;
    public $metadata = [];

    public $categories = [];

    public function mount($product = null)
    {
        $this->categories = Category::orderBy('name')->get();
        if ($product) {
            $p = Product::findOrFail($product);
            $this->productId = $p->id;
            $this->sku = $p->sku;
            $this->name = $p->name;
            $this->description = $p->description;
            $this->category_id = $p->category_id;
            $this->price = $p->price;
            $this->cost = $p->cost;
            $this->barcode = $p->barcode;
            $this->unit = $p->unit;
            $this->track_serial = $p->track_serial;
            $this->track_expiry = $p->track_expiry;
            $this->min_stock = $p->min_stock;
            $this->max_stock = $p->max_stock;
            $this->active = $p->active;
            $this->metadata = $p->metadata ?? [];
        }
    }

    public function rules()
    {
        return [
            'sku' => ['required', 'string', Rule::unique('products', 'sku')->ignore($this->productId)],
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'barcode' => ['nullable', Rule::unique('products', 'barcode')->ignore($this->productId)],
            'unit' => 'required|string',
            'track_serial' => 'boolean',
            'track_expiry' => 'boolean',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();
        $data = $this->only([
            'sku', 'name', 'description', 'category_id', 'price', 'cost', 'barcode', 'unit',
            'track_serial', 'track_expiry', 'min_stock', 'max_stock', 'active', 'metadata'
        ]);
        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash('message', 'Product updated successfully.');
        } else {
            $product = Product::create($data);
            session()->flash('message', 'Product created successfully.');
        }
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product-form', [
            'categories' => $this->categories,
        ]);
    }
}
