<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $this->setLocale($request);
        return $next($request);
    }

    public function setLocale(Request $request)
    {
        if (config('app.use_browser_locale')) {
            $browserLocale = $request->getPreferredLanguage(config('app.available_locales'));
            if ($browserLocale !== config('app.locale')) {
                App::setLocale($browserLocale);
            }
        }
    }
}
