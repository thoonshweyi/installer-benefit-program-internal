<?php

namespace App\Models\POS108;

use Illuminate\Database\Eloquent\Model;

class Pos108SaleCashItems extends Model
{
    protected $connection = 'pos108_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS108\Pos108TempMasterProduct','barcode_code','barcode_code');
    }
}

