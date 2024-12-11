<?php

namespace App\Providers;

use App\Filament\CustomLogoutResponse;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Filament::serving(function () {
            // Override the default logout response
            app()->singleton(\Filament\Http\Responses\Auth\LogoutResponse::class, CustomLogoutResponse::class);
        });
    }
}
