<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = 'province';
    protected $fillable = ['province_id','province_name','region_id'];


}
