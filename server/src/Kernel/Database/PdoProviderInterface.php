<?php

namespace App\Kernel\Database;

interface PdoProviderInterface
{
    public function getPdo(): \PDO;
}
