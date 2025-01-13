<?php

namespace App\Models\AyeTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyeTharyarLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_ayetharyar';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\AyeTharyar\AyeTharyarBrand','brand_id','product_brand_id');
    }
}