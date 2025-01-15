<?php

namespace App\Models\ShwePyiThar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShwePyiTharBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos114_pgsql';
    protected $table="master_brand";
}
