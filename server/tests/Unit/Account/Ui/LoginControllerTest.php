<?php

namespace Unit\Account\Ui;

use App\Account\Application\Token\TokenMangerInterface;
use App\Account\Domain\RoleEnum;
use App\Account\Domain\User;
use App\Account\Domain\UserRepositoryInterface;
use App\Account\Ui\LoginController;
use App\Account\Ui\LoginUserDto;
use App\Kernel\HashPassword\HashPasswordManagerInterface;
use App\Kernel\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    private MockObject&HashPasswordManagerInterface $hashPasswordManager;
    private MockObject&UserRepositoryInterface $userRepository;
    private MockObject&SerializerInterface $serializer;
    private MockObject&TokenMangerInterface $tokenManger;

    private LoginController $controller;

    protected function setUp(): void
    {
        $this->hashPasswordManager = $this->createMock(HashPasswordManagerInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->tokenManger = $this->createMock(TokenMangerInterface::class);

        $this->controller = new LoginController(
            $this->hashPasswordManager,
            $this->userRepository,
            $this->serializer,
            $this->tokenManger
        );
    }

    public function testNoLoginWhenUserNotFound(): void
    {
        $loginUserDto = new LoginUserDto('username', 'password');
        $this->userRepository
            ->expects($this->once())
            ->method('getByUsername')
            ->with($loginUserDto->username)
            ->willReturn([]);
        $this->serializer
            ->expects($this->never())
            ->method('denormalize');

        $this->controller->login($loginUserDto);
    }

    public function testsNoLoginWhenPasswordIsInvalid(): void
    {
        $loginUserDto = new LoginUserDto('username', 'password');
        $user = new User('email', 'username', 'password', [RoleEnum::USER]);
        $this->userRepository
            ->expects($this->once())
            ->method('getByUsername')
            ->with($loginUserDto->username)
            ->willReturn([[]]);
        $this->serializer
            ->expects($this->once())
            ->method('denormalize')
            ->with([], User::class)
            ->willReturn($user);
        $this->hashPasswordManager
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($loginUserDto->password, $user->getPassword())
            ->willReturn(false);
        $this->tokenManger
            ->expects($this->never())
            ->method('generateToken');

        $this->controller->login($loginUserDto);
    }

    public function testLoginWhenPasswordValid(): void
    {
        $loginUserDto = new LoginUserDto('username', 'password');
        $user = new User('email', 'username', 'password', [RoleEnum::USER]);
        $this->userRepository
            ->expects($this->once())
            ->method('getByUsername')
            ->with($loginUserDto->username)
            ->willReturn([[]]);
        $this->serializer
            ->expects($this->once())
            ->method('denormalize')
            ->with([], User::class)
            ->willReturn($user);
        $this->hashPasswordManager
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($loginUserDto->password, $user->getPassword())
            ->willReturn(true);
        $this->tokenManger
            ->expects($this->once())
            ->method('generateToken')
            ->with([
                'id' => $user->getId()->toString(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]);

        $this->controller->login($loginUserDto);
    }
}