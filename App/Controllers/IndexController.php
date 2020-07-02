<?php

namespace App\Controllers;

use App\Singletons\Response;

class IndexController extends BaseController
{

    public function index()
    {
        Response::json(['status' => 'ok']);
    }
}