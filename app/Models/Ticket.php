<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'ticket_no',
        'ticket_header_uuid',
        'promotion_uuid',
        'claim_history_uuid',
        'created_at'
    ];

    public function ticket_headers(){
        return $this->belongsTo('App\Models\TicketHeader','ticket_header_uuid','uuid')->withDefault();
    }

    public function promotions()
    {
        return $this->belongsTo(LuckyDraw::class,'promotion_uuid','uuid')->withDefault();
    }
}
