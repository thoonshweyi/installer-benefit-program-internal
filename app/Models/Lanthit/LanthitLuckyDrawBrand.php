<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_lanthit';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\Lanthit\LanthitBrand','brand_id','product_brand_id');
    }
}