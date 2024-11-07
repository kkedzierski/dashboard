<?php

namespace App\Kernel\Database\Migration;

enum MigrationTypeEnum: string
{
    case UP = 'up';
    case DOWN = 'down';
}
