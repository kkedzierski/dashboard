<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Kernel\Clock\Clock;
use App\Kernel\Database\Migration\MigrationManager;
use App\Kernel\Database\Migration\MigrationTypeEnum;
use App\Kernel\Database\PdoProvider;
use App\Kernel\Logger\Logger;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('/var/www/server/');
$dotenv->load();

$migrationManager = provideMigrationManager();

$direction = $argv[1] ?? null;

if (!$direction || !in_array($direction, [MigrationTypeEnum::UP->value, MigrationTypeEnum::DOWN->value], true)) {
    echo "Usage: php MakeMigrationCommand.php [up|down]\n";
    exit(1);
}

switch ($direction) {
    case MigrationTypeEnum::UP->value:
        echo "Migrating up...\n";
        $migrationManager->migrateUp();
        break;
    case MigrationTypeEnum::DOWN->value:
        echo "Migrating down will delete all data. Are you sure you want to proceed? (yes/no): ";
        $confirmation = strtolower(trim(readline()));

        if ($confirmation === 'yes') {
            echo "Migrating down...\n";
            $migrationManager->migrateDown();
        } else {
            echo "Migration down canceled.\n";
        }
        break;
}

function provideMigrationManager(): MigrationManager
{
    $clock = new Clock();
    $logger = new Logger($clock, $_ENV['LOG_DIR'], $_ENV['ENV']);
    $pdoProvider = new PdoProvider($logger, $_ENV['DATABASE_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

    return new MigrationManager($pdoProvider);
}
