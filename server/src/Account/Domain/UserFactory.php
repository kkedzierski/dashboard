<?php

namespace App\Account\Domain;

use App\Account\Ui\CreateUserDto;
use App\Kernel\HashPassword\HashPasswordManagerInterface;

class UserFactory
{
    public function __construct(
        private readonly HashPasswordManagerInterface $passwordManager,
    ) {
    }

    public function create(CreateUserDto $createUserDto): User
    {
        return new User(
            $createUserDto->email,
            $createUserDto->username,
            $this->passwordManager->hashPassword($createUserDto->password),
            $createUserDto->roles,
        );
    }
}
