<?php

namespace App\Models\POS112;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos112Province extends Model
{
    use HasFactory;
    protected $connection = 'pos112_pgsql';
    protected $table = 'province';
}
