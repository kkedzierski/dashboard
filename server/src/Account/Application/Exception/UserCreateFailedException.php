<?php

namespace App\Account\Application\Exception;

class UserCreateFailedException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'User creation failed.';
}