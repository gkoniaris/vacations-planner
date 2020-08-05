<?php

namespace App\Controllers;

use App\Core\Controllers\BaseController;
use App\Core\Singletons\Request;
use App\Core\Singletons\Response;
use App\Core\Exceptions\FunctionalException;
use App\Validators\UserCreateValidator;
use App\Validators\UserUpdateValidator;
use App\Services\UserService;

class UserController extends BaseController
{
    protected $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->all();

        Response::json($users);
    }

    public function get()
    {
        $data = Request::query();

        if (isset($data->id)) {
            $user = $this->user->get($data->id);
            return Response::json($user);
        }

        $users = $this->user->all();

        Response::json($users);
    }

    public function create()
    {
        try {
            $data = Request::data();
            
            $validated = UserCreateValidator::validate($data);

            if ($validated !== true) {
                return Response::json(['message' => $validated], 400);
            }

            $user = $this->user->create($data);

            return Response::json($user);
        } catch (FunctionalException $e) {
            return Response::json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Something went wrong'], 500);
        }
    }

    public function update()
    {
        try {
            $data = Request::data();

            $validated = UserUpdateValidator::validate($data);

            if ($validated !== true) {
                return Response::json(['message' => $validated], 400);
            }

            $user = $this->user->update($data);

            return Response::json($user);
        } catch (FunctionalException $e) {
            return Response::json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Something went wrong'], 500);
        }
    }
}
