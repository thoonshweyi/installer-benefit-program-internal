<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Support\Str;
use App\Models\PrizeCCCheck;
use Illuminate\Http\Request;
use App\Models\CashCouponBranch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use App\Models\PromotionSubPromotion;
use App\Models\ExtendCheckPrizeGrabChance;

class PrizeCCCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uuid = (string) Str::uuid();
        $filename = $uuid .'.png';
        $prize_cash_coupon_check['uuid'] = $uuid;
        $prize_cash_coupon_check['promotion_uuid'] = $request->promotion_uuid;
        $prize_cash_coupon_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
        $prize_cash_coupon_check['qty'] = $request->qty;
        $prize_cash_coupon_check['name'] = $request->name;
        $prize_cash_coupon_check['type'] = $request->type;
        $prize_cash_coupon_check['ticket_image'] = $filename;
        
        $prize_cash_coupon_check = PrizeCCCheck::create($prize_cash_coupon_check);
        if ($request->ticket_image) {
            File::delete(public_path('images/cash_coupon_image/' . $filename));
            $request->ticket_image->move(public_path('images/cash_coupon_image'), $filename);
        }
        $prize_cash_coupon_check_uuid = $prize_cash_coupon_check->uuid;
        $branch_ids = $request->branch_id;
        foreach ($branch_ids as $branch_id) {
            $prize_cash_coupon_check_branch['prize_cash_coupon_check_uuid'] = $prize_cash_coupon_check_uuid;
            $prize_cash_coupon_check_branch['branch_id'] = $branch_id;
            CashCouponBranch::create($prize_cash_coupon_check_branch);

        }
        $sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)
        ->where('promotion_uuid', $request->promotion_uuid)->first();
  
        $check_type_update['prize_check_type'] = 2;
        $sub_promotion->update($check_type_update);

        return json_encode(array(
            "statusCode"=>200
        ));
    } 
    public function cash_coupon_result(Request $request)
    {

        $result = PrizeCCCheck::with('cashCouponBranches')->where('type', '1')->get();
        return DataTables::of($result)
            ->addColumn('branch',function($data){
                foreach ($data->cashCouponBranches as $cashCouponBranches){
                    $cashCouponBranchArray[] =  $cashCouponBranches->Branches->branch_name_eng;
                }
                return implode(", ", $cashCouponBranchArray); 

                if (isset($data->invoices)) {
                    $invoices = '';
                    foreach ($data->invoices as $invoice) {
                        $invoices .= $invoice->invoice_no . ',';
                    }
                    return rtrim($invoices, ", ");
                }
                return '';
            })
            ->addIndexColumn()
            ->make(true);
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws_prices.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }

    public function present_result(Request $request)
    {
        $result = PrizeCCCheck::with('cashCouponBranches')->where('type' ,'2')->get();
        return DataTables::of($result)
            ->addColumn('branch',function($data){
                foreach ($data->cashCouponBranches as $cashCouponBranches){
                    $cashCouponBranchArray[] =  $cashCouponBranches->Branches->branch_name_eng;
                }
                return implode(", ", $cashCouponBranchArray); 

                if (isset($data->invoices)) {
                    $invoices = '';
                    foreach ($data->invoices as $invoice) {
                        $invoices .= $invoice->invoice_no . ',';
                    }
                    return rtrim($invoices, ", ");
                }
                return '';
            })
            ->addIndexColumn()
            ->make(true);
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws_prices.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }
    public function cash_coupon_branch_by_name(Request $request)
    {
        // try{
            $cash_coupon_uuid = $request->uuid;
            $cash_coupon_info = PrizeCCCheck::where('uuid', $cash_coupon_uuid)->first();
            $cash_coupon_branch_info = CashCouponBranch::select('branch_id')->where('prize_cash_coupon_check_uuid', $cash_coupon_info->uuid)->get()->pluck('branch_id')->toarray();
            $branches = Branch::select('branch_id','branch_name_eng')->wherein('branch_id', $cash_coupon_branch_info)->get()->toarray();
        return $branches;
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to generate Document No!');
        // }
    }
    public function prize_item_destory($prize_cc_check_uuid)
    {
        $check_prize_check_qty = PrizeCCCheck::where('uuid', $prize_cc_check_uuid)->first();
        $check_extend_qty = ExtendCheckPrizeGrabChance::where('prize_c_c_check_uuid', $prize_cc_check_uuid)->where('extended_qty',$check_prize_check_qty->qty)->delete();
        $check_prize_check_qty->delete();
        return response()->json([
            'success' => 'Item is deleted successfully',
        ]);
    }


}
