<?php

namespace App\Models\POS114;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos114SaleCashDocument extends Model
{
    protected $connection = 'pos114_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}
