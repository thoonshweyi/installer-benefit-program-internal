<?php

namespace App\Models\AyeTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyeTharyarBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos108_pgsql';
    protected $table="master_brand";
}