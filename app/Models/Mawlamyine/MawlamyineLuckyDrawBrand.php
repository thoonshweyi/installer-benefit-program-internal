<?php

namespace App\Models\Mawlamyine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MawlamyineLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mawlamyine';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\Mawlamyine\MawlamyineBrand','brand_id','product_brand_id');
    }
}