<?php

declare(strict_types=1);

namespace App\Account\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User
{
    private ?UuidInterface $id;

    /**
     * @var RoleEnum[]
     */
    private array $roles;

    private string $email;

    private string $username;

    private ?string $lastname = null;

    private string $password;

    /**
     * @param RoleEnum[] $roles
     */
    public function __construct(
        string $email,
        string $username,
        string $password,
        array $roles = []
    ) {
        $this->id = Uuid::uuid4();
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function makeAdmin(): self
    {
        $this->roles[] = RoleEnum::ADMIN;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
