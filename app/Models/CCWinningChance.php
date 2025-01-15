<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\PrizeCCCheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CCWinningChance extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'promotion_uuid',
        'sub_promotion_uuid',
        'branch_id',
        'prize_cc_check_uuid',
        'minimum_amount',
        'winning_percentage',
    ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
    public function prize_cc_checks(){
        return $this->belongsTo('App\Models\PrizeCCCheck','prize_cc_check_uuid','uuid');
    }

}
