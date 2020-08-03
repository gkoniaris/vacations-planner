<?php
namespace App\Models;

use App\Core\Models\BaseModel;
use App\Core\Singletons\Database;

class Industry extends BaseModel
{
    protected $table = 'industries';
    protected $fillable = [
        'name'
    ];
}
