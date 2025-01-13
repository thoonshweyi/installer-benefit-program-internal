<?php

namespace App\Models\TheikPan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheikPanBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos_pgsql';
    protected $table="master_brand";
}
