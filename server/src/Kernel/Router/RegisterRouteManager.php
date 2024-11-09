<?php

namespace App\Kernel\Router;

use App\Account\Ui\AuthController;
use App\Account\Ui\LoginController;

class RegisterRouteManager
{
    public function __construct(
        public readonly Router $router,
    ) {
    }

    public function registerRoutes(): void
    {
        $this->router->registerRoute('POST', '/login', LoginController::class, 'login');
        $this->router->registerRoute('GET', '/auth', AuthController::class, 'checkAuth');
    }

    public function handleRequests(): void
    {
        $this->router->handleRequest();
    }
}