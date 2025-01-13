<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_terminalm';
    protected $table = 'ticket_header_step_sales';

}
