<div class="container mx-auto py-8 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">{{ $categoryId ? 'Edit Category' : 'Add Category' }}</h1>

    <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="name" class="border rounded px-3 py-2 w-full" />
                @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Slug <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="slug" class="border rounded px-3 py-2 w-full" />
                @error('slug') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block font-medium">Parent Category</label>
                <select wire:model.defer="parent_id" class="border rounded px-3 py-2 w-full">
                    <option value="">None</option>
                    @foreach ($categories as $cat)
                        @if (!$categoryId || $cat->id != $categoryId)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block font-medium">Description</label>
                <textarea wire:model.defer="description" class="border rounded px-3 py-2 w-full" rows="3"></textarea>
                @error('description') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-medium">Sort Order</label>
                <input type="number" wire:model.defer="sort_order" class="border rounded px-3 py-2 w-full" />
                @error('sort_order') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center gap-2 mt-6">
                <input type="checkbox" wire:model.defer="active" />
                <label class="font-medium">Active</label>
                @error('active') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
            <a href="{{ route('categories.index') }}" class="bg-gray-200 px-6 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
