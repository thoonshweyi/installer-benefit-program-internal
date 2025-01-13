<?php

namespace App\Models\Mawlamyine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MawlamyineBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos105_pgsql';
    protected $table="master_brand";
}