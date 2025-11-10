<?php

declare(strict_types=1);

use DI\ContainerBuilder;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require __DIR__ . '/dependencies.php');

return $containerBuilder->build();
