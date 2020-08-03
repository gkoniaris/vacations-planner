<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Services\UserService;
use App\Core\Singletons\Request;
use App\Core\Singletons\Response;
use App\Validators\LoginValidator;
use App\Validators\RegisterValidator;

class AuthController extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->user = new UserService();
    }

    public function login()
    {
        $data = Request::data();
        
        $validated = LoginValidator::validate($data);

        if ($validated !== true) {
            return Response::json(['message' => $validated], 400);
        }

        $user = $this->user->login($data);

        if (!$user) {
            $responseData = ['message' => 'Please enter valid credentials'];

            return Response::json($responseData, 400);
        }

        $responseData = ['message' => 'You have been successfully logged in', 'user' => $user];
            
        return Response::json($responseData);
    }

    public function register()
    {
        $data = Request::data();

        $validated = RegisterValidator::validate($data);

        if ($validated !== true) {
            return Response::json(['message' => $validated], 400);
        }

        $user = UserService::register($data->user, $data->company);

        if (!$user) {
            $responseData = ['message' => 'User not created'];

            return Response::json($responseData, 400);
        }

        $responseData = ['message' => 'You have been successfully logged in', 'user' => $user];
            
        return Response::json($responseData);
    }
}
