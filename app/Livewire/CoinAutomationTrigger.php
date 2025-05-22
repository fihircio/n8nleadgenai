<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CoinAutomationTrigger extends Component
{
    public $data = [];
    public $status = null;
    public $error = null;

    public function trigger()
    {
        $this->reset(['status', 'error']);
        $response = Http::post('/api/automation/trigger', [
            'data' => $this->data,
        ]);
        if ($response->successful()) {
            $this->status = 'Workflow triggered!';
        } else {
            $this->error = $response->json('error') ?? 'Failed to trigger workflow.';
        }
        $this->emit('refreshCoinBalance');
    }

    public function getAutomationResultsProperty()
    {
        $user = Auth::user();
        return $user && method_exists($user, 'automationResults') ? $user->automationResults()->latest()->take(5)->get() : collect();
    }

    public function render()
    {
        return view('livewire.coin-automation-trigger');
    }
}
