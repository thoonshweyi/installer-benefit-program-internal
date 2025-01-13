<?php

namespace App\Models\POS108;

use Illuminate\Database\Eloquent\Model;

class Pos108GbhCustomer extends Model
{
    protected $connection = 'pos108_pgsql';
    protected $table = 'gbh_customer';
}