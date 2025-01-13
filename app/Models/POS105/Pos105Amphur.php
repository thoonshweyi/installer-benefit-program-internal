<?php

namespace App\Models\POS105;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos105Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos105_pgsql';
    protected $table = 'amphur';
}
