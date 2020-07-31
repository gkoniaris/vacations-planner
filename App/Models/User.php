<?php
namespace App\Models;

use App\Models\BaseModel;
use App\Singletons\Database;

class User extends BaseModel
{
    protected $table = 'users';

    public function create($data)
    {
        try {
            Database::beginTransaction();
    
            $user = parent::create($data);
    
            if ($user->role === 'employee') {
                Database::insert('INSERT INTO pto(employee_id, remaining_days) VALUES(?, ?)', [$user->id, 21]);
            }
    
            Database::insert('INSERT INTO supervisor_employee(employee_id, supervisor_id) 
                VALUES(?, ?)', [
                    $user->id,
                    1
                ]);
    
            Database::commit();

            return $user;
        } catch(\Exception $e) {
            Database::rollback();

            throw $e;
        }
    }
}
