<?php

namespace Authanram\Theme\Contracts;

interface ThemeService
{
    public function get(): string;

    public function use(array $configuration): array;
}
