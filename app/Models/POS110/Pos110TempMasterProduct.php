<?php

namespace App\Models\POS110;

use Illuminate\Database\Eloquent\Model;

class Pos110TempMasterProduct extends Model
{
    protected $connection = 'pos110_pgsql';
    protected $table = 'temp_master_product';


}
