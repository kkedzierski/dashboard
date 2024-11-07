<?php

namespace App\Kernel\Database\Migration;

use App\Kernel\Database\PdoProviderInterface;
use PDO;

class MigrationManager
{
    public function __construct(
        private readonly PdoProviderInterface $pdoProvider,
        private ?string $migrationsPath = null,
    ) {
        $this->migrationsPath = sprintf('%s/../../../../migrations/*.php', __DIR__);
    }

    /**
     * @throws \Throwable
     */
    public function migrateUp(): void
    {
        $this->migrate(MigrationTypeEnum::UP);
    }

    /**
     * @throws \Throwable
     */
    public function migrateDown(): void
    {
        $this->migrate(MigrationTypeEnum::DOWN);
    }

    private function migrate(MigrationTypeEnum $migrationType): void
    {
        $pdo = $this->pdoProvider->getPdo();
        $pdo->exec('CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');

        $actualMigrations = $pdo->query('SELECT migration FROM migrations')->fetchAll(PDO::FETCH_COLUMN);

        $files = glob($this->migrationsPath);

        foreach ($files as $file) {
            require_once $file;
            $migrationData = $this->provideMigrationDataByFile($file, $migrationType);

            if ($migrationType === MigrationTypeEnum::UP
                && in_array($migrationData->name, $actualMigrations, true)) {
                echo sprintf("Migration %s already ran. skipping...\n", $migrationData->name);
                continue;
            }

                try {
                    $pdo->beginTransaction();
                    foreach ($migrationData->sql as $sql) {
                        $pdo->exec($sql);
                    }

                    if ($migrationData->type === MigrationTypeEnum::UP) {
                        $pdo->exec(sprintf('INSERT INTO migrations (migration, description) VALUES ("%s", "%s")', $migrationData->name, $migrationData->description));
                    }

                    if ($migrationData->type === MigrationTypeEnum::DOWN) {
                        $pdo->exec(sprintf('DELETE FROM migrations WHERE migration = "%s"', $migrationData->name));
                    }

                    if ($pdo->inTransaction()) {
                        $pdo->commit();
                    }
                    echo "Migration ran successfully.\n";
                } catch (\Throwable $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    echo "Migration failed.\n";
                    throw $e;
                }
        }
    }

    private function provideMigrationDataByFile(string $file, MigrationTypeEnum $type): MigrationDataDto
    {
        $migrationClassName = basename($file, '.php');

        $fullyQualifiedClassName = sprintf("App\\Migrations\\%s", $migrationClassName);

        if (!class_exists($fullyQualifiedClassName)) {
            throw new \RuntimeException(sprintf(("Class %s not found in file %s."), $fullyQualifiedClassName, $file));
        }

        $migration = new $fullyQualifiedClassName();

        if (!$migration instanceof AbstractMigration) {
            throw new \RuntimeException(sprintf(("Class %s must extend AbstractMigration."), $fullyQualifiedClassName));
        }

        return match ($type) {
            MigrationTypeEnum::UP => $migration->getMigrationUpData(),
            MigrationTypeEnum::DOWN => $migration->getMigrationDownData(),
        };
    }
}
