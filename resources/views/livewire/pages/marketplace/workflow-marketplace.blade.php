<div class="max-w-5xl mx-auto py-10">
    <h1 class="text-4xl font-bold mb-2">Workflow Marketplace</h1>
    <p class="mb-8 text-gray-600">Purchase ready-to-use automated workflows with your coin balance</p>

    <!-- Category Tabs -->
    <div class="flex gap-2 mb-8">
        @foreach ($this->categories as $key => $label)
            <button wire:click="selectCategory('{{ $key }}')"
                class="px-4 py-2 rounded-full font-semibold text-sm
                    {{ $activeCategory === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($filteredTemplates as $tpl)
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-start">
                <div class="flex items-center gap-2 mb-2">
                    <img src="/{{ $tpl->icon }}" alt="icon" class="w-8 h-8">
                    <span class="text-xs px-2 py-1 rounded bg-gray-200 font-semibold">{{ ucfirst($tpl->category) }}</span>
                </div>
                <h2 class="text-lg font-bold mb-1">{{ $tpl->title }}</h2>
                <p class="text-gray-500 text-sm mb-2">{{ $tpl->description }}</p>
                <div class="mb-3 text-sm font-semibold text-yellow-600">{{ $tpl->coin_cost }} Coins per execution</div>
                <div class="flex gap-2 mt-auto">
                    <button wire:click="previewTemplate({{ $tpl->id }})" class="px-3 py-1 bg-gray-100 rounded hover:bg-blue-100 text-blue-700 font-semibold">Preview</button>
                    <button class="px-3 py-1 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700">Purchase & Run</button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400">No templates in this category yet.</div>
        @endforelse
    </div>

    <!-- Preview Modal -->
    @if ($showModal && $selectedTemplate)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-lg w-full p-6 relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                <div class="flex items-center gap-2 mb-2">
                    <img src="/{{ $selectedTemplate->icon }}" alt="icon" class="w-8 h-8">
                    <span class="text-xs px-2 py-1 rounded bg-gray-200 font-semibold">{{ ucfirst($selectedTemplate->category) }}</span>
                </div>
                <h2 class="text-xl font-bold mb-1">{{ $selectedTemplate->title }}</h2>
                <p class="text-gray-500 text-sm mb-2">{{ $selectedTemplate->description }}</p>
                <div class="mb-3 text-sm font-semibold text-yellow-600">{{ $selectedTemplate->coin_cost }} Coins per execution</div>
                <div class="mb-4">
                    <h3 class="font-semibold mb-1">Inputs</h3>
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        @if(is_array($selectedTemplate->inputs))
                            @foreach ($selectedTemplate->inputs as $input)
                                <li>{{ ucfirst($input) }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="mb-4">
                    <h3 class="font-semibold mb-1">Sample Output</h3>
                    <div class="bg-gray-50 border rounded p-2 text-xs text-gray-600">{{ $selectedTemplate->sample_output }}</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200 text-gray-700 font-semibold">Close</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700">Confirm & Run</button>
                </div>
            </div>
        </div>
    @endif
</div>
