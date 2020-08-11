<?php
namespace App\Middlewares;

use App\Core\Middlewares\BaseMiddleware;
use App\Core\Singletons\Request;

class IsEmployee extends BaseMiddleware
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct();
    }


    public function handle()
    {
        global $settings;

        $user = $this->request->user();

        if ($user['role'] !== 'employee') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->request->abort(new \Exception('Unauthorized'), 403);
            }

            return Response::redirect($settings['FRONTEND_URL'] . '/?error=true&message=You dont have permissions for this action');
        }
        
        $this->next();
    }
}
