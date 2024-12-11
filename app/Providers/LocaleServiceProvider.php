<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\SetLocale;
use Spatie\Translatable\Facades\Translatable;

class LocaleServiceProvider extends ServiceProvider
{
    public function register()
    {
        Translatable::fallback(
            fallbackAny: true,
        );
    }

    public function boot()
    {
        $setLocale = new SetLocale();
        $setLocale->setLocale($this->app['request']);
    }
}
