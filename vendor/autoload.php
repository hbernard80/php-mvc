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

    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
