<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardNumberGenerator extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "card_number_generators";
    protected $fillable = [
        "uuid",
        "branch_id",
        "document_no",
        "to_branch_id",
        "quantity",
        "random",
        "status",
        "prepare_by",
        "approved_by",
        "approved_date",
        "remark",
        "mkt_mgr_remark",
        "exported_by",
        "exported_date"
    ];


    protected static function boot(){
        parent::boot();

        static::creating(function($cardnumbergenerator){
            $cardnumbergenerator->document_no = self::generate_doc_no($cardnumbergenerator->branch_id);
        });
    }
    public static function generate_doc_no($branch_id)
    {
        $prefix = 'ICG';
        $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
        $todayDate = Carbon::now()->format('Ymd'); // Format as YYYYMMDD


        // Query the latest document for the branch from today
        $lasttransaction = CardNumberGenerator::where('branch_id', $branch_id)
                                ->whereDate('created_at', Carbon::today())
                                ->orderBy('document_no', 'desc')
                                ->first();
        // dd($lasttransaction);

        // Set initial suffix
        $newSuffix = '0001';

        if ($lasttransaction) {
            // Extract the numeric suffix from the last document number
            $lastSuffix = (int) substr($lasttransaction->document_no, -4);

            // Increment the suffix by 1 and pad with zeros
            $newSuffix = str_pad($lastSuffix + 1, 4, '0', STR_PAD_LEFT);
        }
        // Combine parts to create the new document number
        $documentNumber = $prefix . $branch_prefix . $todayDate . '-' . $newSuffix;
        // dd($documentNumber);

        return $documentNumber;
    }


    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function tobranch(){
        return $this->belongsTo(Branch::class,"to_branch_id","branch_id");
    }


    public function prepareby() {
        return $this->belongsTo(User::class, "prepare_by", "uuid");
    }
    public function approvedby(){
        return $this->belongsTo(User::class,"approved_by","uuid");
    }
    public function exportedby(){
        return $this->belongsTo(User::class,"exported_by","uuid");
    }

    public function cardnumbers(){
        return $this->hasMany(CardNumber::class,"card_number_generator_uuid","uuid");
    }



    public function isApproveAuthUser(){

        $user = Auth::user();

        // dd($user->roles);
        // Check if the user's branch matches the transaction's branch
        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
                            ->where('branch_id', $this->branch_id)
                            ->exists();

        // Check if the user has the Branch Manager role
        $isMktManager = $user->roles()->whereIn('name', ['Marketing Manager',"Super Admin"])->exists();
         // Return true if both conditions are met
         return $belongsToBranch && $isMktManager;
    }


    public function isFinishedAuthUser(){

        $user = Auth::user();

        $prepareby_user = User::where("uuid",$this->prepare_by)->first();


        return ($user == $prepareby_user);
    }

    public function multipleExportUser(){
        $user = Auth::user();

        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
        ->where('branch_id', $this->branch_id)
        ->exists();

        // Check if the user has the Branch Manager role
        $isMktManager = $user->roles()->whereIn('name', ["Super Admin"])->exists();


        return $belongsToBranch && $isMktManager;
    }


}
