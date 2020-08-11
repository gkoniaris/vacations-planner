<?php
namespace App\Services;

use App\Core\Singletons\Database;
use App\Classes\Helpers;
use App\Core\Exceptions\FunctionalException;
use App\Models\User as UserModel;
use App\Models\Company as CompanyModel;

class UserService
{
    public function __construct(UserModel $user, Database $database)
    {
        $this->user = $user;
        $this->database = $database;
    }

    /**
     * Performs login and creates a session for the user
     *
     * @param $data
     */
    public function login($data)
    {
        $email = $data->email;
        $password = $data->password;
        
        $user = $this->findByEmailPassword($email, $password);
        
        if (!$user) return false;

        if (!password_verify($user->salt . '.' . $password, $user->password)) return false;
        
        unset($user->password);
        unset($user->salt);

        if ($user) {
            $this->saveSession($user->id);
            
            return $user;
        }

        return false;
    }

    /**
     * Returns all users
     */
    public function all()
    {
        $users = $this->user->all();

        return $users;
    }

    /**
     * Returns a specific user by it's id
     */
    public function get($id)
    {
        $user = $this->user->find($id);

        return $user;
    }

    /**
     * Creates a new user
     *
     * @param $data
     */
    public function create($data)
    {      
        $user = $this->database->select('SELECT id FROM users WHERE email = ?', [$data->email]);

        //Return if user already exists
        if ($user) {
            throw new FunctionalException('User with this email already exists');
        }

        $salt = Helpers::randomString(20);
        $hashedPassword = password_hash($salt . '.' . $data->password, PASSWORD_BCRYPT, ['cost' => 12]);

        $user = $this->user->fill([
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'email' => $data->email
        ]);

        $user->set('salt', $salt);
        $user->set('password', $hashedPassword);
        $user->set('role', $data->role);
        $user->set('company_id', $data->company_id);

        $user = $user->save();

        return $user;
    }

    /**
     * Updates a user
     */
    public function update($data)
    {
        $this->database->beginTransaction();
        
        $user = $this->database->select('SELECT id FROM users WHERE id = ?', [$data->id]);

        if (!$user) {
            $this->database->rollback();
            throw new FunctionalException('The user does not exist');
        }

        $this->database->update('UPDATE users SET email = ?, first_name = ?, last_name = ? WHERE id = ?', [
            $data->email,
            $data->first_name,
            $data->last_name,
            $data->id
        ]);

        $user = $this->database->select('SELECT id FROM users WHERE id = ?', [$data->id]);

        $this->database->commit();

        return $user;
    }

    /**
     * Returns a user by searching their email and hash password
     *
     * @param $email
     * @param $password
     */
    private function findByEmailPassword($email, $password)
    {
        $user = $this->user->findBy('email', $email);

        if (!$user) {
            return false;
        }
        
        return $user;

    }

    /**
     * Creates a cookie session
     *
     * @param $userId
     */
    private function saveSession($userId)
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
}
