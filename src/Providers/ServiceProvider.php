<?php

namespace Authanram\Theme\Providers;

use Authanram\Theme\Contracts;
use Authanram\Theme\Services;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Contracts\ThemeService::class, Services\ThemeService::class);

        $this->mergeConfigFrom(__DIR__ . '/../../config.php', 'authanram-theme');

        $this->mergeThemeIntoConfiguration();
    }

    public function boot(): void
    {
        $this->publishes([

            __DIR__ . '/../../config.php' => config_path('authanram-theme.php'),

        ]);
    }

    private function mergeThemeIntoConfiguration(): void
    {
        $themeService = $this->app->make(Contracts\ThemeService::class);

        $theme = $themeService->use(config('authanram-theme'));

        $this->mergeConfig($theme, 'authanram-theme');
    }

    private function mergeConfig(array $config, string $key): void
    {
        if (! $this->app->configurationIsCached()) {

            data_get($this->app, 'config')->set($key, $config);

        }
    }
}
