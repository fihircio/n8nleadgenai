<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $analyticsService = app(AnalyticsService::class);
        $globalAnalytics = $analyticsService->getGlobalAnalytics(30);

        return [
            Stat::make('Total Users', $globalAnalytics['total_users'])
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Users', $globalAnalytics['active_users'])
                ->description('Users with activity in last 30 days')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Leads Scored', $globalAnalytics['total_leads_scored'])
                ->description('AI lead scoring operations')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),

            Stat::make('Template Usage', $globalAnalytics['total_template_usage'])
                ->description('AI template purchases')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Total Revenue', '$' . number_format($globalAnalytics['total_revenue'], 2))
                ->description('Generated revenue')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
} 