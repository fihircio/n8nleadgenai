<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">AI Lead Scoring</h2>
                    
                    @if($selectedLead)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2">Selected Lead</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p><strong>Name:</strong> {{ $selectedLead->name }}</p>
                                        <p><strong>Company:</strong> {{ $selectedLead->company }}</p>
                                        <p><strong>Title:</strong> {{ $selectedLead->title }}</p>
                                        <p><strong>Email:</strong> {{ $selectedLead->email }}</p>
                                        <p><strong>Phone:</strong> {{ $selectedLead->phone }}</p>
                                        <p><strong>Source:</strong> {{ $selectedLead->source }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($selectedLead->status) }}</p>
                                    </div>
                                    
                                    @if($selectedLead->aiScore)
                                        <div>
                                            <h4 class="font-medium mb-2">AI Score Details</h4>
                                            <div class="mb-2">
                                                <span class="text-lg font-bold 
                                                    @if($selectedLead->aiScore->isHot()) text-red-600
                                                    @elseif($selectedLead->aiScore->isWarm()) text-yellow-600
                                                    @else text-blue-600
                                                    @endif">
                                                    {{ $selectedLead->aiScore->score }}/100
                                                </span>
                                                <span class="ml-2 px-2 py-1 rounded text-xs font-semibold
                                                    @if($selectedLead->aiScore->isHot()) bg-red-100 text-red-800
                                                    @elseif($selectedLead->aiScore->isWarm()) bg-yellow-100 text-yellow-800
                                                    @else bg-blue-100 text-blue-800
                                                    @endif">
                                                    {{ $selectedLead->aiScore->isHot() ? 'Hot' : ($selectedLead->aiScore->isWarm() ? 'Warm' : 'Cold') }}
                                                </span>
                                            </div>
                                            
                                            @if($selectedLead->aiScore->scoring_factors)
                                                <div class="mb-3">
                                                    <h5 class="font-medium text-sm mb-1">Scoring Factors:</h5>
                                                    <div class="text-sm space-y-1">
                                                        @foreach($selectedLead->aiScore->scoring_factors as $factor => $value)
                                                            <div class="flex justify-between">
                                                                <span class="capitalize">{{ str_replace('_', ' ', $factor) }}:</span>
                                                                <span class="font-medium">{{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($selectedLead->aiScore->enrichment_data)
                                                <div class="mb-3">
                                                    <h5 class="font-medium text-sm mb-1">Enrichment Data:</h5>
                                                    <div class="text-sm space-y-1">
                                                        @foreach($selectedLead->aiScore->enrichment_data as $key => $value)
                                                            <div class="flex justify-between">
                                                                <span class="capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                                                <span class="font-medium">{{ $value }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <p class="text-xs text-gray-500">Last Updated: {{ $selectedLead->aiScore->updated_at->diffForHumans() }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4 flex gap-2">
                                    <button wire:click="scoreLead" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
                                        @if($isScoring) disabled @endif>
                                        @if($isScoring) Scoring... @else Score Lead @endif
                                    </button>
                                    <button wire:click="rescoreLead" 
                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:opacity-50"
                                        @if($isScoring) disabled @endif>
                                        @if($isScoring) Rescoring... @else Rescore Lead @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-2">Recent Leads</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($leads as $lead)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $lead->company }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $lead->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $lead->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($lead->aiScore)
                                                    <span class="inline-flex items-center">
                                                        <span class="text-sm font-medium 
                                                            @if($lead->aiScore->isHot()) text-red-600
                                                            @elseif($lead->aiScore->isWarm()) text-yellow-600
                                                            @else text-blue-600
                                                            @endif">
                                                            {{ $lead->aiScore->score }}
                                                        </span>
                                                        <span class="ml-1 px-2 py-1 rounded text-xs font-semibold
                                                            @if($lead->aiScore->isHot()) bg-red-100 text-red-800
                                                            @elseif($lead->aiScore->isWarm()) bg-yellow-100 text-yellow-800
                                                            @else bg-blue-100 text-blue-800
                                                            @endif">
                                                            {{ $lead->aiScore->isHot() ? 'Hot' : ($lead->aiScore->isWarm() ? 'Warm' : 'Cold') }}
                                                        </span>
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Not scored</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button wire:click="selectLead({{ $lead->id }})" class="text-blue-600 hover:text-blue-900">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $leads->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 