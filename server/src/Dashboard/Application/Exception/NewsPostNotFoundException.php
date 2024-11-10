<?php

namespace App\Dashboard\Application\Exception;

class NewsPostNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'News post not found.';
}
