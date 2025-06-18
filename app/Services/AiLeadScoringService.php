<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Models\AiLeadScore;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class AiLeadScoringService
{
    use HasRoles;

    protected $apiKey;
    protected $apiEndpoint = 'https://api.openai.com/v1/chat/completions';
    protected $requiredCoins = 20; // Cost per scoring operation

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    public function scoreLead(Lead $lead): AiLeadScore
    {
        // Check if user has enough coins
        $user = User::find(Auth::id());
        if (!$user->hasRole('admin') && $user->coins < $this->requiredCoins) {
            throw new \Exception('Insufficient coins. You need ' . $this->requiredCoins . ' coins to score a lead.');
        }

        try {
            // Start with a base score
            $score = 0;
            $scoringFactors = [];
            $enrichmentData = [];

            // 1. Company Information Score
            if ($lead->company) {
                $companyScore = $this->analyzeCompany($lead->company);
                $score += $companyScore['score'];
                $scoringFactors['company'] = $companyScore['factors'];
                $enrichmentData['company'] = $companyScore['enrichment'];
            }

            // 2. Title/Role Score
            if ($lead->title) {
                $titleScore = $this->analyzeTitle($lead->title);
                $score += $titleScore['score'];
                $scoringFactors['title'] = $titleScore['factors'];
                $enrichmentData['title'] = $titleScore['enrichment'];
            }

            // 3. Email Domain Score
            $emailScore = $this->analyzeEmail($lead->email);
            $score += $emailScore['score'];
            $scoringFactors['email'] = $emailScore['factors'];
            $enrichmentData['email'] = $emailScore['enrichment'];

            // Deduct coins if not admin
            if (!$user->hasRole('admin')) {
                $user->coins -= $this->requiredCoins;
                $user->save();
            }

            // Create or update the AI score
            return AiLeadScore::updateOrCreate(
                ['lead_id' => $lead->id],
                [
                    'score' => min(100, $score), // Cap at 100
                    'scoring_factors' => $scoringFactors,
                    'enrichment_data' => $enrichmentData,
                    'status' => 'completed'
                ]
            );
        } catch (\Exception $e) {
            Log::error('AI Lead Scoring failed', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage()
            ]);

            return AiLeadScore::updateOrCreate(
                ['lead_id' => $lead->id],
                [
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]
            );
        }
    }

    protected function analyzeCompany(string $company): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiEndpoint, [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Analyze the company name and provide a score (0-30) and factors that influenced the score. Also provide enrichment data about the company. Return the response in JSON format with keys: score, factors, enrichment.'
                ],
                [
                    'role' => 'user',
                    'content' => "Company: {$company}"
                ]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }

        $analysis = json_decode($response->json()['choices'][0]['message']['content'], true);
        
        return [
            'score' => $analysis['score'] ?? 0,
            'factors' => $analysis['factors'] ?? [],
            'enrichment' => $analysis['enrichment'] ?? []
        ];
    }

    protected function analyzeTitle(string $title): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiEndpoint, [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Analyze the job title and provide a score (0-30) and factors that influenced the score. Also provide enrichment data about the role. Return the response in JSON format with keys: score, factors, enrichment.'
                ],
                [
                    'role' => 'user',
                    'content' => "Title: {$title}"
                ]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }

        $analysis = json_decode($response->json()['choices'][0]['message']['content'], true);
        
        return [
            'score' => $analysis['score'] ?? 0,
            'factors' => $analysis['factors'] ?? [],
            'enrichment' => $analysis['enrichment'] ?? []
        ];
    }

    protected function analyzeEmail(string $email): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiEndpoint, [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Analyze the email address and provide a score (0-40) and factors that influenced the score. Also provide enrichment data about the email domain. Return the response in JSON format with keys: score, factors, enrichment.'
                ],
                [
                    'role' => 'user',
                    'content' => "Email: {$email}"
                ]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }

        $analysis = json_decode($response->json()['choices'][0]['message']['content'], true);
        
        return [
            'score' => $analysis['score'] ?? 0,
            'factors' => $analysis['factors'] ?? [],
            'enrichment' => $analysis['enrichment'] ?? []
        ];
    }
} 