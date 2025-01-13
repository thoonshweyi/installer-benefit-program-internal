<?php

namespace App\Models\POS114;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos114Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos114_pgsql';
    protected $table = 'amphur';
}
