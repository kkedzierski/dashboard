<?php

namespace App\Kernel\Router;

use App\Kernel\JsonResponse\JsonResponse;
use App\Kernel\Logger\LoggerInterface;

class Router
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @var Route[]
     */
    private array $routes = [];

    public function registerRoute(
        string $method,
        string $path,
        string $controller,
        string $controllerMethod
    ): void {
        $this->routes[] = new Route(sprintf('%s%s', $_ENV['API_PREFIX'], $path), $method, $controller, $controllerMethod);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        try {
            foreach ($this->routes as $route) {
                if ($route->method === $method && $route->path === $path) {
                    $controller = new $route->controller();
                    $controller->{$route->controllerMethod}();
                    return;
                }
            }

            throw new RouteNotFoundException("Route not found for path: $path and method: $method");
        } catch (RouteNotFoundException $exception) {
            JsonResponse::send(['error' => $exception->getMessage()], 404);
        } catch (\Throwable $exception) {
            $this->logger->logException('Cannot handle request', $exception, ['path' => $path, 'method' => $method]);
            JsonResponse::send(['error' => 'Internal server error'], 500);
        }
    }
}