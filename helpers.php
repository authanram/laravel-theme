<?php

use Authanram\Theme\Services\ThemeService;

if (! function_exists('theme')) {
    function theme($keys, $default = null, bool $throw = true): string
    {
        return trim(ThemeService::get($keys, $default, $throw));
    }
}
