<?php

declare(strict_types=1);

namespace App\Core\Container;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use RuntimeException;

final class Container
{
    /**
     * @var array<class-string, mixed>
     */
    private array $definitions;

    /**
     * @var array<class-string, mixed>
     */
    private array $resolved = [];

    /**
     * @param array<class-string, mixed> $definitions
     */
    public function __construct(array $definitions = [])
    {
        $this->definitions = $definitions;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $value = $this->resolve($id);
        $this->resolved[$id] = $value;

        return $value;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions) || class_exists($id);
    }

    private function resolve(string $id): mixed
    {
        if (array_key_exists($id, $this->definitions)) {
            $definition = $this->definitions[$id];

            if ($definition instanceof Closure) {
                $reflection = new ReflectionFunction($definition);

                return $reflection->getNumberOfParameters() > 0
                    ? $definition($this)
                    : $definition();
            }

            if (is_string($definition)) {
                return $this->get($definition);
            }

            if (is_object($definition)) {
                return $definition;
            }

            throw new RuntimeException(sprintf('Unable to resolve definition for "%s".', $id));
        }

        if (!class_exists($id)) {
            throw new RuntimeException(sprintf('No entry found for "%s".', $id));
        }

        return $this->autowire($id);
    }

    private function autowire(string $class): object
    {
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(sprintf('Class "%s" is not instantiable.', $class));
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $arguments[] = $this->get($type->getName());

                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();

                continue;
            }

            throw new RuntimeException(
                sprintf(
                    'Unable to resolve parameter "$%s" of "%s::__construct()".',
                    $parameter->getName(),
                    $class,
                ),
            );
        }

        return $reflection->newInstanceArgs($arguments);
    }
}
