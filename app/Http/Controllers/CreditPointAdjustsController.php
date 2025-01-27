<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CreditPointAdjust;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditPointAdjustDetail;

class CreditPointAdjustsController extends Controller
{
    public function index(){
        $branch_id = getCurrentBranch();

        $creditpointadjusts = CreditPointAdjust::
                                where('branch_id',$branch_id)
                                ->orderBy("created_at",'desc')
                                ->paginate(10);
        return view("creditpointadjusts.index",compact('creditpointadjusts'));
    }

    public function store(Request $request){
        $request->validate([
            'total_points_adjusted' => "required",
            'total_adjust_value' => "required",
            'reason'=>"required",
            "remark"=>"required"
        ]);

        $card_number = $request->card_number;
        $branch_id = getCurrentBranch();
        $user = Auth::user();
        $user_uuid = $user->uuid;
        $creditpointadjust = CreditPointAdjust::create([
            'uuid' => (string) Str::uuid(),
            'branch_id'=>$branch_id,
            'document_no' => $this->generate_doc_no($branch_id),
            "installer_card_card_number"=> $request->card_number,
            'total_points_adjusted'=> $request->total_points_adjusted,
            'total_adjust_value'=>$request->total_adjust_value,
            'status' => 'pending',
            'adjust_date'=> now(),
            'remark'=> $request->remark,
            "user_uuid"=>$user->uuid,
        ]);


        $preusedpointbybases = InstallerCardPoint::select(
            'point_based',
            DB::raw('SUM(points_balance) as total_points_balance'),
            DB::raw('SUM(amount_balance) as total_amount_balance')
        )
        ->where("installer_card_card_number", $card_number)
        ->where('points_balance', "<", 0)
        ->where('amount_balance', "<", 0)
        ->groupBy('point_based')
        ->get();

        // dd($preusedpoints);
        foreach($preusedpointbybases as $preusedpointbybase){
            $creditpointadjustdetail = CreditPointAdjustDetail::create([
                "point_based"=> $preusedpointbybase->point_based,
                "points_adjusted"=> abs($preusedpointbybase->total_points_balance),
                "amount_adjusted"=>  abs($preusedpointbybase->total_amount_balance),
                "point_adjust_uuid"=> $creditpointadjust->uuid
            ]);
        }

        return redirect()->back()->with("success","Credit Point Adjust Request successfully created");
    }

    public static function generate_doc_no($branch_id)
    {
        $prefix = 'ICA';
        $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
        $todayDate = Carbon::now()->format('Ymd'); // Format as YYYYMMDD


        // Query the latest document for the branch from today
        $lasttransaction = CreditPointAdjust::where('branch_id', $branch_id)
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


    public function show($uuid){
        $creditpointadjust = CreditPointAdjust::where('uuid',$uuid)->first();

        $installercardpoints = InstallerCardPoint::where("collection_transaction_uuid",$uuid)
                                ->orderBy("created_at", "asc")
                                ->orderBy('id','asc')
                                ->get();


        return view("collectiontransactions.show",compact(
            'collectiontransaction',
            'installercardpoints',
            'total_available_points',
            'total_available_amount',
            'returnbanners',
            'previousRouteName'
        ));
    }


}
