<?php

namespace App\Models\POS103;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos103Amphur extends Model
{
    use HasFactory;

    protected $connection = 'pos103_pgsql';
    protected $table = 'amphur';
}
