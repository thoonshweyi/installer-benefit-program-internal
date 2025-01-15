<?php

namespace App\Models\POS101;

use Illuminate\Database\Eloquent\Model;

class Pos101SaleCashItems extends Model
{
    protected $connection = 'pos101_pgsql';
    protected $table = 'sale_cash.sale_cash_items';

    public function products(){
        return $this->belongsTo('App\Models\POS101\Pos101TempMasterProduct','barcode_code','barcode_code');
    }
}

