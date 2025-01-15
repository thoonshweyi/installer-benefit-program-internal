<?php

namespace App\Models\POS107;

use Illuminate\Database\Eloquent\Model;

class Pos107SaleCashItems extends Model
{
    protected $connection = 'pos107_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS107\Pos107TempMasterProduct','barcode_code','barcode_code');
    }
}

