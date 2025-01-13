<?php

namespace App\Models\POS108;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos108Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos108_pgsql';
    protected $table = 'amphur';
}
