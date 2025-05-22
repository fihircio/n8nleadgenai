<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Jobs\TriggerN8nWorkflowJob;

class CoinAutomationTrigger extends Component
{
    public $data = [];
    public $status = null;
    public $error = null;

    public function trigger()
    {
        $this->reset(['status', 'error']);
        $user = Auth::user();
        $cost = 10; // Example: cost per automation
        if ($user->getCoinBalance() < $cost) {
            $this->error = 'Not enough coins';
            return;
        }
        $user->subtractCoins($cost, ['reason' => 'n8n automation trigger']);
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'data' => $this->data,
        ];
        TriggerN8nWorkflowJob::dispatch($payload);
        $this->status = 'Workflow triggered!';
        $this->dispatch('refreshCoinBalance');
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
