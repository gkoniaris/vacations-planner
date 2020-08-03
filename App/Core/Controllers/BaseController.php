<?php

namespace App\Core\Controllers;

abstract class BaseController{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}