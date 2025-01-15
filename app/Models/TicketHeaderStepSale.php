<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'ticket_header_uuid', 
        'step_sale_type', 
        'qty', 
    ];

}
