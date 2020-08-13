<?php

namespace App\Services;
use App\Models\User;
use App\Core\Singletons\Database;

class AuthService
{

    public function __construct(User $user, Database $database)
    {
        $this->user = $user;
        $this->database = $database;
    }

    /**
     * Creates a cookie session
     *
     * @param $userId
     */
    private function createSession($userId)
    {
        session_start([
            'cookie_lifetime' => 86400,
        ]);
     
        $uniqueId = uniqid('', true); // Add true for more entropy
        $_SESSION['unique_id'] = $uniqueId;
        
        $stmt = $this->database->insert("INSERT INTO sessions(user_id, session_id, expires_at) 
            VALUES (?, ?, CURRENT_TIMESTAMP + INTERVAL 1 DAY)", [
                $userId,
                $uniqueId
            ]);
    }

    /**
     * Performs login and creates a session for the user
     *
     * @param $data
     */
    public function login($email, $password)
    {
        $user = $this->user->withHidden()->findBy('email', $email);

        if (!$user) return false;

        if (!password_verify($user->salt . '.' . $password, $user->password)) return false;
        
        unset($user->password);
        unset($user->salt);

        if ($user) {
            $this->createSession($user->id);
            
            return $user;
        }

        return false;
    }
}
