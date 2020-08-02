<?php
namespace App\Models;

use App\Models\BaseModel;
use App\Singletons\Database;

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
