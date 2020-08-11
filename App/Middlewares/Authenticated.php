<?php
namespace App\Middlewares;

use App\Core\Middlewares\BaseMiddleware;
use App\Core\Singletons\Request;
use App\Core\Singletons\Response;
use App\Core\Singletons\Database;

/**
 * Middleware that checks if a user has a valid active session
 */
class Authenticated extends BaseMiddleware
{
    protected $request;
    protected $db;
    
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function handle()
    {
        try {
            session_start();

            if (empty($_SESSION['unique_id'])) {
                return $this->request->abort('You are not allowed to perform this action', 401);
            }

            $session = Database::select('SELECT * FROM sessions WHERE session_id = ? AND expires_at > CURRENT_TIMESTAMP', [$_SESSION['unique_id']]);

            if (!$session) {
                return $this->request->abort('You are not allowed to perform this action', 401);
            }

            $user = Database::select('SELECT * FROM users WHERE id = ?', [$session['user_id']]);

            $this->request->setUser($user);

            return $this->next();
        } catch (\Exception $e) {
            return $this->next($e);
        }
    }
}
