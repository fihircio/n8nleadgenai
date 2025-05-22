<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\TriggerN8nWorkflowJob;

class AutomationController extends Controller
{
    /**
     * Trigger a coin-gated n8n workflow for the authenticated user.
     */
    public function trigger(Request $request)
    {
        $user = $request->user();
        $cost = 10; // Example: cost per automation
        if ($user->getCoinBalance() < $cost) {
            Log::warning('Automation trigger: insufficient coins', [
                'user_id' => $user->id,
                'action' => 'automation_trigger',
            ]);
            return response()->json(['error' => 'Not enough coins'], 402);
        }
        $user->subtractCoins($cost, ['reason' => 'n8n automation trigger']);
        Log::info('Automation trigger: coins deducted', [
            'user_id' => $user->id,
            'amount' => $cost,
            'action' => 'automation_trigger',
        ]);
        // Prepare payload for n8n
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'data' => $request->input('data', []),
        ];
        // Dispatch async job
        TriggerN8nWorkflowJob::dispatch($payload);
        return response()->json(['success' => true, 'message' => 'Workflow triggered.']);
    }
}
