<?php
namespace App\Models;

use App\Core\Models\BaseModel;
use App\Core\Singletons\Database;

class User extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
        'email',
        'first_name',
        'last_name'
    ];

    private function setPTODays($user, $days = 21)
    {
        $this->database->insert('INSERT INTO pto(employee_id, remaining_days) VALUES(?, ?)', [
            $user->id, 
            $days
        ]);
    }

    public function create($data)
    {
        $user = parent::create($data);

        if ($user->role === 'employee') $this->setPTODays($user);                   

        return $user;
    }
}
