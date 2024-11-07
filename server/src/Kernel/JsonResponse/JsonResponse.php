<?php

namespace App\Kernel\JsonResponse;

class JsonResponse
{
    public static function send(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}