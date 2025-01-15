<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMBrand extends Model
{
    use HasFactory;
    protected $connection = 'pos112_pgsql';
    protected $table="master_brand";
}
