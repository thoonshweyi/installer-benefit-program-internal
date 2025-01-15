<?php

namespace App\Models\EastDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EastDagonTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_eastdagon';
    protected $table = 'ticket_header_step_sales';

}
