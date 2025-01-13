<?php

namespace App\Models\POS105;

use Illuminate\Database\Eloquent\Model;

class Pos105GbhCustomer extends Model
{
    protected $connection = 'pos105_pgsql';
    protected $table = 'gbh_customer';
}