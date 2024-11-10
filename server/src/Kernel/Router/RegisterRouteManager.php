<?php

namespace App\Kernel\Router;

use App\Account\Ui\LoginController;
use App\Dashboard\Ui\NewsPostController;
use App\Kernel\Authorization\AuthManager;

class RegisterRouteManager
{
    public function __construct(
        public readonly Router $router,
    ) {
    }

    public function registerRoutes(): void
    {
        $this->router->registerRoute('POST', '/login', LoginController::class, 'login');
        $this->router->registerRoute('GET', '/auth', AuthManager::class, 'checkAuth');
        $this->router->registerRoute('GET', '/news-posts', NewsPostController::class, 'getNewsPosts');
        $this->router->registerRoute('POST', '/news-posts', NewsPostController::class, 'createNewsPost');
        $this->router->registerRoute('PATCH', '/news-posts', NewsPostController::class, 'updateNewsPost');
        $this->router->registerRoute('DELETE', '/news-posts', NewsPostController::class, 'deleteNewsPost');
    }

    public function handleRequests(): void
    {
        $this->router->handleRequest();
    }
}