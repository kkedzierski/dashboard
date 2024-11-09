<?php

namespace App\Kernel\Clock;

class Clock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }

    public function dateTime(string $datetime): \DateTimeImmutable
    {
        return $this->now()->modify($datetime);
    }
}
