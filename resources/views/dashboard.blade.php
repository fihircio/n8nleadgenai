@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Coin Balance -->
        <div class="p-6 bg-blue-100 rounded shadow flex items-center">
            <div class="text-blue-500 text-3xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
            <div>
                <div class="font-semibold">Your Coin Balance</div>
                <div class="text-2xl font-mono" id="coin-balance-dashboard">{{ auth()->user()->getCoinBalance() }}</div>
                <button id="refresh-coin-balance-dashboard" class="mt-2 px-2 py-1 bg-blue-500 text-white rounded text-xs">Refresh</button>
            </div>
        </div>
        <!-- Referral Code -->
        <div class="p-6 bg-green-100 rounded shadow flex items-center">
            <div class="text-green-500 text-3xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div>
                <div class="font-semibold">Your Referral Code</div>
                <div class="text-lg font-mono">{{ auth()->user()->referral_code }}</div>
                <div class="text-gray-500 text-xs">(Share to earn coins!)</div>
            </div>
        </div>
        <!-- Referrals -->
        <div class="p-6 bg-yellow-100 rounded shadow flex items-center">
            <div class="text-yellow-500 text-3xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/></svg>
            </div>
            <div>
                <div class="font-semibold">Referrals</div>
                <ul class="list-disc ml-4 text-sm">
                    @forelse(auth()->user()->referrals as $ref)
                        <li>{{ $ref->email }} <span class="text-gray-500">(joined: {{ $ref->created_at->format('Y-m-d') }})</span></li>
                    @empty
                        <li>No referrals yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- Coin Transaction History -->
        <div class="p-6 bg-indigo-100 rounded shadow flex items-center col-span-1 md:col-span-2 lg:col-span-1">
            <div class="text-indigo-500 text-3xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h2l1 2h13l1-2h2M5 6h14l1 2H4l1-2zm2 14h10a2 2 0 002-2v-5H5v5a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="font-semibold">Coin Transaction History</div>
                <a href="/coins/history" class="text-blue-600 underline text-sm">View All Transactions</a>
            </div>
        </div>
        <!-- Premium Features -->
        <div class="p-6 bg-pink-100 rounded shadow flex items-center col-span-1 md:col-span-2 lg:col-span-2">
            <div class="text-pink-500 text-3xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
            <div>
                <div class="font-semibold">Premium Features</div>
                <ul class="list-disc ml-4 text-sm">
                    <li>Silver Feature (10 coins): <a href="/premium/silver" class="text-blue-600 underline">Access</a></li>
                    <li>Gold Feature (50 coins): <a href="/premium/gold" class="text-blue-600 underline">Access</a></li>
                    <li>Platinum Feature (100 coins): <a href="/premium/platinum" class="text-blue-600 underline">Access</a></li>
                </ul>
            </div>
        </div>
        <!-- Analytics Dashboard -->
        <div class="p-6 bg-purple-100 rounded shadow flex items-center col-span-1 md:col-span-2 lg:col-span-3">
            <div class="text-purple-500 text-3xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <div class="font-semibold">Analytics & Reporting</div>
                <p class="text-sm text-gray-600 mb-2">Track your lead generation performance, conversion rates, and ROI</p>
                <div class="flex space-x-2">
                    <a href="{{ route('analytics') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                        Basic Analytics
                    </a>
                    <a href="{{ route('advanced-analytics') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                        Advanced Analytics
                    </a>
                    <a href="{{ route('conversions') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                        Conversion Tracking
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Workflow Marketplace Section -->
    <div class="bg-white rounded shadow p-8">
        <h2 class="text-2xl font-semibold mb-4">Workflow Marketplace</h2>
        @livewire('page.marketplace.workflow-marketplace')
    </div>
    <div class="bg-white rounded shadow p-8">
        <h2 class="text-2xl font-semibold mb-4">AI Lead Scoring</h2>
        @livewire('page.leads.ai-lead-scoring')
    </div>
    <!-- AI Templates Section -->
    <div class="bg-white rounded shadow p-8">
        <h2 class="text-2xl font-semibold mb-4">AI Templates</h2>
        @livewire('page.ai-templates.ai-template-manager')
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const refreshBtn = document.getElementById('refresh-coin-balance-dashboard');
    const balanceSpan = document.getElementById('coin-balance-dashboard');
    if (refreshBtn && balanceSpan) {
        refreshBtn.addEventListener('click', async function () {
            refreshBtn.disabled = true;
            refreshBtn.textContent = '...';
            try {
                const response = await fetch('/api/coins/balance', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    credentials: 'same-origin',
                });
                if (response.ok) {
                    const data = await response.json();
                    balanceSpan.textContent = data.balance;
                } else {
                    alert('Failed to fetch coin balance.');
                }
            } catch (e) {
                alert('Error fetching coin balance.');
            }
            refreshBtn.disabled = false;
            refreshBtn.textContent = 'Refresh';
        });
    }
});
</script>
@endpush