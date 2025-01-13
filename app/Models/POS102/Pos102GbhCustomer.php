<?php

namespace App\Models\POS102;

use Illuminate\Database\Eloquent\Model;

class Pos102GbhCustomer extends Model
{
    protected $connection = 'pos102_pgsql';
    protected $table = 'gbh_customer';
}