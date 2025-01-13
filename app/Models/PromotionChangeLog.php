<?php

namespace App\Models;

use App\Models\LuckyDraw;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionChangeLog extends Model
{
    use HasFactory;
    protected $table = 'promotion_change_log';
    protected $fillable = [
            'uuid',
            'promotion_uuid',
            'date',
            'user_uuid',
            'old_info',
            'new_info',
            'reason',
    ];
    public function users(){
        return $this->belongsTo('App\Models\User','user_uuid','uuid');
    }
    public function promotions(){
        return $this->belongsTo('App\Models\LuckyDraw','promotion_uuid','uuid');
    }
}
