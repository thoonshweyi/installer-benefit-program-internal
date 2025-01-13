<?php

namespace App\Models\POS113;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos113Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos113_pgsql';
    protected $table = 'amphur';
}
