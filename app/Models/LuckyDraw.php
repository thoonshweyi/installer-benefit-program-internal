<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyDraw extends Model
{
    use HasFactory;
    protected $table = "promotions";
    protected $fillable = [
        'uuid',
        'name',
        'start_date',
        'end_date',
        'prefix',
        'number_of_ticket',
        'amount_for_one_ticket',
        'status',
        'remark',
        'discon_status',
        'lucky_draw_type_uuid',
        'diposit_type_id',
    ];
    public function promotion_type()
    {
        return $this->hasOne('App\Models\LuckyDrawType', 'uuid', 'lucky_draw_type_uuid');
    }
    public function promotion_sub_promotions()
    {
        return $this->hasMany('App\Models\PromotionSubPromotion', 'promotion_uuid', 'uuid');
    }
    public function claim_histories()
    {
        return $this->hasMany('App\Models\ClaimHistory', 'promotion_uuid', 'uuid');
    }

}
