<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMTicket extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_terminalm';
    protected $table="tickets";
    protected $fillable = [
        'uuid',
        'ticket_no',
        'ticket_header_uuid',
        'promotion_uuid'
    ];

    public function ticket_headers(){
        return $this->belongsTo('App\Models\TicketHeader','ticket_header_uuid','uuid');
    }
}
