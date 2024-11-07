<?php

namespace App\Kernel\Clock;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}