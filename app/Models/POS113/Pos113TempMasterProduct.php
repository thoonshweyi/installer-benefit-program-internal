<?php

namespace App\Models\POS113;

use Illuminate\Database\Eloquent\Model;

class Pos113TempMasterProduct extends Model
{
    protected $connection = 'pos113_pgsql';
    protected $table = 'temp_master_product';

    
}