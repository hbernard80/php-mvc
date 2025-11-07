<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GreetingService;

final class HomeController
{
    public function __construct(private readonly GreetingService $greetingService)
    {
    }

    public function __invoke(): void
    {
        echo $this->greetingService->greet('World');
    }
}
