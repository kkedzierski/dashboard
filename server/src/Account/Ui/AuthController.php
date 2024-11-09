<?php

namespace App\Account\Ui;

use App\Account\Application\Token\TokenMangerInterface;
use App\Kernel\JsonResponse\JsonResponse;

class AuthController
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
            JsonResponse::send(['status' => 'unauthenticated'], 401);
            return;
        }

        if ($this->tokenManager->validateToken($jwt)) {
            JsonResponse::send(['status' => 'authenticated'], 200);
        } else {
            JsonResponse::send(['status' => 'unauthenticated'], 401);
        }
    }
}