<?php

namespace App\Models\POS110;

use Illuminate\Database\Eloquent\Model;

class Pos110SaleCashItems extends Model
{
    protected $connection = 'pos110_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS114\Pos114TempMasterProduct','barcode_code','barcode_code');
    }
}

