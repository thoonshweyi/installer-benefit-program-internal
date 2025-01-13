<?php

namespace App\Models\POS103;

use Illuminate\Database\Eloquent\Model;

class Pos103GbhCustomer extends Model
{
    protected $connection = 'pos103_pgsql';
    protected $table = 'gbh_customer';
}