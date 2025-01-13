<?php

namespace App\Models;

use App\Models\LuckyDraw;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubPromotion extends Model
{
    use HasFactory;
    protected $table = 'sub_promotions';
    protected $fillable = [
        'uuid',
        'name',
    ];
}
