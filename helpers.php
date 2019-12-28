<?php

$authanramThemeService = app()->make(\Authanram\Theme\Contracts\ThemeService::class);

if (! function_exists('theme')) {
    function theme(): string
    {
        global $authanramThemeService;

        $args = \func_get_args();

        return trim($authanramThemeService->get(...$args));
    }
}
