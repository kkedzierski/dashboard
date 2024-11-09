<?php

namespace App\Kernel\Router;

use App\Kernel\JsonResponse\JsonResponse;

class DtoFactory
{
    /**
     * @throws \ReflectionException
     */
    public function create(string $dto, array $data): object
    {
        $reflection = new \ReflectionClass($dto);
        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $key => $value) {
            $property = $reflection->getProperty($key);
            $property->setValue($instance, $value);
        }

        return $instance;
    }
}