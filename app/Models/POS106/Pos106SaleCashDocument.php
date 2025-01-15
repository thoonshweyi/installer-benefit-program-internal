<?php

namespace App\Models\POS106;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos106SaleCashDocument extends Model
{
    protected $connection = 'pos106_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}