<?php

declare(strict_types=1);

namespace App\Account\Domain;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): array;

    public function getByUsername(string $username): array;

    public function save(User $user): void;
}