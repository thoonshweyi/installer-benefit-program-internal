<?php

namespace App\Models\POS110;

use Illuminate\Database\Eloquent\Model;

class Pos110GbhCustomer extends Model
{
    protected $connection = 'pos110_pgsql';
    protected $table = 'gbh_customer';
}
