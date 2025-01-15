<?php

namespace App\Models\ShwePyiThar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShwePyiTharTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_shwepyithar';
    protected $table = 'ticket_header_step_sales';

}
