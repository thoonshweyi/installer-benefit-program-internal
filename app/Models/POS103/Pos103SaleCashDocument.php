<?php

namespace App\Models\POS103;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos103SaleCashDocument extends Model
{
    protected $connection = 'pos103_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}