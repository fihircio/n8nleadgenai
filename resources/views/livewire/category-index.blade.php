<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Categories</h1>
        <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Category</a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex items-center mb-4">
        <input type="text" wire:model.debounce.500ms="search" placeholder="Search by name or slug..." class="border rounded px-3 py-2 w-full max-w-xs mr-4" />
        <select wire:model="perPage" class="border rounded px-2 py-2">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $category->name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $category->slug }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $category->parent->name ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if ($category->active)
                                <span class="text-green-600 font-semibold">Yes</span>
                            @else
                                <span class="text-red-600 font-semibold">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:underline">Edit</a>
                            <button wire:click="deleteCategory({{ $category->id }})" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
