<?php

namespace App\Livewire;

use App\Models\StockTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class StockTransactionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search'];

    protected $listeners = ['stockTransactionDeleted' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteStockTransaction($id)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->delete();
        $this->emit('stockTransactionDeleted');
        session()->flash('message', 'Stock transaction deleted successfully.');
    }

    public function render()
    {
        $transactions = StockTransaction::with(['product', 'warehouse'])
            ->where(function ($query) {
                $query->where('reference_number', 'like', "%{$this->search}%")
                      ->orWhereHas('product', function ($q) { $q->where('name', 'like', "%{$this->search}%"); })
                      ->orWhereHas('warehouse', function ($q) { $q->where('name', 'like', "%{$this->search}%"); });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.stock-transaction-index', [
            'transactions' => $transactions,
        ]);
    }
}
