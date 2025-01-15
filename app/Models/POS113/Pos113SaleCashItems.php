<?php

namespace App\Models\POS113;

use Illuminate\Database\Eloquent\Model;

class Pos113SaleCashItems extends Model
{
    protected $connection = 'pos113_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS113\Pos113TempMasterProduct','barcode_code','barcode_code');
    }
}

