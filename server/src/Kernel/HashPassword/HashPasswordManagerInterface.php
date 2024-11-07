<?php

namespace App\Kernel\HashPassword;

interface HashPasswordManagerInterface
{
    public function hashPassword(string $password): string;
    public function isPasswordValid(string $password, string $hash): bool;
}
