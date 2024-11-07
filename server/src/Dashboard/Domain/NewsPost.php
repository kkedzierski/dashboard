<?php

declare(strict_types=1);

namespace App\Dashboard\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class NewsPost
{
    private ?UuidInterface $id;

    private string $title;

    private string $content;

    private \DateTimeImmutable $createdAt;

    public function __construct(
        string $title,
        string $content
    ) {
        $this->id = Uuid::uuid4();
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = new \DateTimeImmutable();
    }
}