<?php

namespace Idynsys\BillingSdk\Exceptions;

use Exception;
use Throwable;

class AuthException extends Exception
{
    private array $originalError;

    public function __construct($errorData, $code = 0, Throwable $previous = null)
    {
        $this->originalError = $errorData;
        parent::__construct($this->getErrorMessage(), $code, $previous);
    }

    private function getErrorMessage(): string
    {
        return json_encode($this->originalError);
    }

    public function getError(): array
    {
        return $this->originalError;
    }

    public function getErrorStatus(): int
    {
        return $this->getCode();
    }
}