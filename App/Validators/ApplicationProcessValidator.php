<?php

namespace App\Validators;

use App\Core\Validators\BaseValidator;
use App\Classes\Helpers;

class ApplicationProcessValidator extends BaseValidator {

    public static function validate($data)
    {
        if (!$data) return 'Please provide valid data';
        if (!isset($data->hash) || !strlen($data->hash)) return 'Please provide a valid hash';

        return true;
    }
}
