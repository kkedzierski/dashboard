<?php

namespace Unit\Account\Application;

use App\Account\Application\CreateUserService;
use App\Account\Application\Exception\UserCreateFailedException;
use App\Account\Application\Exception\UserExistException;
use App\Account\Domain\RoleEnum;
use App\Account\Domain\User;
use App\Account\Domain\UserFactory;
use App\Account\Domain\UserRepositoryInterface;
use App\Account\Ui\CreateUserDto;
use App\Kernel\Logger\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserServiceTest extends TestCase
{
    private MockObject&UserFactory $userFactory;
    private MockObject&UserRepositoryInterface $userRepository;
    private MockObject&LoggerInterface $logger;

    private CreateUserService $service;

    protected function setUp(): void
    {
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new CreateUserService(
            $this->userFactory,
            $this->userRepository,
            $this->logger
        );
    }

    public function testThrowExceptionWhenUserNotExist(): void
    {
        $createUserDto = new CreateUserDto('email', 'username', 'password', [RoleEnum::USER]);
        $this->expectException(UserExistException::class);
        $this->userRepository
            ->expects($this->once())
            ->method('getByEmail')
            ->with($createUserDto->email)
            ->willReturn([]);
        $this->userFactory
            ->expects($this->never())
            ->method('create');

        $this->service->createUser($createUserDto);
    }

    public function testThrowAndLogExceptionWhenCreateUserFails(): void
    {
        $createUserDto = new CreateUserDto('email', 'username', 'password', [RoleEnum::USER]);
        $user = new User('email', 'username', 'password', [RoleEnum::USER]);
        $this->expectException(UserCreateFailedException::class);
        $this->userRepository
            ->expects($this->once())
            ->method('getByEmail')
            ->with($createUserDto->email)
            ->willReturn(['user']);
        $this->userFactory
            ->expects($this->once())
            ->method('create')
            ->with($createUserDto)
            ->willReturn($user);
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($user)
            ->willThrowException($exception = new \Exception());
        $this->logger
            ->expects($this->once())
            ->method('logException')
            ->with(
                'Create user failed.',
                $exception,
                ['email' => $createUserDto->email, 'username' => $createUserDto->username]
            );

        $this->service->createUser($createUserDto);
    }
}