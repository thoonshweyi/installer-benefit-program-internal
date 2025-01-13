<?php

namespace App\Models\POS106;

use Illuminate\Database\Eloquent\Model;

class Pos106TempMasterProduct extends Model
{
    protected $connection = 'pos106_pgsql';
    protected $table = 'temp_master_product';

    
}