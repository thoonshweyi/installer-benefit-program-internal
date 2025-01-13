<?php

namespace App\Models\POS113;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos113SaleCashDocument extends Model
{
    protected $connection = 'pos113_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}