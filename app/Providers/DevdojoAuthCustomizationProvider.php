<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * DevdojoAuthCustomizationProvider
 *
 * This service provider is responsible for customizing the Devdojo authentication
 * configuration by merging custom language settings and translating the resulting configuration.
 */
class DevdojoAuthCustomizationProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeCustomConfig();
    }

    protected function mergeCustomConfig()
    {
        $packageConfig = config('devdojo.auth.language');
        $customConfig = config('devdojo-auth-language-custom');

        $mergedConfig = array_replace_recursive($packageConfig, $customConfig);

        // Translate the merged config
        $translatedConfig = $this->translateConfig($mergedConfig);

        config(['devdojo.auth.language' => $translatedConfig]);
    }

    protected function translateConfig($config)
    {
        return collect($config)->map(function ($item) {
            if (is_array($item)) {
                return $this->translateConfig($item);
            }
            if (is_string($item)) {
                return __($item);
            }
            return $item;
        })->all();
    }
}
