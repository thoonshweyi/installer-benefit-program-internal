<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyDrawPrize extends Model
{
    use HasFactory;
    protected $table="promotion_prizes";
    protected $fillable = [
        'promotion_uuid',
        'order',
        'name',
        'amount',
        'quantity',
        'description', 
    ];
}
