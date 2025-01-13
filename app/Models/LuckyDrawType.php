<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyDrawType extends Model
{
    use HasFactory;

    protected $connection = "pgsql";
    protected $table = "promotion_types";
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'status',
    ];

    public function promotions(){
        return $this->hasMany(LuckyDraw::class,'lucky_draw_type_uuid','uuid');
    }
}
