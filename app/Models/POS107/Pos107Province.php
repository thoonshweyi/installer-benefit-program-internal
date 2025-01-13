<?php

namespace App\Models\POS107;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos107Province extends Model
{
    use HasFactory;
    protected $connection = 'pos107_pgsql';
    protected $table = 'province';
}
