<?php

namespace App\Models\HlaingTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HlaingTharyarLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_hlaingtharyar';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\HlaingTharyar\HlaingTharyarBrand','brand_id','product_brand_id');
    }
}