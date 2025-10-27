<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Extend the view function to include the current theme's views
        view()->macro('theme', function ($view, $data = [], $mergeData = []) {
            $currentTheme = env('CURRENT_THEME', 'basic');
            $themeView = "frontend.themes.$currentTheme.$view";

            return view($themeView, $data, $mergeData);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
