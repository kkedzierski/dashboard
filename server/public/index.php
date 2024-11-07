<?php

use App\Kernel\Container\Container;
use App\Kernel\Container\ContainerServicesManager;
use App\Kernel\Router\RegisterRouteManager;
use App\Kernel\Router\Router;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/global.php';

$dotenv = Dotenv::createImmutable('/var/www/server/');
$dotenv->load();

$container = new Container();
$containerServicesManager = new ContainerServicesManager($container);
$containerServicesManager->registerServices();

$registerRouteManager = new RegisterRouteManager(
    $container->get(Router::class),
);

$registerRouteManager->registerRoutes();
$registerRouteManager->handleRequests();
