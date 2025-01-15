<?php

namespace App\Models\POS101;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos101Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos101_pgsql';
    protected $table = 'amphur';
}
