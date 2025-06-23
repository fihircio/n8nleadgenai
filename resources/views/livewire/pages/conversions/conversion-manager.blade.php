<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Conversion Tracking</h2>
                    <button wire:click="createConversion" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Add Conversion
                    </button>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <!-- Conversion Form Modal -->
                @if($showForm)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium mb-4">
                                    {{ $editingConversion ? 'Edit Conversion' : 'Add New Conversion' }}
                                </h3>
                                
                                <form wire:submit.prevent="saveConversion">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Lead Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Lead</label>
                                            <select wire:model="lead_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Select a Lead</option>
                                                @foreach($leads as $lead)
                                                    <option value="{{ $lead->id }}">
                                                        {{ $lead->name }} - {{ $lead->company }} ({{ $lead->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('lead_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- AI Lead Score -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">AI Lead Score (Optional)</label>
                                            <select wire:model="ai_lead_score_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Select AI Score</option>
                                                @foreach($leads as $lead)
                                                    @if($lead->aiScore)
                                                        <option value="{{ $lead->aiScore->id }}">
                                                            {{ $lead->name }} - Score: {{ $lead->aiScore->score }}/100
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('ai_lead_score_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Conversion Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Conversion Type</label>
                                            <select wire:model="conversion_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Select Type</option>
                                                @foreach($conversionTypes as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('conversion_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Status -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select wire:model="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @foreach($statusOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Revenue -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Revenue ($)</label>
                                            <input type="number" wire:model="revenue" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error('revenue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Deal Size -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Deal Size ($)</label>
                                            <input type="number" wire:model="deal_size" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error('deal_size') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Conversion Date -->
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Conversion Date</label>
                                            <input type="date" wire:model="conversion_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error('conversion_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Notes -->
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                            <textarea wire:model="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Additional notes about this conversion..."></textarea>
                                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-3 mt-6">
                                        <button type="button" wire:click="cancel" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            {{ $editingConversion ? 'Update' : 'Save' }} Conversion
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Conversions Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($conversions as $conversion)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $conversion->lead->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $conversion->lead->company }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $conversionTypes[$conversion->conversion_type] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'lost' => 'bg-red-100 text-red-800',
                                                'delayed' => 'bg-gray-100 text-gray-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$conversion->status] }}">
                                            {{ $statusOptions[$conversion->status] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($conversion->revenue, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $conversion->conversion_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button wire:click="editConversion({{ $conversion->id }})" class="text-blue-600 hover:text-blue-900">
                                                Edit
                                            </button>
                                            <button wire:click="deleteConversion({{ $conversion->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No conversions found. <button wire:click="createConversion" class="text-blue-600 hover:text-blue-800">Add your first conversion</button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $conversions->links() }}
                </div>
            </div>
        </div>
    </div>
</div> 