<?php

namespace Authanram\Theme;

class ThemeService
{
    public static function getValue(): string
    {
        $args = \func_get_args();

        $cssClasses = '';

        foreach ($args as $arg) {

            if (! \is_string($arg)) {

                continue;

            }

            $value = config("authanram-resources.theme.$arg");

            if (! \is_string($value)) {

                continue;

            }

            $cssClasses .= ' ' . $value;

        }

        return $cssClasses;
    }

    public static function get(string $filePath): array
    {
        $theme = Yaml::parseFile($filePath);

        return static::prepare($theme);
    }

    private static function prepare(\stdClass $theme): array
    {
        $accent = $theme->accent;

        $json = \json_encode($theme, JSON_THROW_ON_ERROR, 512);

        $preparedJson = \str_replace('%accent%', $accent, $json);

        $preparedTheme = \json_decode($preparedJson, true, 512, JSON_THROW_ON_ERROR);

        return (array)$preparedTheme;
    }
}
