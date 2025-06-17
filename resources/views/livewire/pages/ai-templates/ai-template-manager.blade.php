<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">AI Templates Marketplace</h2>
                    
                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    @error('purchase')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($templates as $template)
                            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                                <h3 class="text-xl font-semibold mb-2">{{ $template->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ $template->description }}</p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm text-gray-500">
                                        Provider: {{ ucfirst($template->provider) }}
                                    </span>
                                    <span class="text-sm font-semibold text-indigo-600">
                                        {{ $template->cost_in_coins }} coins
                                    </span>
                                </div>

                                <button wire:click="purchase({{ $template->id }})"
                                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Purchase & Generate
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $templates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (data) => {
            // You can use your preferred notification library here
            alert(data.message);
            if (data.content) {
                console.log('Generated content:', data.content);
            }
        });
    });
</script>
@endpush 