<?php

namespace App\Validators;

abstract class BaseValidator {

    public static function validate($data)
    {
        return false;
    }
}