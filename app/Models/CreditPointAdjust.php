<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditPointAdjust extends Model
{
    use HasFactory;
    protected $table = "credit_point_adjusts";
    protected $fillable = [
        "uuid",
        'branch_id',
        'document_no',
        "installer_card_card_number",
        'total_points_adjusted',
        'total_adjust_value',
        'status',
        'adjust_date',
        'prepare_by',
        'approved_by',
        "approved_date",
        "reason",
        'remark',
        'bm_remark',
        "collection_transaction_uuid",
    ];


    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function user(){
        return $this->belongsTo(User::class,"user_uuid","uuid");
    }

    public function prepareby() {
        return $this->belongsTo(User::class, "prepare_by", "uuid");
    }
    public function approvedby(){
        return $this->belongsTo(User::class,"approved_by","uuid");
    }

    public function isApproveAuthUser(){

        $user = Auth::user();

        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
                            ->where('branch_id', $this->branch_id)
                            ->exists();

        // Check if the user has the Branch Manager role
        $isBranchManager = $user->roles()->whereIn('name', ['Branch Manager',"Super Admin"])->exists();
         // Return true if both conditions are met
         return $belongsToBranch && $isBranchManager;
    }



}
