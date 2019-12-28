<?php

namespace Authanram\Theme\Services;

use Authanram\Theme\Contracts\ThemeService as ThemeServiceContract;
use Symfony\Component\Yaml\Yaml;

class ThemeService implements ThemeServiceContract
{
    public static function get(): string
    {
        $args = \func_get_args();

        $cssClasses = '';

        foreach ($args as $arg) {

            if (! \is_string($arg)) {

                continue;

            }

            $value = config("authanram-theme.$arg");

            if (! \is_string($value)) {

                continue;

            }

            $cssClasses .= ' ' . $value;

        }

        return $cssClasses;
    }

    public static function make(array $configuration): array
    {
        $result = [];

        foreach ($configuration['paths'] ?? [] as $path) {

            if (! file_exists($path)) {

                continue;

            }

            $current = $result;

            $parsedFile = (array)Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);

            $result = array_merge($current, $parsedFile);

        }

        return static::prepare($result, $configuration['replace'] ?? []);
    }

    private static function prepare(array $theme, array $replaces): array
    {
        if (empty(\count($replaces))) {

            return $theme;

        }

        $json = \json_encode($theme, JSON_THROW_ON_ERROR, 512);

        foreach ($replaces as $search => $key) {

            $json = \str_replace($search, data_get($theme, $key), $json);

        }

        $theme = \json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        unset($theme['paths'], $theme['replace']);

        return (array)$theme;
    }
}
