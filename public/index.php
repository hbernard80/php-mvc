<?php

declare(strict_types=1);

use App\Core\Environment\Env;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');

$container = require dirname(__DIR__) . '/config/container.php';
$router = $container->get(\AltoRouter::class);
$match = $router->match();

if ($match === false) {
    http_response_code(404);
    echo 'Not Found';
    exit;
}

$target = $match['target'];
$params = array_values($match['params']);

if (is_string($target)) {
    $controller = $container->get($target);

    if (!is_callable($controller)) {
        throw new RuntimeException(sprintf('Controller "%s" is not invokable.', $target));
    }

    $controller(...$params);

    return;
}

if (is_callable($target)) {
    $target(...$params);

    return;
}

throw new RuntimeException('Unable to resolve route target.');
