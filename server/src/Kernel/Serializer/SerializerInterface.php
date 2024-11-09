<?php

namespace App\Kernel\Serializer;

interface SerializerInterface
{
    public function denormalize(array $data, string $className): object;
}