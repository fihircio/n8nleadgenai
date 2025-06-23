<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getUserAnalytics(Request $request): JsonResponse
    {
        $period = $request->get('period', 30);
        $user = Auth::user();
        
        $analytics = $this->analyticsService->getUserAnalytics($user, $period);
        
        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    public function getGlobalAnalytics(Request $request): JsonResponse
    {
        $period = $request->get('period', 30);
        
        $analytics = $this->analyticsService->getGlobalAnalytics($period);
        
        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }
} 