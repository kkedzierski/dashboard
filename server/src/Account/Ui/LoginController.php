<?php

namespace App\Account\Ui;

use App\Account\Domain\User;
use App\Account\Domain\UserRepositoryInterface;
use App\Kernel\HashPassword\HashPasswordManagerInterface;
use App\Kernel\Serializer\SerializerInterface;

class LoginController
{
    public function __construct(
        private readonly HashPasswordManagerInterface $hashPasswordManager,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function login(LoginUserDto $loginUserDto): bool
    {
        $user = $this->userRepository->getByEmail($loginUserDto->username);

        if (empty($user)) {
            return false;
        }
//
//        $user = $this->serializer->denormalize($user, User::class);


        $this->hashPasswordManager->isPasswordValid($loginUserDto->password, $user['password']);
    }
}