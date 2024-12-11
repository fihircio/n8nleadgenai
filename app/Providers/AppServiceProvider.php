<?php

namespace App\Providers;

use App\BillableTraitSelector;
use Illuminate\Support\ServiceProvider;

use App\Livewire\NavigationMenu;
use App\Livewire\Page\Contact\CustomContactsForm;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use LaraZeus\Wind\Livewire\ContactsForm;
use LemonSqueezy\Laravel\LemonSqueezy;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        LemonSqueezy::ignoreMigrations();

        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService();
        });
    }

    public function boot(): void
    {
        if (!$this->shouldSkipTraitSelection()) {
            BillableTraitSelector::selectTrait();
        }

        Blade::directive('theme', function ($expression) {
            return "<?php echo app(\App\Services\ThemeService::class)->getTheme($expression); ?>";
        });

        $this->handleWebhooks();

        if (!file_exists(public_path('storage'))) {
            $this->createStorageLink();
        }

        /* This is used to hide "post_link" and "library_link" in the admin panel when choosing
           the type of page link, if you use those features, you can comment the code below and
           update AdminPanelProvider.php SkyPlugin::make()->hideResources([The resources to hide]) */
        FilamentAsset::register([
            Js::make('hide-options', __DIR__ . '/../../resources/js/hide_options.js'),
        ], package: 'lara-zeus');

        Livewire::component('navigation-menu', NavigationMenu::class);

        Blade::component('components.auth.elements.social-button', 'auth::elements.social-button');
        Blade::component('components.auth.elements.social-providers', 'auth::elements.social-providers');
        Blade::component('components.auth.elements.container', 'auth::elements.container');
        Blade::component('components.auth.elements.button', 'auth::elements.button');
        Blade::component('components.auth.elements.separator', 'auth::elements.separator');
        Blade::component('components.auth.elements.input-code', 'auth::elements.input-code');

        $this->app->bind(ContactsForm::class, CustomContactsForm::class);
    }

    protected function shouldSkipTraitSelection(): bool
    {
        if (app()->runningInConsole()) {
            $excludedCommands = [
                'optimize',
                'optimize:clear',
                'config:cache',
                'route:cache',
                'view:cache',
                // Add any other commands to exclude
            ];

            $currentCommand = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : null;

            return in_array($currentCommand, $excludedCommands);
        }

        // Don't skip by default
        return false;
    }

    protected function createStorageLink()
    {
        try {
            Artisan::call('storage:link');
        } catch (\Exception $e) {}
    }

    private function handleWebhooks(): void
    {
        Route::post('/stripe/webhook', function (\Illuminate\Http\Request $request) {
            $stripeController = new \Laravel\Cashier\Http\Controllers\WebhookController();
            return $stripeController->handleWebhook($request);
        })->name('cashier.webhook')->middleware('api');

        Route::post('/paddle/webhook', function (\Illuminate\Http\Request $request) {
            $paddleController = new \Laravel\Paddle\Http\Controllers\WebhookController();
            return $paddleController($request);
        })->name('cashier.paddle.webhook')->middleware('api');
    }
}
