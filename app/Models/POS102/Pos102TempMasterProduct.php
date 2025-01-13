<?php

namespace App\Models\POS102;

use Illuminate\Database\Eloquent\Model;

class Pos102TempMasterProduct extends Model
{
    protected $connection = 'pos102_pgsql';
    protected $table = 'temp_master_product';

    
}