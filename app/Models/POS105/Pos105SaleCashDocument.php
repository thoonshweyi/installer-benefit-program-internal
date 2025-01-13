<?php

namespace App\Models\POS105;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos105SaleCashDocument extends Model
{
    protected $connection = 'pos105_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}