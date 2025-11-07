<?php

declare(strict_types=1);

use App\Controller\HomeController;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = require dirname(__DIR__) . '/config/container.php';

$controller = $container->get(HomeController::class);
$controller();
