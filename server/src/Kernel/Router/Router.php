<?php

namespace App\Kernel\Router;

use App\Kernel\JsonResponse\JsonResponse;
use App\Kernel\Logger\LoggerInterface;
use Psr\Container\ContainerInterface;

class Router
{
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ContainerInterface $container,
        private readonly DtoFactory $dtoFactory,
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

        $body = [];
        if (in_array($method, [self::METHOD_PUT, self::METHOD_PATCH, self::METHOD_POST], true)) {
            $body = $this->provideBody();
        }

        try {
            foreach ($this->routes as $route) {
                if ($route->method === $method && $route->path === $path) {
                    $controller = $this->container->get($route->controller);

                    $reflectionMethod = new \ReflectionMethod($controller, $route->controllerMethod);
                    $parameters = $reflectionMethod->getParameters();
                    // Tworzenie argumentów do wywołania metody kontrolera
                    $args = [];
                    foreach ($parameters as $param) {
                        $paramType = $param->getType();
                        $paramClassName = $paramType instanceof \ReflectionNamedType ? $paramType->getName() : null;

                        if ($paramClassName && class_exists($paramClassName)) {
                            // Tworzenie DTO, jeśli typ parametru istnieje jako klasa
                            $args[] = $this->dtoFactory->create($paramClassName, $body);
                        } else {
                            // Możesz zdecydować, co zrobić, jeśli parametr nie jest DTO, np. błąd lub null
                            $args[] = null;
                        }
                    }

                    $controller->{$route->controllerMethod}(...$args);
                    return;
                }
            }

            throw new RouteNotFoundException("Route not found for path: $path and method: $method");
        } catch (RouteNotFoundException $exception) {
            JsonResponse::send(['error' => $exception->getMessage()], 404);
        } catch (\Throwable $exception) {
            JsonResponse::send([$exception->getMessage()], 500);
            $this->logger->logException('Cannot handle request', $exception, ['path' => $path, 'method' => $method]);
            JsonResponse::send(['error' => 'Internal server error'], 500);
        }
    }

    private function provideDto(): object
    {
        return $this->dtoFactory->create($dto, $data);
    }

    private function provideBody(): array
    {
        $rawInput = file_get_contents("php://input");
        return json_decode($rawInput, true) ?? [];
    }
}
