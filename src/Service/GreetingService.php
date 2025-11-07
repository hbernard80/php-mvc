<?php

declare(strict_types=1);

namespace App\Service;

final class GreetingService
{
    public function greet(string $name): string
    {
        return sprintf('Hello, %s!', $name);
    }
}
