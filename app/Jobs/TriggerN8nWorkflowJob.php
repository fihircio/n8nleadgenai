<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TriggerN8nWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $webhookUrl = config('services.n8n.webhook_url', 'http://n8n:5678/webhook/coin-gated');
        $secret = config('services.n8n.secret', 'changeme');
        $callbackUrl = config('app.url') . '/api/automation/result';
        $payload = (array) $this->payload;
        $payload['callback_url'] = $callbackUrl;
        $signature = hash_hmac('sha256', json_encode($payload), $secret);
        $response = \Http::withHeaders([
            'X-N8N-Signature' => $signature,
        ])->post($webhookUrl, $payload);
        \Log::info('n8n webhook sent', [
            'url' => $webhookUrl,
            'payload' => $payload,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }
}
