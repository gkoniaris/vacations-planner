<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Core\Singletons\Response;
use App\Services\IndustryService;

class IndustryController extends BaseController
{

    public function __construct(IndustryService $industry)
    {
        $this->industry = $industry;
    }

    public function index()
    {
        $industries = $this->industry->get();

        Response::json($industries);
    }
}
