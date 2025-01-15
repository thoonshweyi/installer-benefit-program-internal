<?php

namespace App\Models\POS101;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Pos101SaleCashDocument extends Model
{
    protected $connection = 'pos101_pgsql';
    protected $table = 'sale_cash.sale_cash_document';
}