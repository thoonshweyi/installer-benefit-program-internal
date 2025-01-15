<?php

namespace App\Models;

use App\Models\PirzeCCCheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtendCheckPrizeGrabChance extends Model
{
    use HasFactory;
    protected $table = 'extended_item_histories';
    protected $fillable = [
        'uuid',
        'prize_c_c_check_uuid',
        'branch_id',
        'extended_qty',
        'action',
        'extended_by',
    ];

    public function prize_cc_checks(){
        return $this->hasMany('PirzeCCCheck','uuid','prize_c_c_check_uuid');
    }
    public function users(){
        return $this->belongsTo('App\Models\User','extended_by','uuid');
    }

}
