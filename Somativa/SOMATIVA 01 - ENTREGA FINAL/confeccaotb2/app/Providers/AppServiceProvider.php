<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => '<link rel="stylesheet" href="' . asset('css/filament-custom.css') . '?v=' . filemtime(public_path('css/filament-custom.css')) . '">',
        );
    }
}
