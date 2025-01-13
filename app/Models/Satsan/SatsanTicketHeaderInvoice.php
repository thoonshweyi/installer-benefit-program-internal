<?php

namespace App\Models\Satsan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatsanTicketHeaderInvoice extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_satsan';
    protected $table = 'ticket_header_invoices';

}
