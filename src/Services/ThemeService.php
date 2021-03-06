<?php

namespace Authanram\Theme\Services;

use Authanram\Theme\Contracts\ThemeService as ThemeServiceContract;
use Authanram\Theme\Exceptions\ThemeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class ThemeService implements ThemeServiceContract
{
    public static function get($keys, $default = null, bool $throw = true): string
    {
        if (\is_string($keys)) {

            $keys = [$keys];

        }

        $cssClasses = '';

        $groupKey = '';

        foreach ($keys as $key) {

            if ($key === null) {

                continue;

            }

            if (! \is_string($key)) {

                throw new ThemeException("Expected string at theme path \"$key\".");

            }

            $keyOrigin = $key;

            $key = !empty($groupKey) ? $groupKey . $key : $key;

            $value = config("authanram-theme.$key");

            if (!empty($groupKey) && !$value) {

                $key = $keyOrigin;

                $value = config("authanram-theme.$key");

            }

            if (\is_array($value) && !Arr::isAssoc($value)) {

                $value = array_pop($value);

            }

            if (\is_array($value)) {

                $groupKey = $key . '.';

                continue;

            }

            $value = static::makeDefaultValue($key, $value, $default);

            if ($throw && $value === null) {

                throw new ThemeException("Theme path \"$key\" has not been set.");

            }

            if (! \is_string($value)) {

                continue;

            }

            $cssClasses .= " $value";

        }

        return trim($cssClasses);
    }

    public static function make(array $configuration): array
    {
        $files = [];

        foreach (data_get($configuration, 'paths') ?? [] as $path) {

            if (File::isDirectory($path)) {

                $fn = fn (\SplFileInfo $file) => $file->getPathname();

                $paths = collect(File::allFiles($path))->map($fn)->toArray();

                $current = $files;

                $files = array_merge($current, $paths);

            } else {

                $files[] = $path;

            }
        }

        $merged = static::merge($files);

        return static::prepare($merged, data_get($configuration, 'replace'));
    }

    private static function merge(array $files): array
    {
        $result = [];

        foreach ($files as $path) {

            if (! file_exists($path)) {

                continue;

            }

            $current = $result;

            $parsedFile = (array)Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);

            $result = array_merge_recursive($current, $parsedFile);

        }

        return $result;
    }

    private static function makeDefaultValue(string $key, ?string $value, $default): ?string
    {
        if (!empty($value)) {

            return $value;

        }

        return \is_array($default)

            ? config("authanram-theme.$default[$key]", $default[$key])

            : config("authanram-theme.$default", $default);
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
