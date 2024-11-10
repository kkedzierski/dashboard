<?php

namespace App\Dashboard\Ui;

class NewsPostDto
{
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $content = null,
    ) {
    }
}