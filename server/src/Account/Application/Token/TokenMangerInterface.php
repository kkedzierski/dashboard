<?php

namespace App\Account\Application\Token;

interface TokenMangerInterface
{
    public function generateToken(array $claims): string;

    public function validateToken(string $token): bool;
}