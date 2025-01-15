<?php

namespace App\Models\POS114;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos114Province extends Model
{
    use HasFactory;
    protected $connection = 'pos114_pgsql';
    protected $table = 'province';
}
