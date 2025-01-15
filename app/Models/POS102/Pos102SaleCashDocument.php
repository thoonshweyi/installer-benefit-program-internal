<?php

namespace App\Models\POS102;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos102SaleCashDocument extends Model
{
    protected $connection = 'pos102_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}