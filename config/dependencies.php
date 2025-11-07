<?php

declare(strict_types=1);

use App\Database\DatabaseConnection;
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
    \PDO::class => static fn (): \PDO => DatabaseConnection::getInstance(),
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
