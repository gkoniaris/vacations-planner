<?php

namespace App\Core\Validators;

abstract class BaseValidator
{
    public static function validate($data)
    {
        return false;
    }
}
