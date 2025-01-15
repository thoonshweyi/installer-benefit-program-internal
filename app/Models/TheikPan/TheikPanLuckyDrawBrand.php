<?php

namespace App\Models\TheikPan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheikPanLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_theikpan';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\TheikPan\TheikPanBrand','brand_id','product_brand_id');
    }
}