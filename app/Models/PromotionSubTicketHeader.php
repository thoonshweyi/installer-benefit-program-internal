<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionSubTicketHeader extends Model
{
    use HasFactory;
    protected $table = 'promotion_sub_ticketheaders';
    protected $fillable = [
        'promotion_uuid',
        'sub_promotion_uuid',
        'ticket_header_uuid',
        'invoice_id',
        'invoice_no',
        'valid_amount',
        'gbh_customer_id',
        'status'
    ];
    public function sub_promotions(){
        return $this->hasOne('App\Models\SubPromotion','uuid','sub_promotion_uuid');
    }
    public function promotion(){
        return $this->belongsTo('App\Models\LuckyDraw','promotion_uuid','uuid');
    }
}
