<?php

namespace App\Account\Ui;

use App\Account\Application\Token\TokenMangerInterface;
use App\Account\Domain\User;
use App\Account\Domain\UserRepositoryInterface;
use App\Kernel\HashPassword\HashPasswordManagerInterface;
use App\Kernel\JsonResponse\JsonResponse;
use App\Kernel\Serializer\SerializerInterface;

class LoginController
{
    public function __construct(
        private readonly HashPasswordManagerInterface $hashPasswordManager,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly TokenMangerInterface $tokenManger,
    ) {
    }

    public function login(LoginUserDto $loginUserDto): void
    {
        $user = $this->userRepository->getByUsername($loginUserDto->username);

        if (empty($user)) {
            JsonResponse::send(['error' => 'Invalid credentials'], 401);
            return;
        }

        /** @var User $user */
        $user = $this->serializer->denormalize($user[0], User::class);

        if (!$this->hashPasswordManager->isPasswordValid($loginUserDto->password, $user->getPassword())) {
            JsonResponse::send(['error' => 'Invalid credentials'], 401);
            return;
        }

        $token = $this->tokenManger->generateToken([
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);

        JsonResponse::send(['token' => $token], 200);
    }
}
