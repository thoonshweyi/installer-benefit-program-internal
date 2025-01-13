<?php

namespace App\Models;

use App\Models\PrizeCCCheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClaimHistoryDetail extends Model
{
    use HasFactory;
    protected $table = 'claim_history_details';

    protected $fillable = [
        'uuid',
        'claim_history_uuid',
        'times',
        'price_cc_check_uuid',
        'prize_item_uuid',
        'serial_no',
    ];
    public function prize_cc_check()
    {
        return $this->hasOne('App\Models\PrizeCCCheck', 'uuid', 'price_cc_check_uuid');
    }
    public function claim_history()
    {
        return $this->hasOne('App\Models\ClaimHistory', 'uuid', 'claim_history_uuid');
    }
}
