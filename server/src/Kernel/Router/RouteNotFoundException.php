<?php

namespace App\Kernel\Router;

class RouteNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message ?: 'Not found', 404, $previous);
    }
}