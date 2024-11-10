<?php

namespace App\Kernel\Authorization;

interface AuthManagerInterface
{
    public function checkAuth(): void;

    public function isAuthorized(): bool;
}
