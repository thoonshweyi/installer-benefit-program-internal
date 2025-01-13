<?php

namespace App\Models\POS104;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos104Province extends Model
{
    use HasFactory;
    protected $connection = 'pos104_pgsql';
    protected $table = 'province';
}
