<?php

namespace App\Models\POS112;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos112SaleCashDocument extends Model
{
    protected $connection = 'pos112_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}