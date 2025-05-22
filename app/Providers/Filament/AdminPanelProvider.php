<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\Sky\Filament\Resources\FaqResource;
use LaraZeus\Sky\Filament\Resources\LibraryResource;
use LaraZeus\Sky\Filament\Resources\PostResource;
use LaraZeus\Sky\Filament\Resources\TagResource;
use LaraZeus\Sky\SkyPlugin;
use LaraZeus\Wind\WindPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $widgets = [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ];

        if (config('saashovel.GOOGLE_ANALYTICS_WIDGETS')) {
            $widgets = array_merge($widgets, [
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\PageViewsWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\VisitorsWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersOneDayWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersSevenDayWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersTwentyEightDayWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsDurationWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsByCountryWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsByDeviceWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\MostVisitedPagesWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\TopReferrersListWidget::class,
            ]);
        }

        $widgets[] = \App\Filament\Widgets\CoinStatsWidget::class;
        $widgets[] = \App\Filament\Widgets\CoinFlowChartWidget::class;

        $contact = WindPlugin::make()
        ->navigationGroupLabel('Contact')
        ->windPrefix('contact')
        ->windMiddleware(['web'])
        ->defaultDepartmentId(1)
        ->defaultStatus('NEW')
        ->departmentResource()
        ->windModels([
            'Department' => \LaraZeus\Wind\Models\Department::class,
            'Letter' => \LaraZeus\Wind\Models\Letter::class,
        ])
        ->uploadDisk('public')
        ->uploadDirectory('logos');

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            //->login()
            ->spa()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon(asset('favicon.png'))
            ->plugins([
                SpatieLaravelTranslatablePlugin::make()->defaultLocales(config('app.available_locales')),
                FilamentEnvEditorPlugin::make()
                    ->authorize(fn () => auth()->user()->can('access admin panel'))
                    ->navigationGroup('System Tools')
                    ->navigationLabel('My Env.')
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    //->navigationSort(1)
                    ->slug('env-editor'),
                SkyPlugin::make()
                    ->navigationGroupLabel('Site')
                    ->hideResources([
                        PostResource::class,
                        //FaqResource::class,
                        LibraryResource::class,
                        TagResource::class,
                ]),
                $contact, // comment this line if you don't want to use the contact form
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets($widgets)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
