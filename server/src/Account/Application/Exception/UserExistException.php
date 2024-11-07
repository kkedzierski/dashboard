<?php

namespace App\Account\Application\Exception;

class UserExistException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'User already exists.';
}
