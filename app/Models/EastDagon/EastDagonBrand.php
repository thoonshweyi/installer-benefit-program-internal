<?php

namespace App\Models\EastDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EastDagonBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos104_pgsql';
    protected $table="master_brand";
}