<?php

namespace App\Models\POS114;

use Illuminate\Database\Eloquent\Model;

class Pos114SaleCashItems extends Model
{
    protected $connection = 'pos114_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS114\Pos114TempMasterProduct','barcode_code','barcode_code');
    }
}

