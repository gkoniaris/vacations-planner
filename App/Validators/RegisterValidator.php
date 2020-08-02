<?php

namespace App\Validators;

class RegisterValidator extends BaseValidator {

    public static function validate($data)
    {
        echo(json_encode($data));
        if (!$data) return 'Please provide valid data';
        if (!$data->user || !is_object($data->user)) return 'Please provide valid data';
        if (!$data->company || !is_object($data->user)) return 'Please provide valid data';
        
        if (!isset($data->user->first_name) || !strlen($data->user->first_name)) return 'Please provide a valid first name';
        if (!isset($data->user->last_name) || !strlen($data->user->last_name)) return 'Please provide a valid last name';
        if (!isset($data->user->email) || !strlen($data->user->email) || !filter_var($data->user->email, FILTER_VALIDATE_EMAIL)) return 'Please provide a valid email';
        if (!isset($data->user->password) || !strlen($data->user->password)) return 'Please provide a valid password';

        if (!isset($data->company->name) || !strlen($data->company->name)) return 'Please provide a valid company name';
        if (!isset($data->company->industry_id) || $data->company->industry_id < 1) return 'Please provide a valid industry';
        if (!isset($data->company->country) || !strlen($data->company->country)) return 'Please provide a valid country';
        if (!isset($data->company->city) || !strlen($data->company->city)) return 'Please provide a valid city';
        if (!isset($data->company->vat) || !strlen($data->company->vat)) return 'Please provide a valid vat';
        if (!isset($data->company->total_employees) || !strlen($data->company->total_employees) || !in_array($data->company->total_employees, ['1 - 9', '10 - 50', '51 - 200', '201 - 1000', '1000+'])) return 'Please provide a valid total employees option';

        return true;
    }
}