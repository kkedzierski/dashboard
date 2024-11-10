<?php

namespace App\Kernel\Serializer;

use App\Kernel\JsonResponse\JsonResponse;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Serializer implements SerializerInterface
{
    public function denormalize(array $data, string $className): object
    {
        $reflectionClass = new \ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();

        $args = [];

        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $paramName = $param->getName();
                if (array_key_exists($paramName, $data)) {
                    $value = $data[$paramName];

                    if (is_string($value) && $this->isJson($value)) {
                        $value = json_decode($value, true);
                    }

                    if (is_string($value) && Uuid::isValid($value)) {
                        JsonResponse::send([$value]);
                        $value = Uuid::fromString($value);
                    }

                    $args[] = $value;
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    $args[] = null;
                }
            }
        }

        $object = $reflectionClass->newInstanceArgs($args);

        foreach ($data as $key => $value) {
            if ($reflectionClass->hasProperty($key)) {
                $property = $reflectionClass->getProperty($key);
                $property->setAccessible(true);

                if (is_string($value) && $this->isJson($value)) {
                    $value = json_decode($value, true);
                }

                if ($property->getType() && $property->getType()->getName() === UuidInterface::class && is_string($value)) {
                    $value = Uuid::fromString($value);
                }

                $property->setValue($object, $value);
            }
        }

        return $object;
    }

    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}