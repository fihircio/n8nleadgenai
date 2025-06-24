<?php

namespace App\Livewire;

use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;

class StockTransactionForm extends Component
{
    public $transactionId;
    public $reference_number;
    public $product_id;
    public $warehouse_id;
    public $type = 'inbound';
    public $quantity;
    public $batch_number;
    public $serial_number;
    public $expiry_date;
    public $unit_cost;
    public $reason;
    public $source_warehouse_id;
    public $destination_warehouse_id;
    public $created_by;
    public $status = 'completed';
    public $metadata = [];

    public $products = [];
    public $warehouses = [];
    public $users = [];

    public function mount($transaction = null)
    {
        $this->products = Product::orderBy('name')->get();
        $this->warehouses = Warehouse::orderBy('name')->get();
        $this->users = User::orderBy('name')->get();
        if ($transaction) {
            $txn = StockTransaction::findOrFail($transaction);
            $this->transactionId = $txn->id;
            $this->reference_number = $txn->reference_number;
            $this->product_id = $txn->product_id;
            $this->warehouse_id = $txn->warehouse_id;
            $this->type = $txn->type;
            $this->quantity = $txn->quantity;
            $this->batch_number = $txn->batch_number;
            $this->serial_number = $txn->serial_number;
            $this->expiry_date = $txn->expiry_date;
            $this->unit_cost = $txn->unit_cost;
            $this->reason = $txn->reason;
            $this->source_warehouse_id = $txn->source_warehouse_id;
            $this->destination_warehouse_id = $txn->destination_warehouse_id;
            $this->created_by = $txn->created_by;
            $this->status = $txn->status;
            $this->metadata = $txn->metadata ?? [];
        }
    }

    public function rules()
    {
        return [
            'reference_number' => ['required', 'string', Rule::unique('stock_transactions', 'reference_number')->ignore($this->transactionId)],
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:inbound,outbound,transfer,adjustment',
            'quantity' => 'required|numeric|min:1',
            'batch_number' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'unit_cost' => 'nullable|numeric',
            'reason' => 'nullable|string',
            'source_warehouse_id' => 'nullable|exists:warehouses,id',
            'destination_warehouse_id' => 'nullable|exists:warehouses,id',
            'created_by' => 'required|exists:users,id',
            'status' => 'required|string',
        ];
    }

    public function save()
    {
        $this->validate();
        $data = $this->only([
            'reference_number', 'product_id', 'warehouse_id', 'type', 'quantity', 'batch_number', 'serial_number', 'expiry_date', 'unit_cost', 'reason', 'source_warehouse_id', 'destination_warehouse_id', 'created_by', 'status', 'metadata'
        ]);
        if ($this->transactionId) {
            $txn = StockTransaction::findOrFail($this->transactionId);
            $txn->update($data);
            session()->flash('message', 'Stock transaction updated successfully.');
        } else {
            StockTransaction::create($data);
            session()->flash('message', 'Stock transaction created successfully.');
        }
        return redirect()->route('stock-transactions.index');
    }

    public function render()
    {
        return view('livewire.stock-transaction-form', [
            'products' => $this->products,
            'warehouses' => $this->warehouses,
            'users' => $this->users,
        ]);
    }
}
