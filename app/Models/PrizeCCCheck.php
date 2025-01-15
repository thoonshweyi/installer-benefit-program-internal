<?php

namespace App\Models;

use App\Models\SubPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrizeCCCheck extends Model
{
    use HasFactory;
    protected $fillable = [
            'uuid',
            'prize_item_uuid',
            'promotion_uuid',
            'sub_promotion_uuid',
            'qty',
            'stock_qty',
            'ticket_image'
    ];

    public function prizeItem(){
        return $this->hasOne('App\Models\PrizeItem','uuid','prize_item_uuid');
    }
    public function sub_promotions(){
        return $this->hasMany('App\Models\SubPromotion','uuid','sub_promotion_uuid');
    }
    public function promotion(){
        return $this->hasOne('App\Models\LuckyDraw','uuid','promotion_uuid');
    }

    public function prizeCCBranch(){
        return $this->hasOne('App\Models\PrizeCCBranch','prize_c_c_uuid','uuid');
    }
}
