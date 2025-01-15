<?php

namespace App\Models\POS114;

use Illuminate\Database\Eloquent\Model;

class Pos114TempMasterProduct extends Model
{
    protected $connection = 'pos114_pgsql';
    protected $table = 'temp_master_product';


}
