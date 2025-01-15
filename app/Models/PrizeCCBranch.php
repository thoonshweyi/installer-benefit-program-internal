<?php

namespace App\Models;

use App\Models\TiketHeader;
use App\Models\SubPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrizeCCBranch extends Model
{
    use HasFactory;
    protected $fillable = [
            'uuid',
            'prize_c_c_uuid',
            'branch_id',
            'total_qty',
            'remain_qty'
    ];
    
    public function branch(){
        return $this->hasOne('App\Models\Branch','branch_id','branch_id');
    }
    public function prize_cc(){
        return $this->hasOne('App\Models\PrizeCCCheck','uuid','prize_c_c_uuid');
    }

}
