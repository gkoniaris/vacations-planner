<?php

namespace App\Validators;

use App\Classes\Helpers;

class ApplicationCreateValidator extends BaseValidator {

    public static function validate($data)
    {
        if (!$data) return 'Please provide valid data';
        if (!isset($data->from) || !strlen($data->from) || !Helpers::validateDate($data->from, 'Y-m-d')) return 'Please provide a valid vacation start date';
        if (!isset($data->to) || !strlen($data->to) || !Helpers::validateDate($data->to, 'Y-m-d')) return 'Please provide a valid vacation end date';
        if (!isset($data->reason) || !strlen($data->reason)) return 'Please provide a valid vacation reason';

        if (strtotime($data->from) > strtotime($data->to)) return 'Please provide valid dates';
        if (strtotime($data->from) < strtotime('now')) return 'Please provide a valid vacation start date';
        if (strtotime($data->to) < strtotime('now')) return 'Please provide a valid vacation end date';

        return true;
    }
}
