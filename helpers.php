<?php

use Authanram\Theme\Services\ThemeService;

if (! function_exists('theme')) {
    function theme(): string
    {
        $args = \func_get_args();

        return trim(ThemeService::get(...$args));
    }
}
