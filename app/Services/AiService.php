<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $openaiApiKey;
    protected string $elevenlabsApiKey;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
        $this->elevenlabsApiKey = config('services.elevenlabs.api_key');
    }

    public function generateOpenAiContent(string $prompt, array $options = []): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $options['model'] ?? 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $options['system_prompt'] ?? 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $options['temperature'] ?? 0.7,
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            Log::error('OpenAI API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to generate content with OpenAI');
        } catch (\Exception $e) {
            Log::error('OpenAI service error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function generateElevenLabsVoice(string $text, string $voiceId, array $options = []): string
    {
        try {
            $response = Http::withHeaders([
                'xi-api-key' => $this->elevenlabsApiKey,
                'Content-Type' => 'application/json',
            ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
                'text' => $text,
                'model_id' => $options['model_id'] ?? 'eleven_monolingual_v1',
                'voice_settings' => [
                    'stability' => $options['stability'] ?? 0.5,
                    'similarity_boost' => $options['similarity_boost'] ?? 0.75,
                ]
            ]);

            if ($response->successful()) {
                // Store the audio file and return the URL
                $audioPath = storage_path('app/public/voice-messages/' . uniqid() . '.mp3');
                file_put_contents($audioPath, $response->body());
                return asset('storage/voice-messages/' . basename($audioPath));
            }

            Log::error('ElevenLabs API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to generate voice with ElevenLabs');
        } catch (\Exception $e) {
            Log::error('ElevenLabs service error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
} 