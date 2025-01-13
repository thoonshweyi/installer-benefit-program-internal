<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountCheck extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'promotion_uuid',
        'sub_promotion_uuid',
        'amount',
    ];
}
