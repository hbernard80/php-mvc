<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    if ($class === 'AltoRouter') {
        $file = __DIR__ . '/altorouter/altorouter/AltoRouter.php';
        if (is_file($file)) {
            require $file;
        }

        return;
    }

    $prefixes = [
        'App\\Core\\' => __DIR__ . '/../Core/',
        'App\\' => __DIR__ . '/../src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_file($file)) {
            require $file;
        }

        return;
    }
});
