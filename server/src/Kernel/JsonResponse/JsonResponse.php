<?php

namespace App\Kernel\JsonResponse;

class JsonResponse
{
    public static function send(array $data, ?int $statusCode = null): void
    {
        if (null !== $statusCode) {

            http_response_code($statusCode);
            $data = array_merge($data, ['status' => $statusCode]);
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}