<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header with Controls -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Advanced Analytics</h1>
                    <p class="mt-2 text-gray-600">Custom date ranges, charts, and export functionality</p>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                    <!-- Date Range Picker -->
                    <div class="flex space-x-2">
                        <input type="date" wire:model.live="startDate" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="self-center text-gray-500">to</span>
                        <input type="date" wire:model.live="endDate" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <!-- Export Buttons -->
                    <div class="flex space-x-2">
                        <button wire:click="exportData('csv')" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                            Export CSV
                        </button>
                        <button wire:click="exportData('json')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                            Export JSON
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Lead Scoring Trend Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Scoring Trend</h3>
                @if(empty($chartData['lead_scoring']['labels']))
                    <div class="h-64 flex items-center justify-center text-gray-500">
                        <p>No lead scoring data available for this period</p>
                    </div>
                @else
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="leadScoringChart" width="400" height="200"></canvas>
                    </div>
                @endif
            </div>

            <!-- Revenue Trend Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Trend</h3>
                @if(empty($chartData['revenue']['labels']))
                    <div class="h-64 flex items-center justify-center text-gray-500">
                        <p>No revenue data available for this period</p>
                    </div>
                @else
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                @endif
            </div>
        </div>

        <!-- Debug Information (remove in production) -->
        @if(config('app.debug'))
        <div class="bg-gray-100 p-4 rounded-lg mb-8">
            <h4 class="font-medium text-gray-900 mb-2">Debug Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <strong>Lead Scoring Chart Data:</strong>
                    <pre class="text-xs mt-1">{{ json_encode($chartData['lead_scoring'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                </div>
                <div>
                    <strong>Revenue Chart Data:</strong>
                    <pre class="text-xs mt-1">{{ json_encode($chartData['revenue'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        </div>
        @endif

        <!-- Conversion Analytics -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Conversion Analytics by Type</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($conversionData as $type => $data)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $type) }}</h4>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-600">Total: {{ $data['count'] }}</p>
                            <p class="text-sm text-gray-600">Completed: {{ $data['completed'] }}</p>
                            <p class="text-sm font-medium text-green-600">Revenue: ${{ number_format($data['revenue'], 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detailed Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Lead Scoring Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Scoring Details</h3>
                @if(isset($analytics['lead_scoring']['trend']) && count($analytics['lead_scoring']['trend']) > 0)
                    <div class="space-y-3">
                        @foreach($analytics['lead_scoring']['trend'] as $date => $data)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span class="text-sm font-medium">{{ \Carbon\Carbon::parse($date)->format('M d') }}</span>
                                <div class="text-sm text-gray-600">
                                    <span class="mr-4">{{ $data['count'] }} leads</span>
                                    <span>Avg: {{ number_format($data['average'], 1) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No lead scoring data available for this period</p>
                @endif
            </div>

            <!-- Template Usage Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Template Usage Details</h3>
                @if(isset($analytics['template_usage']['popular_templates']) && count($analytics['template_usage']['popular_templates']) > 0)
                    <div class="space-y-3">
                        @foreach($analytics['template_usage']['popular_templates'] as $template)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $template['template_name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $template['usage_count'] }} uses</p>
                                </div>
                                <span class="text-sm font-medium text-blue-600">{{ $template['total_cost'] }} coins</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No template usage data available for this period</p>
                @endif
            </div>
        </div>

        <!-- ROI Analysis -->
        <div class="bg-white shadow rounded-lg p-6 mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">ROI Analysis</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">${{ number_format($analytics['revenue']['total_revenue'] ?? 0, 2) }}</p>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-600">{{ number_format($analytics['coin_usage']['total_spent'] ?? 0) }}</p>
                    <p class="text-sm text-gray-600">Coins Spent</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold {{ ($analytics['revenue']['roi'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($analytics['revenue']['roi'] ?? 0, 1) }}%
                    </p>
                    <p class="text-sm text-gray-600">ROI</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:init', () => {
    let leadScoringChart = null;
    let revenueChart = null;

    // Initialize charts only if data is available
    @if(!empty($chartData['lead_scoring']['labels']))
    const leadScoringCtx = document.getElementById('leadScoringChart');
    if (leadScoringCtx) {
        leadScoringChart = new Chart(leadScoringCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartData['lead_scoring']['labels'] ?? []),
                datasets: [{
                    label: 'Average Score',
                    data: @json($chartData['lead_scoring']['data'] ?? []),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
    @endif

    @if(!empty($chartData['revenue']['labels']))
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        revenueChart = new Chart(revenueCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartData['revenue']['labels'] ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartData['revenue']['data'] ?? []),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    @endif

    // Update charts when data changes
    Livewire.on('analytics-updated', (data) => {
        if (leadScoringChart && data.lead_scoring) {
            leadScoringChart.data.labels = data.lead_scoring.labels;
            leadScoringChart.data.datasets[0].data = data.lead_scoring.data;
            leadScoringChart.update();
        }

        if (revenueChart && data.revenue) {
            revenueChart.data.labels = data.revenue.labels;
            revenueChart.data.datasets[0].data = data.revenue.data;
            revenueChart.update();
        }
    });
});
</script>
@endpush 