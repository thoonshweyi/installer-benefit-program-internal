<?php

namespace App\Models\POS108;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos108SaleCashDocument extends Model
{
    protected $connection = 'pos108_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}