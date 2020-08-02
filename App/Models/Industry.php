<?php
namespace App\Models;

use App\Models\BaseModel;
use App\Singletons\Database;

class Industry extends BaseModel
{
    protected $table = 'industries';
    protected $fillable = [
        'name'
    ];
}
