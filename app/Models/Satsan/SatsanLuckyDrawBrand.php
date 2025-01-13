<?php

namespace App\Models\Satsan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatsanLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_satsan';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\Satsan\SatsanBrand','brand_id','product_brand_id');
    }
}