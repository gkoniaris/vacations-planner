<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Core\Singletons\Response;

class IndexController extends BaseController
{
    public function index()
    {
        Response::json(['status' => 'ok']);
    }
}
