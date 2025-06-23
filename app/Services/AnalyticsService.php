<?php

namespace App\Services;

use App\Models\Analytics;
use App\Models\AiTemplate;
use App\Models\AiLeadScore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getUserAnalytics(User $user, $period = 30)
    {
        $startDate = Carbon::now()->subDays($period);
        
        return [
            'lead_scoring' => $this->getLeadScoringAnalytics($user, $startDate),
            'template_usage' => $this->getTemplateUsageAnalytics($user, $startDate),
            'conversions' => $this->getConversionAnalytics($user, $startDate),
            'revenue' => $this->getRevenueAnalytics($user, $startDate),
            'coin_usage' => $this->getCoinUsageAnalytics($user, $startDate),
        ];
    }

    public function getLeadScoringAnalytics(User $user, $startDate)
    {
        $scores = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'lead_scoring')
            ->where('date', '>=', $startDate)
            ->get();

        if ($scores->isEmpty()) {
            return [
                'total_leads' => 0,
                'average_score' => 0,
                'score_distribution' => [],
                'hot_leads' => 0,
                'warm_leads' => 0,
                'cold_leads' => 0,
                'trend' => [],
            ];
        }

        $totalLeads = $scores->count();
        $averageScore = $scores->avg('metric_value');
        
        // Score distribution
        $hotLeads = $scores->where('metric_value', '>=', 80)->count();
        $warmLeads = $scores->whereBetween('metric_value', [50, 79])->count();
        $coldLeads = $scores->where('metric_value', '<', 50)->count();

        // Daily trend
        $trend = $scores->groupBy('date')
            ->map(function ($dayScores) {
                return [
                    'count' => $dayScores->count(),
                    'average' => $dayScores->avg('metric_value'),
                ];
            })
            ->mapWithKeys(function ($data, $date) {
                // Convert the date key to a proper format
                return [Carbon::parse($date)->format('Y-m-d') => $data];
            })
            ->toArray(); // Convert Collection to array

        return [
            'total_leads' => $totalLeads,
            'average_score' => round($averageScore, 2),
            'score_distribution' => [
                'hot' => $hotLeads,
                'warm' => $warmLeads,
                'cold' => $coldLeads,
            ],
            'hot_leads' => $hotLeads,
            'warm_leads' => $warmLeads,
            'cold_leads' => $coldLeads,
            'trend' => $trend,
        ];
    }

    public function getTemplateUsageAnalytics(User $user, $startDate)
    {
        $usage = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'template_usage')
            ->where('date', '>=', $startDate)
            ->get();

        if ($usage->isEmpty()) {
            return [
                'total_usage' => 0,
                'total_cost' => 0,
                'popular_templates' => [],
                'usage_trend' => [],
            ];
        }

        $totalUsage = $usage->count();
        $totalCost = $usage->sum('metric_value');

        // Popular templates
        $templateUsage = $usage->groupBy('metadata.template_id')
            ->map(function ($templateScores, $templateId) {
                $template = AiTemplate::find($templateId);
                return [
                    'template_name' => $template ? $template->name : 'Unknown',
                    'usage_count' => $templateScores->count(),
                    'total_cost' => $templateScores->sum('metric_value'),
                ];
            })
            ->sortByDesc('usage_count')
            ->take(5)
            ->toArray();

        // Daily usage trend
        $usageTrend = $usage->groupBy('date')
            ->map(function ($dayUsage) {
                return [
                    'count' => $dayUsage->count(),
                    'cost' => $dayUsage->sum('metric_value'),
                ];
            })
            ->toArray();

        return [
            'total_usage' => $totalUsage,
            'total_cost' => $totalCost,
            'popular_templates' => $templateUsage,
            'usage_trend' => $usageTrend,
        ];
    }

    public function getConversionAnalytics(User $user, $startDate)
    {
        $conversions = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'conversion')
            ->where('date', '>=', $startDate)
            ->get();

        if ($conversions->isEmpty()) {
            return [
                'total_conversions' => 0,
                'conversion_rate' => 0,
                'revenue' => 0,
                'conversion_by_score' => [],
                'trend' => [],
            ];
        }

        $totalConversions = $conversions->where('metric_value', 1)->count();
        $totalLeads = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'lead_scoring')
            ->where('date', '>=', $startDate)
            ->count();

        $conversionRate = $totalLeads > 0 ? ($totalConversions / $totalLeads) * 100 : 0;
        $totalRevenue = $conversions->sum('metadata.revenue');

        // Conversion rate by score range
        $conversionByScore = [];
        $scoreRanges = [
            '80-100' => [80, 100],
            '60-79' => [60, 79],
            '40-59' => [40, 59],
            '0-39' => [0, 39],
        ];

        foreach ($scoreRanges as $range => [$min, $max]) {
            $leadsInRange = Analytics::where('user_id', $user->id)
                ->where('metric_type', 'lead_scoring')
                ->whereBetween('metric_value', [$min, $max])
                ->where('date', '>=', $startDate)
                ->count();

            $conversionsInRange = $conversions
                ->where('metadata.lead_score', '>=', $min)
                ->where('metadata.lead_score', '<=', $max)
                ->where('metric_value', 1)
                ->count();

            $conversionByScore[$range] = [
                'leads' => $leadsInRange,
                'conversions' => $conversionsInRange,
                'rate' => $leadsInRange > 0 ? ($conversionsInRange / $leadsInRange) * 100 : 0,
            ];
        }

        // Daily conversion trend
        $trend = $conversions->groupBy('date')
            ->map(function ($dayConversions) {
                return [
                    'conversions' => $dayConversions->where('metric_value', 1)->count(),
                    'revenue' => $dayConversions->sum('metadata.revenue'),
                ];
            })
            ->toArray();

        return [
            'total_conversions' => $totalConversions,
            'conversion_rate' => round($conversionRate, 2),
            'revenue' => $totalRevenue,
            'conversion_by_score' => $conversionByScore,
            'trend' => $trend,
        ];
    }

    public function getRevenueAnalytics(User $user, $startDate)
    {
        $revenue = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'conversion')
            ->where('date', '>=', $startDate)
            ->where('metadata->revenue', '>', 0)
            ->get();

        if ($revenue->isEmpty()) {
            return [
                'total_revenue' => 0,
                'average_deal_size' => 0,
                'revenue_trend' => [],
                'roi' => 0,
            ];
        }

        $totalRevenue = $revenue->sum('metadata.revenue');
        $averageDealSize = $revenue->avg('metadata.revenue');

        // Calculate ROI (Revenue - Coin Costs)
        $coinCosts = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'template_usage')
            ->where('date', '>=', $startDate)
            ->sum('metric_value');

        $roi = $coinCosts > 0 ? (($totalRevenue - $coinCosts) / $coinCosts) * 100 : 0;

        // Daily revenue trend
        $revenueTrend = $revenue->groupBy('date')
            ->map(function ($dayRevenue) {
                return $dayRevenue->sum('metadata.revenue');
            })
            ->mapWithKeys(function ($value, $date) {
                // Convert the date key to a proper format
                return [Carbon::parse($date)->format('Y-m-d') => $value];
            })
            ->toArray(); // Convert Collection to array

        return [
            'total_revenue' => $totalRevenue,
            'average_deal_size' => round($averageDealSize, 2),
            'revenue_trend' => $revenueTrend,
            'roi' => round($roi, 2),
        ];
    }

    public function getCoinUsageAnalytics(User $user, $startDate)
    {
        $coinUsage = Analytics::where('user_id', $user->id)
            ->where('metric_type', 'template_usage')
            ->where('date', '>=', $startDate)
            ->get();

        if ($coinUsage->isEmpty()) {
            return [
                'total_spent' => 0,
                'average_per_day' => 0,
                'spending_trend' => [],
                'category_breakdown' => [],
            ];
        }

        $totalSpent = $coinUsage->sum('metric_value');
        $days = Carbon::now()->diffInDays($startDate) + 1;
        $averagePerDay = $totalSpent / $days;

        // Daily spending trend
        $spendingTrend = $coinUsage->groupBy('date')
            ->map(function ($dayUsage) {
                return $dayUsage->sum('metric_value');
            })
            ->toArray();

        // Category breakdown (by template type)
        $categoryBreakdown = $coinUsage->groupBy('metadata.template_id')
            ->map(function ($templateUsage, $templateId) {
                $template = AiTemplate::find($templateId);
                return [
                    'category' => $template ? $this->getTemplateCategory($template) : 'Unknown',
                    'amount' => $templateUsage->sum('metric_value'),
                ];
            })
            ->groupBy('category')
            ->map(function ($categoryUsage) {
                return $categoryUsage->sum('amount');
            })
            ->toArray();

        return [
            'total_spent' => $totalSpent,
            'average_per_day' => round($averagePerDay, 2),
            'spending_trend' => $spendingTrend,
            'category_breakdown' => $categoryBreakdown,
        ];
    }

    private function getTemplateCategory($template)
    {
        // Simple categorization based on template name
        $name = strtolower($template->name);
        
        if (str_contains($name, 'email') || str_contains($name, 'message')) {
            return 'Communication';
        } elseif (str_contains($name, 'voice') || str_contains($name, 'call')) {
            return 'Voice';
        } elseif (str_contains($name, 'proposal') || str_contains($name, 'pitch')) {
            return 'Sales';
        } else {
            return 'Other';
        }
    }

    public function getGlobalAnalytics($period = 30)
    {
        $startDate = Carbon::now()->subDays($period);
        
        return [
            'total_users' => User::count(),
            'active_users' => User::whereHas('analytics', function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            })->count(),
            'total_leads_scored' => Analytics::where('metric_type', 'lead_scoring')
                ->where('date', '>=', $startDate)
                ->count(),
            'total_template_usage' => Analytics::where('metric_type', 'template_usage')
                ->where('date', '>=', $startDate)
                ->count(),
            'total_revenue' => Analytics::where('metric_type', 'conversion')
                ->where('date', '>=', $startDate)
                ->sum('metadata->revenue'),
        ];
    }
} 