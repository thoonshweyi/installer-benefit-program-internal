<?php

namespace App\Models\POS106;

use Illuminate\Database\Eloquent\Model;

class Pos106SaleCashItems extends Model
{
    protected $connection = 'pos106_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS106\Pos106TempMasterProduct','barcode_code','barcode_code');
    }
}

