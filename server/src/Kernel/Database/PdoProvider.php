<?php

namespace App\Kernel\Database;

use App\Kernel\Logger\LoggerInterface;
use PDO;

readonly class PdoProvider implements PdoProviderInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private ?string         $dsn = null,
        private ?string         $user = null,
        private ?string         $password = null,
    ) {
        if (in_array(null, [$dsn, $user, $password], true)) {
            throw new \InvalidArgumentException('DSN, user and password must be provided');
        }
    }

    public function getPdo(): PDO
    {
        try {
            $pdo = new PDO($this->dsn, $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (\Throwable $e) {
            $this->logger
                ->logException('Could not connect to the database.', $e);

            throw new \RuntimeException('Could not connect to the database');
        }
    }
}
