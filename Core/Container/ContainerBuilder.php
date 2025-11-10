<?php

declare(strict_types=1);

namespace App\Core\Container;

final class ContainerBuilder
{
    /**
     * @var array<class-string, mixed>
     */
    private array $definitions = [];

    /**
     * @param array<class-string, mixed> $definitions
     */
    public function addDefinitions(array $definitions): void
    {
        $this->definitions = array_merge($this->definitions, $definitions);
    }

    public function build(): Container
    {
        return new Container($this->definitions);
    }
}
