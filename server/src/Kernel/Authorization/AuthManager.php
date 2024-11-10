<?php

namespace App\Kernel\Authorization;

use App\Account\Application\Token\TokenMangerInterface;
use App\Kernel\JsonResponse\JsonResponse;

class AuthManager implements AuthManagerInterface
{
    public function __construct(
        private readonly TokenMangerInterface $tokenManager
    ) {
    }

    public function checkAuth(): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if (!$jwt) {
            JsonResponse::send(['message' => 'unauthenticated'], 401);
            return;
        }

        if ($this->tokenManager->validateToken($jwt)) {
            JsonResponse::send(['message' => 'authenticated'], 200);
        } else {
            JsonResponse::send(['message' => 'unauthenticated'], 401);
        }
    }

    public function isAuthorized(): bool
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if (!$jwt) {
            return false;
        }

        return $this->tokenManager->validateToken($jwt);
    }
}
