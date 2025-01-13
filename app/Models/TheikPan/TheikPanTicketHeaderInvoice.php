<?php

namespace App\Models\TheikPan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheikPanTicketHeaderInvoice extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_theikpan';
    protected $table = 'ticket_header_invoices';

}
