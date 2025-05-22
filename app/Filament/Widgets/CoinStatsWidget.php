<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\User;
use Bavix\Wallet\Models\Transaction as WalletTransaction;

class CoinStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.coin-stats-widget';

    public static function canView(): bool
    {
        // Only show to admins
        return auth()->user()?->hasRole('admin');
    }

    public function getData(): array
    {
        return [
            'total_coins' => (int) User::all()->sum(fn($u) => $u->getCoinBalance()),
            'total_transactions' => WalletTransaction::count(),
            'last_transaction' => WalletTransaction::latest()->first(),
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $data = $this->getData();
        return view(static::$view, $data);
    }
}
