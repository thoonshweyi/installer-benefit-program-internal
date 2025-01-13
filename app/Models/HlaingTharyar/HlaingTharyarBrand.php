<?php

namespace App\Models\HlaingTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HlaingTharyarBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos107_pgsql';
    protected $table="master_brand";
}