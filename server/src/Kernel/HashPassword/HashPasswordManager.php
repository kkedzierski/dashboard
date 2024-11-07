<?php

namespace App\Kernel\HashPassword;

class HashPasswordManager implements HashPasswordManagerInterface
{
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    public function isPasswordValid(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
