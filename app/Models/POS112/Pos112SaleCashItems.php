<?php

namespace App\Models\POS112;

use Illuminate\Database\Eloquent\Model;

class Pos112SaleCashItems extends Model
{
    protected $connection = 'pos112_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS112\Pos112TempMasterProduct','barcode_code','barcode_code');
    }
}

