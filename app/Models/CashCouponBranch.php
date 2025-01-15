<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCouponBranch extends Model
{
    use HasFactory;
    protected $table = "cash_coupon_branches";
    protected $fillable = [
        'prize_cash_coupon_check_uuid',
        'branch_id'
    ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }

}
