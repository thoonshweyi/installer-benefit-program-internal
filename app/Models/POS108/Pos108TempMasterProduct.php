<?php

namespace App\Models\POS108;

use Illuminate\Database\Eloquent\Model;

class Pos108TempMasterProduct extends Model
{
    protected $connection = 'pos108_pgsql';
    protected $table = 'temp_master_product';

    
}