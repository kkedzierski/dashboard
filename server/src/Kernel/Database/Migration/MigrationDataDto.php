<?php

namespace App\Kernel\Database\Migration;

class MigrationDataDto
{
    public MigrationTypeEnum $type;

    public string $name;

    public string $description;

    /**
     * @var string[]
     */
    public array $sql;

    public function __construct(MigrationTypeEnum $type, string $name, string $description, array $sql)
    {
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->sql = $sql;
    }
}