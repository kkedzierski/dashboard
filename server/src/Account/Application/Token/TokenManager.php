<?php

namespace App\Account\Application\Token;

use App\Kernel\Clock\ClockInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;

class TokenManager implements TokenMangerInterface
{
    private Configuration $config;

    public function __construct(
        private readonly ClockInterface $clock
    ) {
        if ($_ENV['JWT_SECRET'] === null || $_ENV['JWT_EXPIRATION'] === null) {
            throw new \RuntimeException('JWT_SECRET and JWT_EXPIRATION must be set in .env');
        }

        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($_ENV['JWT_SECRET'])
        );

        $this->config->setValidationConstraints(
            new IssuedBy('dashboard')
        );
    }

    public function generateToken(array $claims): string
    {
        $now = $this->clock->now();
        $builder = $this->config->builder()
            ->issuedBy('dashboard')
            ->issuedAt($now)
            ->expiresAt($now->modify($_ENV['JWT_EXPIRATION']));

        foreach ($claims as $key => $value) {
            $builder->withClaim($key, $value);
        }

        return $builder->getToken($this->config->signer(), $this->config->signingKey())->toString();
    }

    public function validateToken(string $token): bool
    {
        $token = $this->config->parser()->parse($token);
        assert($token instanceof UnencryptedToken);

        $constraints = $this->config->validationConstraints();

        return $this->config->validator()->validate($token, ...$constraints);
    }
}
