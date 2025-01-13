<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimHistory extends Model
{
    use HasFactory;
    protected $table = 'claim_histories';

    protected $fillable = [
        'uuid',
        'ticket_header_uuid',
        'promotion_uuid',
        'sub_promotion_uuid',
        'one_qty_amount',
        'invoice_check_type',
        'prize_check_type',
        'valid_qty',
        'choose_qty',
        'remain_choose_qty',
        'choose_status',
        'remain_claim_qty',
        'claim_status',
        'claimed_at',
        'print_status',
        'printed_at',
        'promotion_sub_promotion_id'
       
    ];

    public function ticket_header()
    {
        return $this->hasOne('App\Models\TicketHeader', 'uuid', 'ticket_header_uuid');
    }

    public function promotion()
    {
        return $this->hasOne('App\Models\LuckyDraw', 'uuid', 'promotion_uuid');
    }

    public function sub_promotion()
    {
        return $this->hasOne('App\Models\SubPromotion', 'uuid', 'sub_promotion_uuid');
    }
}
