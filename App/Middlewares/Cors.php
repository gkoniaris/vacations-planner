<?php
namespace App\Middlewares;

use App\Core\Middlewares\BaseMiddleware;
use App\Core\Singletons\Request;

/**
 * Middleware that enables cross origin requests
 */
class Cors extends BaseMiddleware{

    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct();
    }

    public function handle()
    {
        global $settings;
        
        header('Access-Control-Allow-Origin: ' . $settings['FRONTEND_URL']);
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Accept, Content-Type, Referer, User-Agent, X-CSRF-TOKEN, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
    }
}