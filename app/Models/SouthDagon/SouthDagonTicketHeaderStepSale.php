<?php

namespace App\Models\SouthDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouthDagonTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_southdagon';
    protected $table = 'ticket_header_step_sales';

}
