<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NRC extends Model
{
    use HasFactory;

    protected $connection = 'pos_pgsql';
    protected $table = 'nrc';

    
}
