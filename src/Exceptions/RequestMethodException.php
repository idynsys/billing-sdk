<?php

namespace Idynsys\BillingSdk\Exceptions;

use Exception;

class RequestMethodException extends Exception
{
    public function __construct($previous = null)
    {
        parent::__construct('Undefined method to send request.', 422, $previous);
    }
}