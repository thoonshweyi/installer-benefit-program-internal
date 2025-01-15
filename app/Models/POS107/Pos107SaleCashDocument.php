<?php

namespace App\Models\POS107;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos107SaleCashDocument extends Model
{
    protected $connection = 'pos107_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}