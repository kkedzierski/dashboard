<?php

namespace App\Kernel\Logger;

use App\Kernel\Clock\ClockInterface;

class Logger implements LoggerInterface
{
    public function __construct(
        private readonly ClockInterface $clock,
        private ?string $logDir,
        private ?string $env,
    ) {
    }

    private function log(LogLevel $level, string $message, array $context = []): void
    {
        if ($this->logDir === null || $this->env === null) {
            throw new \RuntimeException('Log directory and environment must be provided.');
        }

        $now = $this->clock->now();

        $logFile = sprintf('%s/%s-%s.log', $this->logDir, $this->env, $now->format('Y-m-d'));

        $formattedMessage = sprintf(
            '[%s] %s: %s %s',
            $now->format('Y-m-d H:i:s'),
            $level->value,
            $message,
            json_encode($context)
        );

        file_put_contents($logFile, sprintf('%s%s', $formattedMessage, PHP_EOL), FILE_APPEND);
    }

    public function logException(string $message, \Throwable $exception, array $context = []): void
    {
        $message = sprintf('%s: %s', $message, $exception->getMessage());

        $this->log(LogLevel::ERROR, $message, $context);
    }
}
