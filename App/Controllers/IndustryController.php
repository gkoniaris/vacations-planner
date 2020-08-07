<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Core\Singletons\Response;
use App\Services\IndustryService;

class IndustryController extends BaseController
{

    public function index(IndustryService $industry)
    {
        $industries = $industry->get();

        Response::json($industries);
    }
}
