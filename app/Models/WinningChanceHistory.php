<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinningChanceHistory extends Model
{
    use HasFactory;
    protected $fillable =[
        'uuid',
        'c_c_winning_chance_uuid',
        'user_uuid',
        'winning_percentage',
        'action',
    ];

}
