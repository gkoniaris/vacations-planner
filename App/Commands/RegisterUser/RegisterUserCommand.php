<?php

namespace App\Commands\RegisterUser;

use App\Core\Commands\BaseCommand;
use App\Core\Singletons\Database;
use App\Services\UserService;
use App\Services\CompanyService;
use App\Classes\Helpers;

class RegisterUserCommand extends BaseCommand
{
    protected $database;
    protected $data;

    public function __construct(Database $database, $data)
    {
        $this->database = $database;
        $this->data = $data;
    }

    public function execute(UserService $userService, CompanyService $companyService)
    {
        try {
            $this->database->beginTransaction();
    
            $company = $companyService->create($this->data->company);
            
            $this->data->user->company_id = $company->id;
            $this->data->user->role = 'supervisor';
    
            $user = $userService->create($this->data->user);
            
            $this->database->commit();
        } catch(\Exception $e) {
            $this->database->rollback();

            throw $e;
        }

        return $user;
    }
}