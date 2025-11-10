<?php

declare(strict_types=1);

use App\Core\Database\DatabaseConnection;
use App\Core\View\TemplateRenderer;

return [
    TemplateRenderer::class => static function (): TemplateRenderer {
        $templatesPath = dirname(__DIR__) . '/templates';

        return new TemplateRenderer($templatesPath);
    },
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
