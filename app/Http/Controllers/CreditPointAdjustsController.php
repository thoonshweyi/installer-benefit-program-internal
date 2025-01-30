<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Jobs\SyncRowJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\PointPromotion;
use App\Models\CreditPointAdjust;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;
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
            "prepare_by"=>$user->uuid,
            'reason'=> $request->reason,
            'remark'=> $request->remark,
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
                "credit_point_adjust_uuid"=> $creditpointadjust->uuid
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


    // public function show($uuid){
    //     $creditpointadjust = CreditPointAdjust::where('uuid',$uuid)->first();

    //     $installercardpoints = InstallerCardPoint::where("collection_transaction_uuid",$uuid)
    //                             ->orderBy("created_at", "asc")
    //                             ->orderBy('id','asc')
    //                             ->get();


    //     return view("collectiontransactions.show",compact(
    //         'collectiontransaction',
    //         'installercardpoints',
    //         'total_available_points',
    //         'total_available_amount',
    //         'returnbanners',
    //         'previousRouteName'
    //     ));
    // }

    public function edit($uuid){
        $creditpointadjust = CreditPointAdjust::where('uuid',$uuid)->orderBy('id','asc')->first();
        $branches = Branch::all();

        $creditpointadjustdetails = CreditPointAdjustDetail::where('credit_point_adjust_uuid',$uuid)->get();
        return view("creditpointadjusts.edit",compact('creditpointadjust',"branches","creditpointadjustdetails"));
    }



    public function approveCreditPointAdjustReq($uuid,Request $request){

        \DB::beginTransaction();
        // try{



            $user = Auth::user();
            $user_uuid = $user->uuid;

            $creditpointadjust = CreditPointAdjust::where("uuid",$uuid)->first();
            $creditpointadjust->update([
                "status"=>"approved",
                "approved_by"=>$user_uuid,
                "approved_date"=>now(),
                "bm_remark"=>$request->remark
            ]);

            $card_number = $creditpointadjust->installer_card_card_number;
            $installercard = InstallerCard::where("card_number",$card_number)->first();
            $branch_id = getCurrentBranch();


            if($creditpointadjust->total_points_adjusted != abs($installercard->credit_points)){
                return redirect()->back()->with("error","Adjusted credit points and installer's actual credit are not equal");
            }

            $currentDate = Carbon::now();
            $activePointPromotion = PointPromotion::where('status', 1)
                ->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)
                ->with('pointRules.pointRuleGroups')
                ->whereHas('branches',function($query) use ($branch_id){
                    $query->where('branches.id',$branch_id);
                })->first();

            $invoice_number = $creditpointadjust->document_no;
            $totalCollectedPoints = $creditpointadjust->total_points_adjusted;
            $totalSavedAmount = $creditpointadjust->total_adjust_value;
            $collectiontransaction  = CollectionTransaction::create([
                'uuid' => (string) Str::uuid(),
                'point_promotion_uud'=>$activePointPromotion->uuid,
                'points_award_rate'=>$activePointPromotion->pointperamount,
                'branch_id'=>$branch_id,
                // 'document_no' => $this->generate_doc_no($branch_id),
                'installer_card_card_number'=> $card_number,
                'invoice_number'=>$invoice_number,
                'total_sale_cash_amount'=>0,
                'total_points_collected'=> $totalCollectedPoints,
                'total_save_value'=> $totalSavedAmount,
                'collection_date'=>now(),
                'user_uuid'=> $user_uuid,
                // 'buy_date'=>$buy_date,
                // 'gbh_customer_id'=>$gbh_customer_id,
                // 'sale_cash_document_id'=>$sale_cash_document_id,
                // 'branch_code'=>$branch_code,
            ]);
            dispatch(new SyncRowJob("collection_transactions","insert",$collectiontransaction));


            $category_remark = $creditpointadjust->reason;
            $category_id = null;
            $category_name = null;
            $group_id = null;
            $group_name = null;

            $creditpointadjustdetails = CreditPointAdjustDetail::where('credit_point_adjust_uuid',$uuid)->get();
            foreach($creditpointadjustdetails as $creditpointadjustdetail){
                $points_earned = $creditpointadjustdetail->points_adjusted;
                $amount_earned = $creditpointadjustdetail->amount_adjusted;
                $point_based = $creditpointadjustdetail->point_based;
                $installercardpoint = InstallerCardPoint::create([
                    'uuid' => (string) Str::uuid(),
                    'installer_card_card_number'=>$card_number,
                    'maincatid' => 0,
                    'category_remark'=>$category_remark,
                    'category_id'=>$category_id ? $category_id : '0',
                    'category_name'=>$category_name ? $category_name : ' ',
                    'group_id'=>$group_id ? $group_id: '0',
                    'group_name'=>$group_name ? $group_name : ' ',
                    'saleamount'=> 0,
                    'points_earned'=> $points_earned,
                    'points_redeemed'=>0,
                    'points_balance'=> ($points_earned >= 0) ? $points_earned: 0,
                    'point_based'=> $point_based,
                    'amount_earned'=> $amount_earned,
                    'amount_redeemed'=>0,
                    'amount_balance'=>$amount_earned ,
                    'preused_points'=>0,
                    'preused_amount'=>0,
                    'expiry_date'=> Carbon::now()->addMonths(6),
                    'is_redeemed'=> 0,
                    'is_returned'=> 0,
                    'collection_transaction_uuid' => $collectiontransaction->uuid
                ]);
                dispatch(new SyncRowJob("installer_card_points","insert",$installercardpoint));
            }

            $installercard->update([
                "totalpoints"=>  $installercard->totalpoints + $totalCollectedPoints,
                "totalamount"=> $installercard->totalamount + $totalSavedAmount
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$installercard));

            if($installercard->totalpoints > 0){
                // Deduct the credit point and amount from the new imcomming points
                deductPreUsedPointsFromCard($installercard->card_number);
            }
            \DB::commit();

            return redirect()->back()->with("success","Installer Points are adjusted successfully");





        // }catch(Exception $err){
        //     \DB::rollback();

        //     return redirect()->route('installercardpoints.detail',$card_number)->with("error","There is an error in saving installer points");
        // }
    }

    public function rejectCreditPointAdjustReq($uuid,Request $request){

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $creditpointadjust = CreditPointAdjust::where("uuid",$uuid)->first();

        $creditpointadjust->update([
            "status"=>"rejected",
            "approved_by"=>$user_uuid,
            "approved_date"=>now(),
            "bm_remark"=>$request->remark
        ]);


        return redirect()->back();

    }
}
