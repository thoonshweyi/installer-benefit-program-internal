<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitTicketHeaderInvoice extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_lanthit';
    protected $table = 'ticket_header_invoices';

}
