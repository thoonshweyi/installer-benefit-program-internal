<?php

namespace App\Models\POS105;

use Illuminate\Database\Eloquent\Model;

class Pos105TempMasterProduct extends Model
{
    protected $connection = 'pos105_pgsql';
    protected $table = 'temp_master_product';

    
}