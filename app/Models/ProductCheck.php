<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCheck extends Model
{
    use HasFactory;
    protected $fillable =[
        'uuid',
        'promotion_uuid',
        'sub_promotion_uuid',
        'check_product_code',
        'check_product_name',
        'check_product_qty',
        'check_product_amount',
    ];

}
