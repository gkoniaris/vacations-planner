<?php

namespace App\Commands;

use App\Services\UserService;
use App\Services\CompanyService;
use App\Classes\Helpers;

class RegisterUserCommand
{
    protected $user;
    protected $company;
    protected $data;

    public function __construct(UserService $user, CompanyService $company)
    {
        $this->user = $user;
        $this->company = $company;
        $this->data = new \StdClass;
    }

    public function setUser($data)
    {
        $this->data->user = $data;
    }

    public function setCompany($data)
    {
        $this->data->company = $data;
    }

    public function execute()
    {
        $salt = Helpers::randomString();
        $hashedPassword = hash('sha256', $salt . '.' . $this->data->user->password);

        try {
            Database::beginTransaction();
    
            $company = $this->company->create($this->data->company);
            
            $this->data->user->company_id = $company->id;
            $this->data->user->role = 'supervisor';
    
            $user = $this->user->create($this->data->user);
            
            Database::commit();
        } catch(\Exception $e) {
            Database::rollback();

            throw $e;
        }

        return $user;
    }
}