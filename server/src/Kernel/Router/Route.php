<?php

namespace App\Kernel\Router;

class Route
{
    public function __construct(
        public string $path,
        public string $method,
        public string $controller,
        public string $controllerMethod
    ) {
    }

}