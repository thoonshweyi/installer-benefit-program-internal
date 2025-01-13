<?php

namespace App\Models\POS104;

use Illuminate\Database\Eloquent\Model;

class Pos104GbhCustomer extends Model
{
    protected $connection = 'pos104_pgsql';
    protected $table = 'gbh_customer';
}