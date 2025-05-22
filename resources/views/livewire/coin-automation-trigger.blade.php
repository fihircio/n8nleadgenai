<div>
    <form wire:submit.prevent="trigger" class="space-y-4">
        <div>
            <label for="data" class="block font-semibold">Workflow Data (JSON)</label>
            <textarea id="data" wire:model.defer="data" class="w-full border rounded p-2" rows="3"></textarea>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Trigger Automation (10 coins)</button>
    </form>
    @if ($status)
        <div class="mt-2 text-green-600">{{ $status }}</div>
    @endif
    @if ($error)
        <div class="mt-2 text-red-600">{{ $error }}</div>
    @endif
    <div class="mt-6">
        <h3 class="font-semibold mb-2">Recent Automation Results</h3>
        <ul>
            @forelse ($this->automationResults as $result)
                <li class="mb-2 p-2 bg-gray-100 rounded">
                    <div>Status: <span class="font-mono">{{ $result->status }}</span></div>
                    <div>Result: <span class="font-mono text-xs">{{ json_encode($result->result) }}</span></div>
                    <div class="text-xs text-gray-500">{{ $result->created_at->diffForHumans() }}</div>
                </li>
            @empty
                <li>No results yet.</li>
            @endforelse
        </ul>
    </div>
</div>
