<?php

namespace App\Models\POS104;

use Illuminate\Database\Eloquent\Model;

class Pos104TempMasterProduct extends Model
{
    protected $connection = 'pos104_pgsql';
    protected $table = 'temp_master_product';

    
}