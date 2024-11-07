<?php

namespace App\Kernel\Logger;

interface LoggerInterface
{
    public function logException(string $message, \Throwable $exception, array $context = []): void;
}