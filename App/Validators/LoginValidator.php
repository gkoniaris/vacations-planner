<?php

namespace App\Validators;

use App\Core\Validators\BaseValidator;
use App\Classes\Helpers;

class LoginValidator extends BaseValidator
{
    public static function validate($data)
    {
        if (!$data) {
            return 'Please provide valid data';
        }
        if (!isset($data->email) || !strlen($data->email) || !filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            return 'Please provide a valid email';
        }
        if (!isset($data->password) || !strlen($data->password)) {
            return 'Please provide a valid password';
        }
        
        return true;
    }
}
