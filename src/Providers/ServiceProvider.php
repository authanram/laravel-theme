<?php

namespace Authanram\Theme\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config.php', 'authanram-theme');
    }

    public function boot(): void
    {
    }
}
