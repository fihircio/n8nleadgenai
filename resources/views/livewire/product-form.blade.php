<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">{{ $productId ? 'Edit Product' : 'Add Product' }}</h1>

    <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">SKU <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="sku" class="border rounded px-3 py-2 w-full" />
                @error('sku') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Barcode</label>
                <input type="text" wire:model.defer="barcode" class="border rounded px-3 py-2 w-full" />
                @error('barcode') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="name" class="border rounded px-3 py-2 w-full" />
                @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Category <span class="text-red-500">*</span></label>
                <select wire:model.defer="category_id" class="border rounded px-3 py-2 w-full">
                    <option value="">Select Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Price <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" wire:model.defer="price" class="border rounded px-3 py-2 w-full" />
                @error('price') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Cost</label>
                <input type="number" step="0.01" wire:model.defer="cost" class="border rounded px-3 py-2 w-full" />
                @error('cost') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Unit</label>
                <input type="text" wire:model.defer="unit" class="border rounded px-3 py-2 w-full" />
                @error('unit') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Min Stock</label>
                <input type="number" wire:model.defer="min_stock" class="border rounded px-3 py-2 w-full" />
                @error('min_stock') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Max Stock</label>
                <input type="number" wire:model.defer="max_stock" class="border rounded px-3 py-2 w-full" />
                @error('max_stock') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div>
            <label class="block font-medium">Description</label>
            <textarea wire:model.defer="description" class="border rounded px-3 py-2 w-full" rows="3"></textarea>
            @error('description') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model.defer="track_serial" /> Track Serial
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model.defer="track_expiry" /> Track Expiry
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model.defer="active" /> Active
            </label>
        </div>
        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
            <a href="{{ route('products.index') }}" class="bg-gray-200 px-6 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
