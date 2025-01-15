<?php

namespace App\Models\POS112;

use Illuminate\Database\Eloquent\Model;

class Pos112TempMasterProduct extends Model
{
    protected $connection = 'pos112_pgsql';
    protected $table = 'temp_master_product';

    
}