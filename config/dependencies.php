<?php

declare(strict_types=1);

use PDO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function DI\autowire;

return [
    Environment::class => static function (): Environment {
        $loader = new FilesystemLoader(dirname(__DIR__) . '/templates');

        return new Environment($loader);
    },
    App\Service\GreetingService::class => autowire(),
    App\Controller\HomeController::class => autowire(),
    PDO::class => static function (): PDO {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'app';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $dbname, $charset);

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    },
    \AltoRouter::class => static function (): \AltoRouter {
        $router = new \AltoRouter();
        $routes = require __DIR__ . '/routes.php';

        foreach ($routes as $route) {
            [$method, $path, $target, $name] = array_pad($route, 4, null);
            $router->map($method, $path, $target, $name);
        }

        return $router;
    },
];
