<?php

namespace App\Commands\RegisterUser;

use App\Core\Commands\BaseCommand;
use App\Core\Singletons\Database;
use App\Services\UserService;
use App\Services\CompanyService;
use App\Classes\Helpers;

class RegisterUserCommand extends BaseCommand
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function execute(UserService $userService, CompanyService $companyService)
    {
        try {
            Database::beginTransaction();
    
            $company = $companyService->create($this->data->company);
            
            $this->data->user->company_id = $company->id;
            $this->data->user->role = 'supervisor';
    
            $user = $userService->create($this->data->user);
            
            Database::commit();
        } catch(\Exception $e) {
            Database::rollback();

            throw $e;
        }

        return $user;
    }
}