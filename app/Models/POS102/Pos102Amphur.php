<?php

namespace App\Models\POS102;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos102Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos102_pgsql';
    protected $table = 'amphur';
}
