<?php

namespace App\Account\Application;

use App\Account\Application\Exception\UserCreateFailedException;
use App\Account\Application\Exception\UserExistException;
use App\Account\Domain\UserFactory;
use App\Account\Domain\UserRepositoryInterface;
use App\Account\Ui\CreateUserDto;
use App\Kernel\Logger\LoggerInterface;

class CreateUserService
{
    public function __construct(
        private readonly UserFactory $userFactory,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @throws UserExistException
     */
    public function createUser(
        CreateUserDto $createUserDto
    ): void {
        if ($this->userRepository->getByEmail($createUserDto->email)) {
            throw new UserExistException();
        }

        try {
            $user = $this->userFactory->create($createUserDto);

            $this->userRepository->save($user);
        } catch (\Throwable $exception) {
            $this->logger->logException(
                'Create user failed.',
                $exception,
                ['email' => $createUserDto->email, 'username' => $createUserDto->username]
            );

            throw new UserCreateFailedException();
        }
    }
}