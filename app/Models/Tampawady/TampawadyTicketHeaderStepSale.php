<?php

namespace App\Models\Tampawady;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TampawadyTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_tampawady';
    protected $table = 'ticket_header_step_sales';

}
