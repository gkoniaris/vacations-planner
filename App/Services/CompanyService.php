<?php
namespace App\Services;

use App\Models\Company as CompanyModel;

class CompanyService
{
    public function __construct(CompanyModel $company)
    {
        $this->company = $company;
    }

    public function create($data)
    {
        $company = $this->company->create($data);
        
        return $company;
    }
}
