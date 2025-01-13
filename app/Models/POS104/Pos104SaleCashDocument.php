<?php

namespace App\Models\POS104;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos104SaleCashDocument extends Model
{
    protected $connection = 'pos104_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}