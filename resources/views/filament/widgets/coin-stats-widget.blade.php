<x-filament::widget>
    <div class="space-y-2">
        <h3 class="text-lg font-bold">Coin System Stats</h3>
        <div>Total Coins in System: <span class="font-mono">{{ $total_coins }}</span></div>
        <div>Total Coin Transactions: <span class="font-mono">{{ $total_transactions }}</span></div>
        <div>Last Transaction: 
            @if($last_transaction)
                <span class="font-mono">{{ $last_transaction->amount }} ({{ $last_transaction->created_at }})</span>
            @else
                <span class="text-gray-500">None</span>
            @endif
        </div>
    </div>
</x-filament::widget>
