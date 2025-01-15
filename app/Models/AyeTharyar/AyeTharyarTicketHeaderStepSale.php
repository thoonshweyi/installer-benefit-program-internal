<?php

namespace App\Models\AyeTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyeTharyarTicketHeaderStepSale extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_ayetharyar';
    protected $table = 'ticket_header_step_sales';

}
