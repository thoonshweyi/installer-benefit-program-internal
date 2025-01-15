<?php

namespace App\Models\Bago;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagoTicketHeaderInvoice extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_bago';
    protected $table = 'ticket_header_invoices';

}
