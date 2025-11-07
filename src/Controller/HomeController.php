<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GreetingService;
use Twig\Environment;

final class HomeController
{
    public function __construct(
        private readonly GreetingService $greetingService,
        private readonly Environment $twig,
    )
    {
    }

    public function __invoke(): void
    {
        echo $this->twig->render('home.html.twig', [
            'greeting' => $this->greetingService->greet('World'),
        ]);
    }
}
