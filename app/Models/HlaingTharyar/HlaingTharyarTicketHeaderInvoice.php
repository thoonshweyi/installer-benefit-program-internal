<?php

namespace App\Models\HlaingTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HlaingTharyarTicketHeaderInvoice extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_hlaingtharyar';
    protected $table = 'ticket_header_invoices';

}
