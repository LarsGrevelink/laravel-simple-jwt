<?php

namespace LGrevelink\LaravelSimpleJWT\Exceptions;

use Exception;

abstract class LaravelSimpleJwtException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $message
     * @param Exception $previous
     * @param int $code
     */
    public function __construct($message = null, Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
