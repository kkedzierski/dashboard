<?php

namespace App\Dashboard\Domain;

interface NewsPostRepositoryInterface
{
    public function getById(string $id): array;

    public function save(NewsPost $newsPost): void;

    public function update(NewsPost $newsPost): void;

    public function delete(string $id): void;

    public function getAll(): array;
}