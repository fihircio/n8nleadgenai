<?php

namespace Tcja\NOWPaymentsLaravel;

use Illuminate\Support\ServiceProvider;

class NOWPaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nowpayments.php', 'nowpayments');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/nowpayments.php' => config_path('nowpayments.php'),
        ], 'nowpayments-config');

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->publishes([
            __DIR__.'/Database/Migrations' => database_path('migrations'),
        ], 'nowpayments-migrations');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
