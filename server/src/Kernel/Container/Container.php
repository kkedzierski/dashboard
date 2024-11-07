<?php

namespace App\Kernel\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected array $services = [];

    /**
     * @throws \Exception
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new \Exception('Service not found');
        }

        return $this->services[$id]($this);
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function set(string $id, callable $resolver): void
    {
        $this->services[$id] = $resolver;
    }
}