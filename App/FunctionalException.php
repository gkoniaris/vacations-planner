<?php

namespace App;

/**
 * A custom exception to return for predictable errors
 */
class FunctionalException extends \Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}