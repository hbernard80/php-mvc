<?php

declare(strict_types=1);

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
];
