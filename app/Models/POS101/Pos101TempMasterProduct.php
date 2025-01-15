<?php

namespace App\Models\POS101;

use Illuminate\Database\Eloquent\Model;

class Pos101TempMasterProduct extends Model
{
    protected $connection = 'pos101_pgsql';
    protected $table = 'temp_master_product';

    
}