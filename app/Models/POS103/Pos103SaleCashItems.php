<?php

namespace App\Models\POS103;

use Illuminate\Database\Eloquent\Model;

class Pos103SaleCashItems extends Model
{
    protected $connection = 'pos103_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS103\Pos103TempMasterProduct','barcode_code','barcode_code');
    }
}

