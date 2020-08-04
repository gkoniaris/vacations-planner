<?php
namespace App\Services;

use App\Models\Industry;

class IndustryService
{
    public function __construct(Industry $industry)
    {
        $this->industry = $industry;
    }

    public function get()
    {
        return $this->industry->all();
    }
}
