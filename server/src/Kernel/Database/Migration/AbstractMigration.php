<?php

namespace App\Kernel\Database\Migration;

abstract class AbstractMigration
{
    private array $sql = [];

    protected function addSql(string $sql): void
    {
        $this->sql[] = $sql;
    }

    private function getSql(): array
    {
        return $this->sql;
    }

    public function getName(): string
    {
        return $this->provideName();
    }

    private function provideName(): string
    {
        $className = basename(static::class, '.php');

        return substr($className, strrpos($className, '\\') + 1);
    }

    public function getMigrationUpData(): MigrationDataDto
    {
        $this->up();
        $migrationDataDto = $this->getMigrationDataDto(MigrationTypeEnum::UP);
        $this->resetSql();

        return $migrationDataDto;
    }

    public function getMigrationDownData(): MigrationDataDto
    {
        $this->down();
        $migrationDataDto = $this->getMigrationDataDto(MigrationTypeEnum::DOWN);
        $this->resetSql();

        return $migrationDataDto;
    }

    private function getMigrationDataDto(MigrationTypeEnum $type): MigrationDataDto
    {
        return new MigrationDataDto(
            $type,
            $this->provideName(),
            $this->getDescription(),
            $this->getSql()
        );
    }

    private function resetSql(): void
    {
        $this->sql = [];
    }
}
