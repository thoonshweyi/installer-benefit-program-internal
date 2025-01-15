<?php

namespace App\Models\POS103;

use Illuminate\Database\Eloquent\Model;

class Pos103TempMasterProduct extends Model
{
    protected $connection = 'pos103_pgsql';
    protected $table = 'temp_master_product';

    
}