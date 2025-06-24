<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">{{ $transactionId ? 'Edit Stock Transaction' : 'Add Stock Transaction' }}</h1>

    <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Reference Number <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="reference_number" class="border rounded px-3 py-2 w-full" />
                @error('reference_number') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Product <span class="text-red-500">*</span></label>
                <select wire:model.defer="product_id" class="border rounded px-3 py-2 w-full">
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Warehouse <span class="text-red-500">*</span></label>
                <select wire:model.defer="warehouse_id" class="border rounded px-3 py-2 w-full">
                    <option value="">Select Warehouse</option>
                    @foreach ($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
                @error('warehouse_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Type <span class="text-red-500">*</span></label>
                <select wire:model.defer="type" class="border rounded px-3 py-2 w-full">
                    <option value="inbound">Inbound</option>
                    <option value="outbound">Outbound</option>
                    <option value="transfer">Transfer</option>
                    <option value="adjustment">Adjustment</option>
                </select>
                @error('type') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Quantity <span class="text-red-500">*</span></label>
                <input type="number" wire:model.defer="quantity" class="border rounded px-3 py-2 w-full" />
                @error('quantity') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Batch Number</label>
                <input type="text" wire:model.defer="batch_number" class="border rounded px-3 py-2 w-full" />
                @error('batch_number') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Serial Number</label>
                <input type="text" wire:model.defer="serial_number" class="border rounded px-3 py-2 w-full" />
                @error('serial_number') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Expiry Date</label>
                <input type="date" wire:model.defer="expiry_date" class="border rounded px-3 py-2 w-full" />
                @error('expiry_date') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Unit Cost</label>
                <input type="number" step="0.01" wire:model.defer="unit_cost" class="border rounded px-3 py-2 w-full" />
                @error('unit_cost') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Reason</label>
                <input type="text" wire:model.defer="reason" class="border rounded px-3 py-2 w-full" />
                @error('reason') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Source Warehouse</label>
                <select wire:model.defer="source_warehouse_id" class="border rounded px-3 py-2 w-full">
                    <option value="">None</option>
                    @foreach ($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
                @error('source_warehouse_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Destination Warehouse</label>
                <select wire:model.defer="destination_warehouse_id" class="border rounded px-3 py-2 w-full">
                    <option value="">None</option>
                    @foreach ($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
                @error('destination_warehouse_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Created By <span class="text-red-500">*</span></label>
                <select wire:model.defer="created_by" class="border rounded px-3 py-2 w-full">
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('created_by') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Status</label>
                <input type="text" wire:model.defer="status" class="border rounded px-3 py-2 w-full" />
                @error('status') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
            <a href="{{ route('stock-transactions.index') }}" class="bg-gray-200 px-6 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
