<?php

namespace App\Models\SouthDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouthDagonBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos113_pgsql';
    protected $table="master_brand";
}