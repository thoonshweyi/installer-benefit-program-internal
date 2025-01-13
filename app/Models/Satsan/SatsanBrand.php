<?php

namespace App\Models\Satsan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatsanBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos103_pgsql';
    protected $table="master_brand";
}