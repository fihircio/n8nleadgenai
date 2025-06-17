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
        'user_id',
        'name',
        'description',
        'prompt',
        'provider',
        'template_data',
        'variables',
        'is_active',
        'cost_in_coins',
    ];

    protected $casts = [
        'template_data' => 'array',
        'variables' => 'array',
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
        $prompt = $this->template_data['prompt_template'];
        foreach ($data as $key => $value) {
            $prompt = str_replace("{{$key}}", $value, $prompt);
        }

        return $aiService->generateOpenAiContent($prompt, [
            'system_prompt' => $this->template_data['system_prompt'] ?? null,
            'model' => $this->template_data['model'] ?? null,
            'temperature' => $this->template_data['temperature'] ?? null,
        ]);
    }

    protected function generateElevenLabsContent(AiService $aiService, array $data): string
    {
        $text = $this->template_data['text_template'];
        foreach ($data as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }

        return $aiService->generateElevenLabsVoice($text, $data['voice_id'], [
            'model_id' => $this->template_data['model_id'] ?? null,
            'stability' => $this->template_data['stability'] ?? null,
            'similarity_boost' => $this->template_data['similarity_boost'] ?? null,
        ]);
    }
} 