<?php

namespace App\Controllers;

use App\Singletons\Request;
use App\Singletons\Response;
use App\Classes\Application;
use App\FunctionalException;
use App\Validators\ApplicationCreateValidator;
use App\Validators\ApplicationProcessValidator;

class ApplicationController extends BaseController
{
    protected $application;
    protected $request;

    public function __construct()
    {
        $this->application = new Application();
        $this->request = Request::getInstance();
    }

    public function index()
    {
        $applications = $this->application->all();

        return Response::json($applications);
    }

    public function create()
    {
        $data = Request::data();

        $validated = ApplicationCreateValidator::validate($data);

        if ($validated !== true) return Response::json(['message' => $validated], 400);

        $user = $this->request->user();

        try {
            $application = $this->application->create($data, $user);
            
            return Response::json($application);
        } catch(FunctionalException $e) {
            return Response::json(['message' => $e->getMessage()], 400);
        } catch(\Exception $e) {;
            return Response::json(['message' => 'Something went wrong'], 500);
        }
    }

    public function process()
    {
        global $settings;
        
        $data = $this->request->query();

        $validated = ApplicationProcessValidator::validate($data);

        if ($validated !== true) return Response::redirect($settings['FRONTEND_URL'] . '/?error=true&message=Invalid email link');

        try {
            $this->application->process($data->hash);
    
            return Response::redirect($settings['FRONTEND_URL'] . '/?message=The application was successfully processed');
        } catch(FunctionalException $e) {
            return Response::redirect($settings['FRONTEND_URL'] . '/?error=true&message=The application has been already processed');
        } catch(\Exception $e) {;
            return Response::redirect($settings['FRONTEND_URL'] . '/?error=true&message=Something went wrong, please try again later');
        }
    }
}