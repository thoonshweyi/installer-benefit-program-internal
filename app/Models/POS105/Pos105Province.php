<?php

namespace App\Models\POS105;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos105Province extends Model
{
    use HasFactory;
    protected $connection = 'pos105_pgsql';
    protected $table = 'province';
}
