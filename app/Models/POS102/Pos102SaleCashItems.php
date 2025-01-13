<?php

namespace App\Models\POS102;

use Illuminate\Database\Eloquent\Model;

class Pos102SaleCashItems extends Model
{
    protected $connection = 'pos102_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS102\Pos102TempMasterProduct','barcode_code','barcode_code');
    }
}

