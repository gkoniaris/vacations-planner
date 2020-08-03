<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Core\Singletons\Response;
use App\Models\Industry as IndustryModel;

class IndustryController extends BaseController
{
    protected $industry;

    public function __construct(IndustryModel $industry)
    {
        $this->industry = $industry;
    }

    public function index()
    {
        $industries = $this->industry->all();

        Response::json($industries);
    }
}