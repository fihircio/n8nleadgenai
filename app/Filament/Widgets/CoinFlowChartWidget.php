<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use Bavix\Wallet\Models\Transaction as WalletTransaction;
use Illuminate\Support\Carbon;

class CoinFlowChartWidget extends BarChartWidget
{
    protected static ?string $heading = 'Coin Flow (Last 30 Days)';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end = Carbon::now()->endOfDay();
        $dates = collect(range(0, 29))->map(fn($i) => $start->copy()->addDays($i)->format('Y-m-d'));

        $transactions = WalletTransaction::whereBetween('created_at', [$start, $end])->get();
        $daily = $dates->mapWithKeys(function($date) use ($transactions) {
            $in = $transactions->where('type', 'deposit')->where('created_at', '>=', $date.' 00:00:00')->where('created_at', '<=', $date.' 23:59:59')->sum('amount');
            $out = $transactions->where('type', 'withdraw')->where('created_at', '>=', $date.' 00:00:00')->where('created_at', '<=', $date.' 23:59:59')->sum('amount');
            return [$date => ['in' => $in, 'out' => $out]];
        });

        return [
            'labels' => $dates->toArray(),
            'datasets' => [
                [
                    'label' => 'Coins In',
                    'backgroundColor' => '#34d399',
                    'data' => $daily->pluck('in')->toArray(),
                ],
                [
                    'label' => 'Coins Out',
                    'backgroundColor' => '#f87171',
                    'data' => $daily->pluck('out')->toArray(),
                ],
            ],
        ];
    }
}
