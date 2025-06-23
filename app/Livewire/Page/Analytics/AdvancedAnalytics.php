<?php

namespace App\Livewire\Page\Analytics;

use App\Services\AnalyticsService;
use App\Models\Conversion;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Title('Advanced Analytics')]
class AdvancedAnalytics extends Component
{
    public $startDate;
    public $endDate;
    public $selectedMetrics = ['lead_scoring', 'template_usage', 'conversions', 'revenue'];
    public $chartType = 'line';
    public $analytics = [];
    public $conversionData = [];
    public $chartData = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        $analyticsService = app(AnalyticsService::class);
        $this->analytics = $analyticsService->getUserAnalytics(
            Auth::user(), 
            Carbon::parse($this->startDate)->diffInDays($this->endDate)
        );
        
        $this->loadConversionData();
        $this->prepareChartData();
        
        // Emit event to update charts
        $this->dispatch('analytics-updated', [
            'lead_scoring' => $this->chartData['lead_scoring'],
            'revenue' => $this->chartData['revenue']
        ]);
    }

    public function loadConversionData()
    {
        $this->conversionData = Conversion::where('user_id', Auth::id())
            ->whereBetween('conversion_date', [$this->startDate, $this->endDate])
            ->with(['lead', 'aiLeadScore'])
            ->get()
            ->groupBy('conversion_type')
            ->map(function ($conversions) {
                return [
                    'count' => $conversions->count(),
                    'revenue' => $conversions->sum('revenue'),
                    'completed' => $conversions->where('status', 'completed')->count(),
                ];
            })
            ->toArray();
    }

    public function prepareChartData()
    {
        // Prepare chart data as arrays for JavaScript
        $this->chartData = [
            'lead_scoring' => [
                'labels' => [],
                'data' => [],
            ],
            'revenue' => [
                'labels' => [],
                'data' => [],
            ],
        ];

        // Lead scoring trend data
        if (isset($this->analytics['lead_scoring']['trend']) && is_array($this->analytics['lead_scoring']['trend'])) {
            $this->chartData['lead_scoring']['labels'] = array_map(function($date) {
                return Carbon::parse($date)->format('M d');
            }, array_keys($this->analytics['lead_scoring']['trend']));
            
            $this->chartData['lead_scoring']['data'] = array_values(array_map(function($data) {
                return round($data['average'] ?? 0, 1);
            }, $this->analytics['lead_scoring']['trend']));
        }

        // Revenue trend data
        if (isset($this->analytics['revenue']['revenue_trend']) && is_array($this->analytics['revenue']['revenue_trend'])) {
            $this->chartData['revenue']['labels'] = array_map(function($date) {
                return Carbon::parse($date)->format('M d');
            }, array_keys($this->analytics['revenue']['revenue_trend']));
            
            $this->chartData['revenue']['data'] = array_values(array_map(function($value) {
                return round($value, 2);
            }, $this->analytics['revenue']['revenue_trend']));
        }
    }

    public function updatedStartDate()
    {
        $this->loadAnalytics();
    }

    public function updatedEndDate()
    {
        $this->loadAnalytics();
    }

    public function exportData($format = 'csv')
    {
        $data = $this->prepareExportData();
        
        if ($format === 'csv') {
            return $this->exportToCsv($data);
        } elseif ($format === 'json') {
            return $this->exportToJson($data);
        }
        
        // Return a default response if format is not recognized
        return response()->json(['error' => 'Invalid export format'], 400);
    }

    private function prepareExportData()
    {
        $data = [
            'period' => [
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ],
            'lead_scoring' => $this->analytics['lead_scoring'] ?? [],
            'template_usage' => $this->analytics['template_usage'] ?? [],
            'conversions' => $this->analytics['conversions'] ?? [],
            'revenue' => $this->analytics['revenue'] ?? [],
            'coin_usage' => $this->analytics['coin_usage'] ?? [],
            'conversion_details' => Conversion::where('user_id', Auth::id())
                ->whereBetween('conversion_date', [$this->startDate, $this->endDate])
                ->with(['lead', 'aiLeadScore'])
                ->get()
                ->map(function ($conversion) {
                    return [
                        'lead_name' => $conversion->lead->name,
                        'lead_email' => $conversion->lead->email,
                        'lead_company' => $conversion->lead->company,
                        'conversion_type' => $conversion->conversion_type,
                        'status' => $conversion->status,
                        'revenue' => $conversion->revenue,
                        'deal_size' => $conversion->deal_size,
                        'conversion_date' => $conversion->conversion_date->format('Y-m-d'),
                        'ai_score' => $conversion->aiLeadScore ? $conversion->aiLeadScore->score : null,
                    ];
                })
                ->toArray(),
        ];

        return $data;
    }

    private function exportToCsv($data)
    {
        $filename = 'analytics_' . $this->startDate . '_to_' . $this->endDate . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Summary data
            fputcsv($file, ['Analytics Summary']);
            fputcsv($file, ['Period', $data['period']['start_date'] . ' to ' . $data['period']['end_date']]);
            fputcsv($file, ['']);
            
            // Lead scoring summary
            fputcsv($file, ['Lead Scoring Summary']);
            fputcsv($file, ['Total Leads', 'Average Score', 'Hot Leads', 'Warm Leads', 'Cold Leads']);
            fputcsv($file, [
                $data['lead_scoring']['total_leads'] ?? 0,
                $data['lead_scoring']['average_score'] ?? 0,
                $data['lead_scoring']['hot_leads'] ?? 0,
                $data['lead_scoring']['warm_leads'] ?? 0,
                $data['lead_scoring']['cold_leads'] ?? 0,
            ]);
            fputcsv($file, ['']);
            
            // Conversion details
            fputcsv($file, ['Conversion Details']);
            fputcsv($file, ['Lead Name', 'Email', 'Company', 'Type', 'Status', 'Revenue', 'Deal Size', 'Date', 'AI Score']);
            
            foreach ($data['conversion_details'] as $conversion) {
                fputcsv($file, [
                    $conversion['lead_name'],
                    $conversion['lead_email'],
                    $conversion['lead_company'],
                    $conversion['conversion_type'],
                    $conversion['status'],
                    $conversion['revenue'],
                    $conversion['deal_size'],
                    $conversion['conversion_date'],
                    $conversion['ai_score'],
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToJson($data)
    {
        $filename = 'analytics_' . $this->startDate . '_to_' . $this->endDate . '.json';
        
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.analytics.advanced-analytics');
    }
} 