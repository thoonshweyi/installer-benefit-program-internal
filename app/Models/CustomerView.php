<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerView extends Model
{
    use HasFactory;
    protected $fillable = [
        'ip',
        'route_name',
        'branch_id',
        'status',
    ];
}
