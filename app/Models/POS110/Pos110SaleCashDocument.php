<?php

namespace App\Models\POS110;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos110SaleCashDocument extends Model
{
    protected $connection = 'pos110_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}
