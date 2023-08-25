<?php

namespace Cacing69\Cquery\Exception;

use Exception;
use Throwable;

class CqueryException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}