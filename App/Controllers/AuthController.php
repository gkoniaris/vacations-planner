<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Services\UserService;
use App\Core\Singletons\Request;
use App\Core\Singletons\Response;
use App\Validators\LoginValidator;
use App\Validators\RegisterValidator;
use App\Core\Commands\CommandsInvoker;
use App\Commands\RegisterUser\RegisterUserCommand;
use App\Commands\RegisterUser\SendRegistrationEmailCommand;

class AuthController extends BaseController
{
    protected $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    public function login($data)
    {        
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

    public function register(CommandsInvoker $register, $data)
    {
        $validated = RegisterValidator::validate($data);

        if ($validated !== true) {
            return Response::json(['message' => $validated], 400);
        }

        $register->addCommand(new RegisterUserCommand($data))
                 ->addCommand(new SendRegistrationEmailCommand($data))
                 ->run();

        $responseData = ['message' => 'You have been successfully registered'];
            
        return Response::json($responseData);
    }
}
