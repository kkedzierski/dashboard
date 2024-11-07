<?php

namespace App\Account\Ui;

class LoginUserDto
{
    public function __construct(
        public string $username,
        public string $password
    ) {
    }
}
