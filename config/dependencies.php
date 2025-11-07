<?php

declare(strict_types=1);

use function DI\autowire;

return [
    App\Service\GreetingService::class => autowire(),
    App\Controller\HomeController::class => autowire(),
];
