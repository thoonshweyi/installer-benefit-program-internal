<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CusSaleAmounts extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "cus_sale_amounts";
    protected $fillable = [
        'customer_barcode',
        'phone',
        'sale_amount',
        'sale_amount_check_uuid',
    ];

    public function saleamountcheck(){
        return $this->belongsTo(SaleAmountCheck::class,"sale_amount_check_uuid","uuid");
    }
}
