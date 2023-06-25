<?php

use DI\Container;
use DI\Bridge\Slim\Bridge as SlimAppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once './env.php';

session_cache_limiter(false);
session_start();

$container = new Container;
$app = SlimAppFactory::create($container);
$app->addBodyParsingMiddleware();

$routes = require __DIR__ . '/../routes/routes.php';
$routes($app);

$app->run();