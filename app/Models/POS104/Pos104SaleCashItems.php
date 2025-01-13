<?php

namespace App\Models\POS104;

use Illuminate\Database\Eloquent\Model;

class Pos104SaleCashItems extends Model
{
    protected $connection = 'pos104_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS104\Pos104TempMasterProduct','barcode_code','barcode_code');
    }
}

