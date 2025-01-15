<?php

namespace App\Models\Tampawady;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TampawadyLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_tampawady';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\Tampawady\TampawadyBrand','brand_id','product_brand_id');
    }
}