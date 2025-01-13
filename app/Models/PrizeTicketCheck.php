<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrizeTicketCheck extends Model
{
    use HasFactory;
    public $fillable = [
        'uuid',
        'promotion_uuid',
        'sub_promotion_uuid',
        'ticket_prize_image',
        'ticket_prize_qty',
    ];
}
