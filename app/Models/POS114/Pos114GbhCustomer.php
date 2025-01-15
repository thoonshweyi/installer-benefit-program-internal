<?php

namespace App\Models\POS114;

use Illuminate\Database\Eloquent\Model;

class Pos114GbhCustomer extends Model
{
    protected $connection = 'pos114_pgsql';
    protected $table = 'gbh_customer';
}
