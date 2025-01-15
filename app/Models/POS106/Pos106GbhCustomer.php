<?php

namespace App\Models\POS106;

use Illuminate\Database\Eloquent\Model;

class Pos106GbhCustomer extends Model
{
    protected $connection = 'pos106_pgsql';
    protected $table = 'gbh_customer';
}