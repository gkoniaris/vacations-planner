<?php

namespace App\Controllers;

use App\Singletons\Response;
use App\Models\Industry as IndustryModel;

class IndustryController extends BaseController
{
    protected $industry;

    public function __construct()
    {
        $this->industry = new IndustryModel();
    }

    public function index()
    {
        $industries = $this->industry->all();

        Response::json($industries);
    }
}