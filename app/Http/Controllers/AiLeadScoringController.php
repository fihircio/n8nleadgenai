<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\AiLeadScoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiLeadScoringController extends Controller
{
    protected $scoringService;

    public function __construct(AiLeadScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    public function scoreLead(Lead $lead): JsonResponse
    {
        try {
            $score = $this->scoringService->scoreLead($lead);
            return response()->json([
                'success' => true,
                'data' => $score
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getScore(Lead $lead): JsonResponse
    {
        $score = $lead->aiScore;
        
        if (!$score) {
            return response()->json([
                'success' => false,
                'message' => 'No score found for this lead'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $score
        ]);
    }

    public function rescoreLead(Lead $lead): JsonResponse
    {
        try {
            $score = $this->scoringService->scoreLead($lead);
            return response()->json([
                'success' => true,
                'data' => $score
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 