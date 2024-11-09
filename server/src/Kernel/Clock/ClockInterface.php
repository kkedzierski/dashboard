<?php

namespace App\Kernel\Clock;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;

    public function dateTime(string $datetime): \DateTimeImmutable;
}
