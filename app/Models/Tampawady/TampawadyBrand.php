<?php

namespace App\Models\Tampawady;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TampawadyBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos106_pgsql';
    protected $table="master_brand";
}