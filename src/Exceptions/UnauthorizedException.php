<?php

namespace Idynsys\BillingSdk\Exceptions;

use Throwable;

class UnauthorizedException extends RequestException
{
    public function __construct($code = 401, $previous = null)
    {
        parent::__construct(['error' => 'Unauthorized', 'error_description' => 'Incorrect token'], $code, $previous);
    }
}