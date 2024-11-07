<?php

namespace App\Account\Ui;

use App\Account\Domain\RoleEnum;

class CreateUserDto
{
    /**
     * @param RoleEnum[] $roles
     */
    public function __construct(
        public string $email,
        public string $username,
        public string $password,
        public array $roles
    ) {
    }
}