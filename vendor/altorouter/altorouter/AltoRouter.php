<?php

declare(strict_types=1);

/**
 * Lightweight routing system compatible with the AltoRouter API.
 */
class AltoRouter
{
    /**
     * @var array<int, array{0:mixed,1:string,2:mixed,3:null|string}>
     */
    protected array $routes = [];

    /**
     * @var array<string, string>
     */
    protected array $namedRoutes = [];

    protected string $basePath = '';

    /**
     * @var array<string, string>
     */
    protected array $matchTypes = [
        'i' => '[0-9]++',
        'a' => '[0-9A-Za-z]++',
        'h' => '[0-9A-Fa-f]++',
        '*' => '.+?',
        's' => '[0-9A-Za-z-_]++',
        ''  => '[^/]++',
    ];

    /**
     * @param array<int, array{0:mixed,1:string,2:mixed,3:null|string}> $routes
     * @param string $basePath
     */
    public function __construct(array $routes = [], string $basePath = '')
    {
        $this->setBasePath($basePath);
        $this->addRoutes($routes);
    }

    /**
     * @return $this
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = rtrim($basePath, '/');

        return $this;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param array<int, array{0:mixed,1:string,2:mixed,3:null|string}> $routes
     */
    public function addRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            $this->map(...$route);
        }
    }

    /**
     * @param string|string[] $method
     * @param mixed           $target
     */
    public function map($method, string $route, $target, ?string $name = null): void
    {
        $this->routes[] = [$method, $route, $target, $name];

        if ($name !== null) {
            $this->namedRoutes[$name] = $route;
        }
    }

    /**
     * @return array{target:mixed,params:array<string,string>,name:null|string}|false
     */
    public function match(?string $requestUrl = null, ?string $requestMethod = null)
    {
        $requestMethod = $requestMethod ?? ($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $requestUrl = $requestUrl ?? ($_SERVER['REQUEST_URI'] ?? '/');
        $requestUrl = parse_url($requestUrl, PHP_URL_PATH) ?: '/';
        $requestUrl = $this->stripBasePath($requestUrl);
        $requestUrl = rawurldecode($requestUrl);

        foreach ($this->routes as $route) {
            [$methods, $routePattern, $target, $name] = $route;

            if ($methods !== '*' && !$this->methodMatches($methods, $requestMethod)) {
                continue;
            }

            $compiled = $this->compileRoute($routePattern);

            if (!preg_match($compiled['regex'], $requestUrl, $matches)) {
                continue;
            }

            $params = [];
            foreach ($compiled['params'] as $paramName) {
                if (isset($matches[$paramName])) {
                    $params[$paramName] = rawurldecode($matches[$paramName]);
                }
            }

            return [
                'target' => $target,
                'params' => $params,
                'name' => $name,
            ];
        }

        return false;
    }

    /**
     * @param array<string, scalar> $params
     */
    public function generate(string $routeName, array $params = []): ?string
    {
        if (!isset($this->namedRoutes[$routeName])) {
            return null;
        }

        $route = $this->namedRoutes[$routeName];

        $url = preg_replace_callback(
            '/\[(?:(\w+|\*):)?([a-zA-Z_][a-zA-Z0-9_-]*)\]/',
            function (array $matches) use (&$params): string {
                $paramName = $matches[2];

                if (!array_key_exists($paramName, $params)) {
                    throw new InvalidArgumentException(sprintf('Missing parameter "%s" for route generation.', $paramName));
                }

                $value = (string) $params[$paramName];

                return rawurlencode($value);
            },
            $route
        );

        return $this->basePath . $url;
    }

    /**
     * @param string|string[] $allowedMethods
     */
    protected function methodMatches($allowedMethods, string $requestMethod): bool
    {
        if ($allowedMethods === '*') {
            return true;
        }

        $methods = is_array($allowedMethods)
            ? $allowedMethods
            : explode('|', (string) $allowedMethods);

        foreach ($methods as $method) {
            if (strtoupper($method) === strtoupper($requestMethod)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{regex:string,params:string[]}
     */
    protected function compileRoute(string $route): array
    {
        $route = $this->normalizeRoute($route);
        $paramNames = [];

        $regex = preg_replace_callback(
            '/\[(?:(\w+|\*):)?([a-zA-Z_][a-zA-Z0-9_-]*)\]/',
            function (array $matches) use (&$paramNames): string {
                $type = $matches[1] ?? '';
                $name = $matches[2];
                $paramNames[] = $name;

                $pattern = $this->matchTypes[$type] ?? $this->matchTypes[''];

                return '(?P<' . $name . '>' . $pattern . ')';
            },
            $route
        );

        return [
            'regex' => '#^' . $regex . '$#u',
            'params' => $paramNames,
        ];
    }

    protected function normalizeRoute(string $route): string
    {
        if ($route === '') {
            return '/';
        }

        $route = '/' . ltrim($route, '/');

        if ($route !== '/' && str_ends_with($route, '/')) {
            $route = rtrim($route, '/');
        }

        return $route;
    }

    protected function stripBasePath(string $requestUrl): string
    {
        if ($this->basePath === '') {
            return $requestUrl;
        }

        if (str_starts_with($requestUrl, $this->basePath)) {
            $requestUrl = substr($requestUrl, strlen($this->basePath));
        }

        return $requestUrl === '' ? '/' : $requestUrl;
    }
}
