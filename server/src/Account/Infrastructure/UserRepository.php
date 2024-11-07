<?php

declare(strict_types=1);

namespace App\Account\Infrastructure;

use App\Account\Domain\User;
use App\Account\Domain\UserRepositoryInterface;
use App\Kernel\Database\QueryBuilder;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly QueryBuilder $queryBuilder
    ) {
    }

    public function getByEmail(string $email): array
    {
        return $this->queryBuilder->createQueryBuilder()
            ->select()
            ->from('user')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->getResult();
    }

    public function save(User $user): void
    {
        $this->queryBuilder->createQueryBuilder()
            ->insert(
                'user',
                ['id', 'email', 'username', 'password', 'roles'],
                [':id', ':email', ':username', ':password', ':roles']
            )->setParameters([
                'id' => $user->getId()->toString(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'roles' => json_encode($user->getRoles())
            ])
            ->execute();
    }
}