<?php

namespace App\Models\Bago;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagoBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos110_pgsql';
    protected $table="master_brand";
}
