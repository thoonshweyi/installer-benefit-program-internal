<?php

namespace App\Models\POS110;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos110Province extends Model
{
    use HasFactory;
    protected $connection = 'pos110_pgsql';
    protected $table = 'province';
}
