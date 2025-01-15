<?php

namespace App\Models\POS101;

use Illuminate\Database\Eloquent\Model;

class Pos101GbhCustomer extends Model
{
    protected $connection = 'pos_pgsql';
    protected $table = 'gbh_customer';
}
