<?php

namespace App\Controllers;

use App\Classes\User;
use App\Singletons\Request;
use App\Singletons\Response;
use App\Validators\LoginValidator;

class AuthController extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
    }

    public function login()
    {
        $data = Request::data();
        
        $validated = LoginValidator::validate($data);

        if ($validated !== true) return Response::json(['message' => $validated], 400);

        $user = $this->user->login($data);

        if (!$user) {
            $responseData = ['message' => 'Please enter valid credentials'];

            return Response::json($responseData, 400);
        }

        $responseData = ['message' => 'You have been successfully logged in', 'user' => $user];
            
        return Response::json($responseData);
    }
}