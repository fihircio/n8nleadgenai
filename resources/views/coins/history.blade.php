@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-4">Coin Transaction History</h1>
        <div id="coin-history-list" class="bg-white rounded shadow p-4">
            <div class="text-gray-500">Loading...</div>
        </div>
        <div class="mt-6">
            <a href="/dashboard" class="text-blue-600 underline">&larr; Back to Dashboard</a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/coins/history', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        credentials: 'same-origin',
    })
    .then(response => response.json())
    .then(data => {
        const list = document.getElementById('coin-history-list');
        if (!data.transactions.length) {
            list.innerHTML = '<div class="text-gray-500">No transactions found.</div>';
            return;
        }
        list.innerHTML = '<ul class="divide-y">' + data.transactions.map(tx => `
            <li class="py-2 flex justify-between items-center">
                <span>
                    <span class="font-mono">${tx.amount > 0 ? '+' : ''}${tx.amount}</span>
                    <span class="ml-2 text-xs text-gray-500">${tx.type}</span>
                    <span class="ml-2 text-xs text-gray-400">${tx.meta && tx.meta.reason ? tx.meta.reason : ''}</span>
                </span>
                <span class="text-xs text-gray-400">${new Date(tx.created_at).toLocaleString()}</span>
            </li>
        `).join('') + '</ul>';
    })
    .catch(() => {
        document.getElementById('coin-history-list').innerHTML = '<div class="text-red-500">Failed to load transactions.</div>';
    });
});
</script>
@endpush
