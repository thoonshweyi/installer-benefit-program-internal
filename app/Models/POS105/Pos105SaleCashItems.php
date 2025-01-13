<?php

namespace App\Models\POS105;

use Illuminate\Database\Eloquent\Model;

class Pos105SaleCashItems extends Model
{
    protected $connection = 'pos105_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS105\Pos105TempMasterProduct','barcode_code','barcode_code');
    }
}

