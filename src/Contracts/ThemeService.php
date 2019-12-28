<?php

namespace Authanram\Theme\Contracts;

interface ThemeService
{
    public static function get(): string;

    public static function make(array $configuration): array;
}
