<?php

namespace App\Models\Mawlamyine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MawlamyineTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mawlamyine';
    protected $table = 'ticket_header_step_sales';

}
