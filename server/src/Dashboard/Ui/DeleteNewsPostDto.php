<?php

namespace App\Dashboard\Ui;

class DeleteNewsPostDto
{
    public function __construct(
        public string $id,
    ) {
    }
}
