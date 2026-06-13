<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\RecentLeads;
use App\Filament\Widgets\RecentPosts;
use App\Filament\Widgets\StatsOverview;
use App\Http\Middleware\FilamentAuthenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('CyT Comunicaciones')
            ->brandLogo(asset('img/logo.png'))
            ->brandLogoHeight('2.2rem')
            ->colors([
                'primary' => Color::Purple,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverview::class,
                RecentPosts::class,
                RecentLeads::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
<style>
/* Sidebar formulario Posts — ancho fijo para consistencia */
@media (min-width: 1024px) {
    .fi-resource-posts-resource .fi-sc-flex > div:last-child {
        width: 320px !important;
        min-width: 320px !important;
        max-width: 320px !important;
        flex-shrink: 0 !important;
        position: sticky;
        top: 1rem;
        align-self: flex-start;
    }
    .fi-resource-posts-resource .fi-sc-flex > div:first-child {
        flex: 1 1 0%;
        min-width: 0;
    }
    /* La imagen no desborda el sidebar */
    .fi-resource-posts-resource .fi-sc-flex > div:last-child img,
    .fi-resource-posts-resource .fi-sc-flex > div:last-child .filepond--root {
        max-width: 100% !important;
    }
}
</style>
')
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                FilamentAuthenticate::class,
            ]);
    }
}
