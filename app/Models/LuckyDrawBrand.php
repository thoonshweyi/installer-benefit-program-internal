<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyDrawBrand extends Model
{
    use HasFactory;
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function lanthitBrands(){
        return $this->belongsTo('App\Models\Lanthit\LanthitBrand','good_brand_id','product_brand_id');
    }

    public function theikPanBrands(){
        return $this->belongsTo('App\Models\TheikPan\TheikPanBrand','good_brand_id','product_brand_id');
    }

    public function satsanBrands(){
        return $this->belongsTo('App\Models\Satsan\SatsanBrand','good_brand_id','product_brand_id');
    }

    public function eastDagonBrands(){
        return $this->belongsTo('App\Models\EastDagon\EastDagonBrand','good_brand_id','product_brand_id');
    }

    public function mawlamyineBrands(){
        return $this->belongsTo('App\Models\Mawlamyine\MawlamyineBrand','good_brand_id','product_brand_id');
    }

    public function tampawadyBrands(){
        return $this->belongsTo('App\Models\Tampawady\TampawadyBrand','good_brand_id','product_brand_id');
    }

    public function hlaingTharyarBrands(){
        return $this->belongsTo('App\Models\HlaingTharyar\HlaingTharyarBrand','good_brand_id','product_brand_id');
    }

    public function ayeTharyarBrands(){
        return $this->belongsTo('App\Models\AyeTharyar\AyeTharyarBrand','good_brand_id','product_brand_id');
    }

    public function terminalMBrands(){
        return $this->belongsTo('App\Models\TerminalM\TerminalMBrand','good_brand_id','product_brand_id');
    }

    public function southDagonBrands(){
        return $this->belongsTo('App\Models\SouthDagon\SouthDagonBrand','good_brand_id','product_brand_id');
    }

    public function bagoBrands(){
        return $this->belongsTo('App\Models\Bago\BagoBrand','good_brand_id','product_brand_id');
    }
}
