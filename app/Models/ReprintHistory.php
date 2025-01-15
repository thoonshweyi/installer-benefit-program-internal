<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReprintHistory extends Model
{
    protected $fillable = [
        'uuid',
        'printed_user_uuid',
        'claim_history_uuid',
    ];
    public function printed_user(){
        return $this->belongsTo('App\Models\User','printed_user_uuid','uuid');
    }
    public function claim_history(){
        return $this->belongsTo('App\Models\ClaimHistory','claim_history_uuid','uuid');
    }
}
