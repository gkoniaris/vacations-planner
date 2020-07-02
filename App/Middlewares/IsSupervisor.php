<?php
namespace App\Middlewares;
use App\Singletons\Request;
use App\Singletons\Response;

class IsSupervisor extends BaseMiddleware{

    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct();
    }

    protected function handle()
    {
        global $settings;

        $user = $this->request->user();

        if ($user['role'] !== 'supervisor') {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->request->terminateRequestWithException(new \Exception('Unauthorized'), 403);
            }

            return Response::redirect($settings['FRONTEND_URL'] . '/?error=true&message=You dont have permissions for this action');
        }
    }
}