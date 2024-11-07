<?php

namespace App\Kernel\Logger;

enum LogLevel: int
{
    case INFO = 200;
    case ERROR = 500;
}