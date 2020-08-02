<?php
namespace App\Services;

use App\Models\Industry as IndustryModel;

class IndustryService {

    public function __construct()
    {
        $this->industry = new IndustryModel();
    }

    public function get()
    {
        return $this->industry->all();
    }

}