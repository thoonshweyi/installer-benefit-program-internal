<?php

namespace App\Models\POS107;

use Illuminate\Database\Eloquent\Model;

class Pos107GbhCustomer extends Model
{
    protected $connection = 'pos107_pgsql';
    protected $table = 'gbh_customer';
}