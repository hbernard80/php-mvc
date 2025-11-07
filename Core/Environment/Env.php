<?php

declare(strict_types=1);

namespace App\Core\Environment;

final class Env
{
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (!is_file($path)) {
            self::$loaded = true;

            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            $value = self::stripQuotes($value);
            $value = self::expandVariables($value);

            if ($name === '') {
                continue;
            }

            if (!array_key_exists($name, $_ENV) && !array_key_exists($name, $_SERVER)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }

        self::$loaded = true;
    }

    private static function stripQuotes(string $value): string
    {
        if ($value === '' || strlen($value) < 2) {
            return $value;
        }

        $firstChar = $value[0];
        $lastChar = $value[strlen($value) - 1];

        if (($firstChar === '"' && $lastChar === '"') || ($firstChar === "'" && $lastChar === "'")) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    private static function expandVariables(string $value): string
    {
        return preg_replace_callback('/\${([A-Z0-9_]+)}/i', static function (array $matches): string {
            $replacement = getenv($matches[1]);

            if ($replacement === false) {
                return '';
            }

            return $replacement;
        }, $value) ?? $value;
    }
}
