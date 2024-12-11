<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;

class ThemeService
{
    public function getTheme($component, $key, $default = null)
    {
        $themeName = config('saashovel.APP_THEME', 'light');
        $themes = Config::get('themes');

        $value = $themes[$themeName][$component][$key] ??
                 $themes[$themeName]['global'][$key] ??
                 $default;

        return is_array($value) ? implode(' ', $value) : (string)$value;
    }

    public function setTheme($themeName)
    {
        if (array_key_exists($themeName, Config::get('themes'))) {
            config(['saashovel.APP_THEME' => $themeName]);
        }
    }
}
