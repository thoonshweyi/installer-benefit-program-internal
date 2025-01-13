<?php

namespace App\Models\POS112;

use Illuminate\Database\Eloquent\Model;

class Pos112GbhCustomer extends Model
{
    protected $connection = 'pos112_pgsql';
    protected $table = 'gbh_customer';
}