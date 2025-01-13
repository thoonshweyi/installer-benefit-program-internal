<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHeaderInvoice extends Model
{
    use HasFactory;
    protected $table = 'ticket_header_invoices';

    protected $fillable = [
        'uuid',
        'ticket_header_uuid', 
        'invoice_id',
        'invoice_no',
        'valid_amount',
        'valid_ticket_qty',
        'promotion_uuid'
    ];
    
    public function ticket_headers(){
        return $this->belongsTo('App\Models\TicketHeader','ticket_header_uuid','uuid');
    }
}
