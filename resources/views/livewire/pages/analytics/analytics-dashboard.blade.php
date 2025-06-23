<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <p class="mt-2 text-gray-600">Track your lead generation performance and ROI</p>
                </div>
                <div class="flex items-center space-x-4">
                    <select wire:model.live="selectedPeriod" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="7">Last 7 days</option>
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Leads Scored -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Leads Scored</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($analytics['lead_scoring']['total_leads'] ?? 0) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Average Score</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $analytics['lead_scoring']['average_score'] ?? 0 }}/100</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Rate -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $analytics['conversions']['conversion_rate'] ?? 0 }}%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($analytics['revenue']['total_revenue'] ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Detailed Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Lead Score Distribution -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Score Distribution</h3>
                @if(isset($analytics['lead_scoring']['score_distribution']) && array_sum($analytics['lead_scoring']['score_distribution']) > 0)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Hot Leads (80-100)</span>
                            <span class="text-sm text-gray-500">{{ $analytics['lead_scoring']['score_distribution']['hot'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $total = array_sum($analytics['lead_scoring']['score_distribution']);
                                $hotPercentage = $total > 0 ? ($analytics['lead_scoring']['score_distribution']['hot'] / $total) * 100 : 0;
                            @endphp
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $hotPercentage }}%"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Warm Leads (50-79)</span>
                            <span class="text-sm text-gray-500">{{ $analytics['lead_scoring']['score_distribution']['warm'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $warmPercentage = $total > 0 ? ($analytics['lead_scoring']['score_distribution']['warm'] / $total) * 100 : 0;
                            @endphp
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $warmPercentage }}%"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Cold Leads (0-49)</span>
                            <span class="text-sm text-gray-500">{{ $analytics['lead_scoring']['score_distribution']['cold'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $coldPercentage = $total > 0 ? ($analytics['lead_scoring']['score_distribution']['cold'] / $total) * 100 : 0;
                            @endphp
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $coldPercentage }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No lead scoring data available</p>
                    </div>
                @endif
            </div>

            <!-- Template Usage -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Templates</h3>
                @if(isset($analytics['template_usage']['popular_templates']) && count($analytics['template_usage']['popular_templates']) > 0)
                    <div class="space-y-3">
                        @foreach($analytics['template_usage']['popular_templates'] as $template)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $template['template_name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $template['usage_count'] }} uses</p>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $template['total_cost'] }} coins</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No template usage data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Conversion Analytics -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Conversion Rate by Score Range</h3>
            @if(isset($analytics['conversions']['conversion_by_score']) && count($analytics['conversions']['conversion_by_score']) > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($analytics['conversions']['conversion_by_score'] as $range => $data)
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-900">{{ $range }}</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ number_format($data['rate'], 1) }}%</p>
                            <p class="text-xs text-gray-500">{{ $data['conversions'] }}/{{ $data['leads'] }} leads</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No conversion data available</p>
                </div>
            @endif
        </div>

        <!-- ROI and Performance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- ROI Analysis -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">ROI Analysis</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Revenue</span>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($analytics['revenue']['total_revenue'] ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Coin Costs</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($analytics['coin_usage']['total_spent'] ?? 0) }} coins</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Average Deal Size</span>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($analytics['revenue']['average_deal_size'] ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-sm font-medium text-gray-900">ROI</span>
                        <span class="text-sm font-medium {{ ($analytics['revenue']['roi'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($analytics['revenue']['roi'] ?? 0, 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Coin Usage Breakdown -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Coin Usage by Category</h3>
                @if(isset($analytics['coin_usage']['category_breakdown']) && count($analytics['coin_usage']['category_breakdown']) > 0)
                    <div class="space-y-3">
                        @foreach($analytics['coin_usage']['category_breakdown'] as $category => $amount)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $category }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $amount }} coins</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No coin usage data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 