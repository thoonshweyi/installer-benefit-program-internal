<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedPrizeAmountCheck extends Model
{
    use HasFactory;
    protected $table = "fixed_prize_amount_checks";
    protected $fillable =[
        'uuid',
        'promotion_uuid',
        'sub_promotion_uuid',
        'fixed_prize_name',
        'fixed_prize_qty',
        'fixed_prize_gp_code',
        'fixed_prize_type',
        'fixed_prize_ticket_amount',
    ];
}
