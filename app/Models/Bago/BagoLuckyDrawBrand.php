<?php

namespace App\Models\Bago;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagoLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_bago';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\Bago\BagoBrand','brand_id','product_brand_id');
    }
}
