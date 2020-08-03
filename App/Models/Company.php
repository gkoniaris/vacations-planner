<?php
namespace App\Models;

use App\Core\Models\BaseModel;
use App\Core\Singletons\Database;

class Company extends BaseModel
{
    protected $table = 'companies';
    protected $fillable = [
        'name',
        'vat',
        'country',
        'city',
        'industry_id',
        'total_employees'
    ];
}
