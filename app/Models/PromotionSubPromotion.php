<?php

namespace App\Models;

use App\Models\LuckyDraw;
use App\Models\SubPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionSubPromotion extends Model
{
    use HasFactory;
    protected $table = 'promotion_sub_promotion';
    protected $fillable = [
        'promotion_uuid',
        'sub_promotion_uuid',
        'invoice_check_type',
        'prize_check_type',
        'invoice_check_status',
        'prize_check_status',
    ];
    public function sub_promotions(){
        return $this->hasOne('App\Models\SubPromotion','uuid','sub_promotion_uuid');
    }
    public function promotion(){
        return $this->belongsTo('App\Models\LuckyDraw','promotion_uuid','uuid');
    }
}
