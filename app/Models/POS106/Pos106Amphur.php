<?php

namespace App\Models\POS106;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos106Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos106_pgsql';
    protected $table = 'amphur';
}
