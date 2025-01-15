<?php

namespace App\Models\SouthDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouthDagonLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_southdagon';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\SouthDagon\SouthDagonBrand','brand_id','product_brand_id');
    }
}