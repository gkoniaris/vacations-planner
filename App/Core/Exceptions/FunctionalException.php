<?php

namespace App\Core\Exceptions;

/**
 * A custom exception to return for predictable errors
 */
class FunctionalException extends \Exception
{
    protected $message;
    protected $code;
    protected $previous;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
