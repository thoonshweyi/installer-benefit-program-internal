<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Jobs\SyncRowJob;
use App\Models\PointPay;
use App\Models\BranchUser;
use Illuminate\Support\Str;
use App\Models\ReturnBanner;
use Illuminate\Http\Request;
use App\Models\GroupedReturn;
use App\Models\InstallerCard;
use App\Models\PointPromotion;
use App\Models\PointsRedemption;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;
use App\Models\CollectionTransactionLog;
use App\Models\CollectionTransactionDeleteLog;
use App\Models\ReferenceReturnInstallerCardPoint;
use App\Models\ReferenceReturnCollectionTransaction;

class CollectionTransactionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-collection-transaction', ['only' => ['index']]);
        $this->middleware('permission:delete-collection-transaction', ['only' => ['destroy']]);
    }
    public function index(){
        $branch_id = getCurrentBranch();

        $user = Auth::user();
        if($user->can("view-all-collection-transaction")){
            $collectiontransactions = CollectionTransaction::
                                    where('branch_id',$branch_id)
                                    ->orderBy("created_at",'desc')
                                    ->paginate(10);
        }else{
            $collectiontransactions = CollectionTransaction::
                                        where('branch_id',$branch_id)
                                        ->where('user_uuid',Auth()->user()->uuid)
                                        ->orderBy("created_at",'desc')
                                        ->paginate(10);
        }
        return view("collectiontransactions.index",compact('collectiontransactions'));
    }

    public function show($uuid){
        $collectiontransaction = CollectionTransaction::where('uuid',$uuid)->first();

        $installercardpoints = InstallerCardPoint::where("collection_transaction_uuid",$uuid)
                                ->orderBy("created_at", "asc")
                                ->orderBy('id','asc')
                                ->get();
        // dd($installercardpoints);
        $returnbanners = ReturnBanner::where("collection_transaction_uuid",$uuid)->get();

        $total_available_points = 0;
        $total_available_amount = 0;
        foreach($installercardpoints as $installercardpoint){
            $total_available_points += $installercardpoint['points_balance'];
            $total_available_amount += $installercardpoint['amount_balance'];
        }

        // Get the previous URL
        $previousUrl = url()->previous();

        // Attempt to match the previous URL to a route
        $previousRoute = app('router')->getRoutes()->match(app('request')->create($previousUrl));

        // Check if the previous route has a name
        $previousRouteName = $previousRoute->getName();
        // dd($previousRouteName);


        return view("collectiontransactions.show",compact(
            'collectiontransaction',
            'installercardpoints',
            'total_available_points',
            'total_available_amount',
            'returnbanners',
            'previousRouteName'
        ));
    }

    public function destroy($uuid){
        \DB::beginTransaction();
        try{
            $collectiontransaction = CollectionTransaction::where('uuid',$uuid)->first();
            $card_number = $collectiontransaction->installer_card_card_number;
            $totalCollectedPoints = $collectiontransaction->total_points_collected;
            $totalSavedAmount = $collectiontransaction->total_save_value;

            // preventing the collection in which this installer pints is requested for redemption and successfully redeemed.
            $isalloweddelete = $collectiontransaction->allowDelete();
            // dd($isalloweddelete);
            if(!$isalloweddelete){
                // return redirect()->route("installercardpoints.detail",$card_number)->with("error","The collection transaction has relations in redemption transaction");
                return redirect()->back()->with("error","The collection transaction has relations in redemption transaction");
            }

            $installercardpoints = InstallerCardPoint::where('collection_transaction_uuid',$uuid)->get();
            foreach($installercardpoints as $installercardpoint){
                $installercardpointId = $installercardpoint->id;
                dispatch(new SyncRowJob("installer_card_points", "delete", ['id' => $installercardpointId]));
            }

            // Save the ID before deletion
            $collectiontransactionId = $collectiontransaction->id;
            $collectiontransaction->forceDelete();
            dispatch(new SyncRowJob("collection_transactions", "delete", ['id' => $collectiontransactionId]));


            $installercard = InstallerCard::where("card_number",$card_number)->first();
            $installercard->update([
                "totalpoints"=>  $installercard->totalpoints - $totalCollectedPoints,
                "totalamount"=> $installercard->totalamount - $totalSavedAmount
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$installercard));

            $user = Auth::user();
            $user_uuid = $user->uuid;
            $branch_id = getCurrentBranch();
            // Log the delete action of collection transaction
            CollectionTransactionDeleteLog::create([
                'action_user_uuid'=> $user_uuid,
                'action_branch_id'=> $branch_id,
                'old_collection_transaction_uuid' => $collectiontransaction->uuid,
                'point_promotion_uud'=> $collectiontransaction->point_promotion_uud,
                'points_award_rate'=> $collectiontransaction->points_award_rate,
                'branch_id'=> $collectiontransaction->branch_id,
                'document_no'=> $collectiontransaction->document_no,
                'installer_card_card_number'=> $collectiontransaction->installer_card_card_number,
                'invoice_number'=> $collectiontransaction->invoice_number,
                'total_sale_cash_amount'=> $collectiontransaction->total_sale_cash_amount,
                'total_points_collected'=> $collectiontransaction->total_points_collected,
                'total_save_value'=> $collectiontransaction->total_save_value,
                'collection_date'=> $collectiontransaction->collection_date,
                'user_uuid'=> $collectiontransaction->user_uuid,
            ]);


            \DB::commit();
            return redirect()->back()->with('success',"Collection Transaction Deleted Successfully");
        }catch(Exception $err){
            \DB::rollback();

            return redirect()->back()->with("error","There is an error in deleteing collection transaction");
        }
    }

    public function search(Request $request){
        $querydocno = $request->docno;
        $queryinvoice_number = $request->invoice_number;
        // dd($queryname);
        $document_from_date     = $request->from_date;
        $document_to_date       = $request->to_date;

        $branch_id = getCurrentBranch();

        $results = CollectionTransaction::query();
        // dd($results);
        if($querydocno){
            $results = $results->where('document_no','LIKE','%'.$querydocno.'%');
        }
        if($queryinvoice_number){
            $results = $results->where('invoice_number','LIKE','%'.$queryinvoice_number.'%');
        }
        if (!empty($document_from_date) || !empty($document_to_date)) {
            if($document_from_date === $document_to_date)
            {
                $results = $results->whereDate('collection_date', $document_from_date);
            }
            else
            {

                if($document_from_date && $document_to_date){
                    $from_date = Carbon::parse($document_from_date);
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereBetween('collection_date', [$from_date , $to_date]);
                }
                if($document_from_date)
                {
                    $from_date = Carbon::parse($document_from_date);
                    $results = $results->whereDate('collection_date', ">=",$from_date);
                }
                if($document_to_date)
                {
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereDate('collection_date',"<=", $to_date);
                }

            }
        }

        $results = $results->where('branch_id',$branch_id);
        $user = Auth::user();
        if($user->can("view-all-collection-transaction")){
            $collectiontransactions = $results->paginate(10);
        }else{
            $collectiontransactions = $results->where('user_uuid',Auth()->user()->uuid)
                                        ->paginate(10);
        }

        // dd($results);

        return view('collectiontransactions.index',compact("collectiontransactions"));
    }

    public function returnproduct($uuid,Request $request){
        \DB::beginTransaction();
        try{

            $return_product_docno = $request->return_product_docno;

            $collectiontransaction = CollectionTransaction::where('uuid',$uuid)->first();
            $installercardpoints = InstallerCardPoint::where("collection_transaction_uuid",$uuid)
                                    ->orderBy("created_at", "asc")
                                    ->orderBy('id','asc')
                                    ->get();
            $card_number = $collectiontransaction->installer_card_card_number;
            $installercard = InstallerCard::where("card_number",$card_number)->first();
            $branch_id = getCurrentBranch();


            // Start Checking Limitations
            if(!$collectiontransaction->isReturnable()){
                return redirect()->back()->with("error","This installer has current redemption transaction for this collection. Ask for reject or approval. We recommend reject.");
            }
            $ret_cat_grp_totals = getRetCategoryGroupPointTotal($return_product_docno,$branch_id);
            // dd($ret_cat_grp_totals);
            if(empty($ret_cat_grp_totals)){
                return redirect()->back()->with("error","Invalid Return Prodoct Document");
            }
            $saleinvoice_no = $ret_cat_grp_totals[0]->saleinvoice_no;
            $returninvoice_no = $collectiontransaction->invoice_number;
            if($saleinvoice_no !=  $returninvoice_no){
                return redirect()->back()->with("error","Return document is not related to sale invoice.");
            }
            if($collectiontransaction->checkreturnbanner($return_product_docno)){
                return redirect()->back()->with("error","This Document is already returned.");
            }
            // End Checking Limitations


            foreach($ret_cat_grp_totals as $ret_cat_grp_total){
                // dd($inv_cat_grp_total->category_name);
                $ret_cat_grp_total->maincatid = getMaincatidByName($ret_cat_grp_total->category_name);
                $ret_cat_grp_total->category_remark = getCategoryRemarkByName($ret_cat_grp_total->category_name);
            }


            $user = Auth::user();
            $user_uuid = $user->uuid;
            $referencereturncollectiontransaction = ReferenceReturnCollectionTransaction::create([
                'uuid'=> (string) Str::uuid(),
                'collection_transaction_uuid'=> $collectiontransaction->uuid,
                'point_promotion_uud'=> $collectiontransaction->point_promotion_uud,
                'points_award_rate'=> $collectiontransaction->points_award_rate,
                'branch_id'=> $collectiontransaction->branch_id,
                'document_no'=> $collectiontransaction->document_no,
                'installer_card_card_number'=> $collectiontransaction->installer_card_card_number,
                'invoice_number'=> $collectiontransaction->invoice_number,
                'total_sale_cash_amount'=> $collectiontransaction->total_sale_cash_amount,
                'total_points_collected'=> $collectiontransaction->total_points_collected,
                'total_save_value'=> $collectiontransaction->total_save_value,
                'collection_date'=> $collectiontransaction->collection_date,
                'user_uuid'=> $collectiontransaction->user_uuid,
                'buy_date'=> $collectiontransaction->buy_date,
                'gbh_customer_id'=> $collectiontransaction->gbh_customer_id,
                'sale_cash_document_id'=> $collectiontransaction->sale_cash_document_id,
                'branch_code' => $collectiontransaction->branch_code,
            ]);
            dispatch(new SyncRowJob("reference_return_collection_transactions","insert",$referencereturncollectiontransaction));



            $return_date = $ret_cat_grp_totals[0]->return_date;
            $gbh_customer_id = $ret_cat_grp_totals[0]->gbh_customer_id;
            $sale_cash_document_id = $ret_cat_grp_totals[0]->sale_cash_document_id;
            $branch_code = $ret_cat_grp_totals[0]->return_product_doc_branchcode;
            $returnbanner  = ReturnBanner::create([
                'uuid' => (string) Str::uuid(),
                'branch_id'=>$branch_id,
                'installer_card_card_number'=> $collectiontransaction->installer_card_card_number,
                'return_product_docno'=>$return_product_docno,
                'ref_invoice_number'=> $collectiontransaction->invoice_number,
                'total_return_value'=> 0,
                'total_return_points'=>0,
                'total_return_amount'=>0,
                'collection_transaction_uuid'=> $collectiontransaction->uuid,
                'reference_return_collection_transaction_uuid'=> $referencereturncollectiontransaction->uuid,
                'return_action_date'=> now(),
                'user_uuid'=> $user_uuid,
                'return_date'=> $return_date,
                'gbh_customer_id'=> $gbh_customer_id,
                'sale_cash_document_id'=> $sale_cash_document_id,
                'return_product_doc_branch_code'=> $branch_code
            ]);
            dispatch(new SyncRowJob("return_banners","insert",$returnbanner));

            $total_return_price_amount = 0;
            $total_return_points = 0;
            $total_return_amount = 0;
            foreach($ret_cat_grp_totals as $item){

                $category_id = $item->category_id;
                $category_name = $item->category_name;
                $group_id = $item->group_id;
                $group_name = $item->group_name;
                $maincatid = $item->maincatid;
                $category_remark = $item->category_remark;
                // $returnpriceamount = $item->sale_price_amount;
                $returnpriceamount = $item->returnamnt;


                foreach($installercardpoints as $installercardpoint){
                    if($installercardpoint->category_id == $category_id && $installercardpoint->group_id == $group_id){

                        $referencereturninstallercardpoint = ReferenceReturnInstallerCardPoint::create([
                            'uuid' => (string) Str::uuid(),
                            'installer_card_point_uuid'=>$installercardpoint->uuid,
                            'installer_card_card_number'=>$installercardpoint->installer_card_card_number,
                            'maincatid'=>$installercardpoint->maincatid,
                            'category_remark'=>$installercardpoint->category_remark,
                            'category_id'=>$installercardpoint->category_id,
                            'category_name'=>$installercardpoint->category_name,
                            'group_id'=>$installercardpoint->group_id,
                            'group_name'=>$installercardpoint->group_name,
                            'saleamount'=>$installercardpoint->saleamount,
                            'points_earned'=>$installercardpoint->points_earned,
                            'points_redeemed'=>$installercardpoint->points_redeemed,
                            'points_balance'=>$installercardpoint->points_balance,
                            'point_based'=>$installercardpoint->point_based,
                            'amount_earned'=>$installercardpoint->amount_earned,
                            'amount_redeemed'=>$installercardpoint->amount_redeemed,
                            'amount_balance'=>$installercardpoint->amount_balance,
                            'preused_points'=>$installercardpoint->preused_points,
                            'preused_amount'=>$installercardpoint->preused_amount,
                            'expiry_date'=>$installercardpoint->expiry_date,
                            'is_redeemed'=>$installercardpoint->is_redeemed,
                            'is_returned'=>$installercardpoint->is_returned,
                            'collection_transaction_uuid'=>$installercardpoint->collection_transaction_uuid,
                            'expire_deduction_date'=>$installercardpoint->expire_deduction_date,
                            'reference_return_collection_transaction_uuid'=> $referencereturncollectiontransaction->uuid
                        ]);
                        dispatch(new SyncRowJob("reference_return_installer_card_points","insert",$referencereturninstallercardpoint));


                        $redemption_value = $installercardpoint->point_based;
                        $return_point = $item->return_point;
                        $return_amount = $return_point * $redemption_value;

                        $groupedreturn = GroupedReturn::create([
                            'installer_card_point_uuid'=> $installercardpoint->uuid,
                            'reference_return_installer_card_point_uuid'=>$referencereturninstallercardpoint->uuid,
                            'maincatid' => $maincatid,
                            'category_remark' => $category_remark,
                            'category_id' =>$category_id,
                            'category_name'=>$category_name,
                            'group_id' => $group_id,
                            'group_name' => $group_name,
                            'return_price_amount' => $returnpriceamount,
                            'return_point' => $return_point,
                            'return_amount' => $return_amount,
                            'return_banner_uuid' => $returnbanner->uuid
                        ]);
                        dispatch(new SyncRowJob("grouped_returns","insert",$groupedreturn));

                        $total_return_price_amount += $returnpriceamount;
                        $total_return_points +=  $return_point;
                        $total_return_amount += $return_amount;

                    }
                }
            }
            $returnbanner->update([
                'total_return_value' => $total_return_price_amount,
                'total_return_points'=> $total_return_points,
                'total_return_amount'=> $total_return_amount,
            ]);
            dispatch(new SyncRowJob("return_banners","update",$returnbanner));


            $totalDeductedPoints = 0;
            $totalMissAmount = 0;
            $totalReturnPriceAmount = $total_return_price_amount;
            $preusedpoints = 0;
            $preusedamount = 0;
            foreach($ret_cat_grp_totals as $item){
                $category_id = $item->category_id;
                $category_name = $item->category_name;
                $group_id = $item->group_id;
                $group_name = $item->group_name;
                $maincatid = $item->maincatid;
                $category_remark = $item->category_remark;
                // $returnpriceamount = $item->sale_price_amount;
                $returnpriceamount = $item->returnamnt;

                foreach($installercardpoints as $installercardpoint){
                    if($installercardpoint->category_id == $category_id && $installercardpoint->group_id == $group_id){

                        $newsaleamount = $installercardpoint->saleamount - $returnpriceamount;
                        $card_number = $installercardpoint->installer_card_card_number;

                        // Recalculate point earnings with the newsale amount
                        // --with the updated rules
                        // $point_promotion_uuid = $installercardpoint->collectiontransaction->pointpromotion->uuid;
                        // $pointpromotion = PointPromotion::where('uuid',$point_promotion_uuid)->first();
                        // $points_per_amount = $pointpromotion->pointperamount;
                        // $redemption_value = $matching_rule->redemption_value;


                        // --with the already awarded rules
                        $points_per_amount = $installercardpoint->collectiontransaction->points_award_rate;
                        $redemption_value = $installercardpoint->point_based;

                        // $points_earned = floor($newsaleamount / $points_per_amount);
                        $points_earned = $installercardpoint->points_earned + $item->return_point;
                        $amount_earned = $points_earned * $redemption_value;

                        $miss_amount = $installercardpoint->amount_earned - $amount_earned;


                        $points_redeemed = $installercardpoint->points_redeemed;
                        $amount_redeemed = $installercardpoint->amount_redeemed;
                        $points_balance = $points_earned - $points_redeemed;
                        $amount_balance = $amount_earned - $amount_redeemed;
                        // Excluding the paid points
                        $points_paid = PointPay::where('installer_card_point_uuid',$installercardpoint->uuid)->sum('points_paid');
                        $amount_paid = PointPay::where('installer_card_point_uuid',$installercardpoint->uuid)->sum('accept_value');
                        $points_balance += $points_paid;
                        $amount_balance += $amount_paid;
                        if($points_balance <= 0 && $amount_balance <= 0){
                            $is_redeemed = 1;
                        }else{
                            $is_redeemed = 0;
                        }

                        $all_preused_points = $points_earned - $points_redeemed;
                        $all_preused_amount = $amount_earned - $amount_redeemed;
                        // Get this time preused points
                        $preusedpoints += $points_balance < 0 ? ($all_preused_points - $installercardpoint->preused_points) : 0;
                        $preusedamount += $amount_balance < 0 ? ($all_preused_amount - $installercardpoint->preused_amount) : 0;
                        $updatearr =  [
                            'id'=>$installercardpoint->id,
                            // 'uuid' => (string) Str::uuid(),
                            'installer_card_card_number'=>$card_number,
                            'maincatid' => $maincatid,
                            'category_remark'=>$category_remark,
                            'category_id'=>$category_id,
                            'category_name'=>$category_name,
                            'group_id'=>$group_id,
                            'group_name'=>$group_name,
                            'saleamount'=> $newsaleamount,
                            'points_earned'=> $points_earned,
                            'points_redeemed'=> $points_redeemed,
                            'points_balance'=> $points_balance,
                            'point_based'=> $redemption_value,
                            'amount_earned'=> $amount_earned,
                            'amount_redeemed'=> $amount_redeemed,
                            'amount_balance'=>$amount_balance ,
                            'preused_points'=> $all_preused_points < 0 ? $all_preused_points : 0,
                            'preused_amount'=> $all_preused_amount < 0 ? $all_preused_amount : 0,
                            // 'expiry_date'=> Carbon::now()->endOfYear(),
                            'is_redeemed'=> $is_redeemed,
                            'is_returned'=> 1,
                            // 'collection_transaction_uuid' => $collectiontransaction->uuid
                        ];
                        $installercardpoint->update($updatearr);
                        dispatch(new SyncRowJob("installer_card_points","update",$updatearr));


                        $totalDeductedPoints += abs($item->return_point);
                        $totalMissAmount += $miss_amount;


                    }
                }
            }


            $collectiontransaction->update([
                "total_sale_cash_amount"=>$collectiontransaction->total_sale_cash_amount - $totalReturnPriceAmount,
                "total_points_collected"=>$collectiontransaction->total_points_collected - $totalDeductedPoints,
                "total_save_value"=>$collectiontransaction->total_save_value - $totalMissAmount
            ]);
            dispatch(new SyncRowJob("collection_transactions","update",$collectiontransaction));

            // Not intantly deduct preused points and saved as debt
            $installercard->update([
                "totalpoints"=>  $installercard->totalpoints - $totalDeductedPoints + abs($preusedpoints),
                "totalamount"=> $installercard->totalamount - $totalMissAmount  + abs($preusedamount),
                'credit_points'=> $installercard->credit_points + $preusedpoints,
                'credit_amount'=> $installercard->credit_amount + $preusedamount,
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$installercard));


            if($installercard->totalpoints > 0){
                deductPreUsedPointsFromCard($installercard->card_number);
            }

            \DB::commit();
            return redirect(route('collectiontransactions.show',$collectiontransaction->uuid));

        }catch(Exception $err){
            \DB::rollback();

            return redirect()->back()->with("error","There is an error in returning products.");
        }
    }

}
