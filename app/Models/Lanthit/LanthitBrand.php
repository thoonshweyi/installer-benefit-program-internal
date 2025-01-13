<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos_pgsql';
    protected $table="master_brand";
}
