<?php

namespace App\Models;

use App\Services\AiService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'prompt',
        'provider',
        'is_active',
        'cost_in_coins',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cost_in_coins' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generateContent(array $data): string
    {
        $aiService = app(AiService::class);

        return match($this->provider) {
            'openai' => $this->generateOpenAiContent($aiService, $data),
            'elevenlabs' => $this->generateElevenLabsContent($aiService, $data),
            default => throw new \Exception('Unsupported AI provider')
        };
    }

    protected function generateOpenAiContent(AiService $aiService, array $data): string
    {
        $prompt = $this->prompt;
        foreach ($data as $key => $value) {
            $prompt = str_replace("{{$key}}", $value, $prompt);
        }

        return $aiService->generateOpenAiContent($prompt, [
            'model' => 'gpt-4',
            'temperature' => 0.7,
        ]);
    }

    protected function generateElevenLabsContent(AiService $aiService, array $data): string
    {
        $text = $this->prompt;
        foreach ($data as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }

        return $aiService->generateElevenLabsVoice($text, $data['voice_id'] ?? 'default', [
            'model_id' => 'eleven_monolingual_v1',
            'stability' => 0.5,
            'similarity_boost' => 0.75,
        ]);
    }
} 