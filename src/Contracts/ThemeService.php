<?php

namespace Authanram\Theme\Contracts;

interface ThemeService
{
    /**
     * @param string[]|string $keys
     *
     * @param string[]|string|null $default
     *
     * @return string
     *
     * @param bool $throw
     *
     * @throws \Authanram\Theme\Exceptions\ThemeException
     */
    public static function get($keys, $default = null, bool $throw = true): string;

    public static function make(array $configuration): array;
}
