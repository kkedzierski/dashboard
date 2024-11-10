<?php

namespace App\Dashboard\Application\Exception;

class SaveNewsPostFailedException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Save news post failed.';
}