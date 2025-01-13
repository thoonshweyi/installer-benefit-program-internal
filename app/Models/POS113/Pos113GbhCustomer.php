<?php

namespace App\Models\POS113;

use Illuminate\Database\Eloquent\Model;

class Pos113GbhCustomer extends Model
{
    protected $connection = 'pos113_pgsql';
    protected $table = 'gbh_customer';
}