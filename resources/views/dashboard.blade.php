@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
        <div class="mb-6 p-4 bg-blue-100 rounded">
            <span class="font-semibold">Your Coin Balance:</span>
            <span class="text-2xl font-mono" id="coin-balance-dashboard">{{ auth()->user()->getCoinBalance() }}</span>
            <button id="refresh-coin-balance-dashboard" class="ml-2 px-2 py-1 bg-blue-500 text-white rounded">Refresh</button>
        </div>
        <div class="mb-6 p-4 bg-green-100 rounded">
            <span class="font-semibold">Your Referral Code:</span>
            <span class="text-lg font-mono">{{ auth()->user()->referral_code }}</span>
            <span class="ml-2 text-gray-500">(Share this code to earn coins!)</span>
        </div>
        <div class="mb-6 p-4 bg-yellow-100 rounded">
            <span class="font-semibold">Referrals:</span>
            <ul class="list-disc ml-6">
                @forelse(auth()->user()->referrals as $ref)
                    <li>{{ $ref->email }} (joined: {{ $ref->created_at->format('Y-m-d') }})</li>
                @empty
                    <li>No referrals yet.</li>
                @endforelse
            </ul>
        </div>
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Coin Transaction History</h2>
            <a href="/coins/history" class="text-blue-600 underline">View All Transactions</a>
        </div>
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Premium Features</h2>
            <ul class="list-disc ml-6">
                <li>Silver Feature (10 coins): <a href="/premium/silver" class="text-blue-600 underline">Access</a></li>
                <li>Gold Feature (50 coins): <a href="/premium/gold" class="text-blue-600 underline">Access</a></li>
                <li>Platinum Feature (100 coins): <a href="/premium/platinum" class="text-blue-600 underline">Access</a></li>
            </ul>
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
