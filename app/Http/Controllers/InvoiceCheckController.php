<?php

namespace App\Http\Controllers;

use App\Models\AmountCheck;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\ProductCheck;
use Illuminate\Http\Request;
use App\Models\AmountCheckBranch;
use App\Models\ProductCheckBranch;
use App\Http\Controllers\Controller;
use App\Models\PromotionSubPromotion;

class InvoiceCheckController extends Controller
{
    public function view_invoice_check($promotion_uuid,$sub_promotion_uuid)
    {
        $luckydraw_branches =[];
        $branches = [];
        $promotion_sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)
        ->where('promotion_uuid', $promotion_uuid)->first();
        if($promotion_sub_promotion){
            if($promotion_sub_promotion->invoice_check_type == 1){
                $amount_check = AmountCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
                return view('promotion.check_invoice_amount',compact('promotion_uuid','promotion_sub_promotion','luckydraw_branches','branches','amount_check'));
            }else{
                return view('promotion.check_invoice_product',compact('promotion_uuid','promotion_sub_promotion','luckydraw_branches','branches'));
            }
        }else{
            return 'Error';
        }
    }
    public function store_invoice_check(Request $request)
    {

        //Check Sub Promotion Used
        $claimHistory = ClaimHistory::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
        if($claimHistory){
            return redirect()->back()->with('error','Sub Promotion is Used');
        }
        if($request->invoice_check_type == 1){
            $amount_check = AmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
            if($amount_check){
                $update_amount_check['amount'] = $request->amount;
                $amount_check->update($update_amount_check);

            }else{
                $update_amount_check['promotion_uuid'] = $request->promotion_uuid;
                $update_amount_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
                $update_amount_check['amount'] = $request->amount;
                $update_amount_check['uuid'] = (string) Str::uuid();
                AmountCheck::create($update_amount_check);

                ////update promotion_sub_promotion status/////
                $update_status = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
                $promotion_sub_promotion_status['invoice_check_status'] = '1';
                $update_status->update($promotion_sub_promotion_status);
            }
                ///old info,new info,reason,promotion_uuid
                create_promotion_log($request->amount,$request->amount,'Create Invoice Check Amount '.$request->amount,$request->promotion_uuid);
            return redirect()->route('new_promotion.edit', $request->promotion_uuid);
        }
        if($request->invoice_check_type == 2){
            $product_checks = ProductCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->get();

            // if($product_checks->isNotEmpty()){
            //     foreach($product_checks as $product_check){
            //         ProductCheckBranch::where('product_check_uuid', $product_check->uuid)->delete();
            //     }
            // }
            $product_check['promotion_uuid'] = $request->promotion_uuid;
            $product_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
            $product_check['check_product_name'] = $request->product_name;
            $product_check['check_product_code'] = $request->product_code;
            $product_check['check_product_qty'] = $request->product_qty;
            $product_check['uuid'] = (string) Str::uuid();
            $new_product_check = ProductCheck::create($product_check);

            ///old info,new info,reason,promotion_uuid
            create_promotion_log($request->product_name,$request->product_name,'Create Invoice Check Amount '.$request->product_name,$request->promotion_uuid);

            ///update promotion_sub_promotion status/////
            $update_status = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
            $promotion_sub_promotion_status['invoice_check_status'] = '1';
            $update_status->update($promotion_sub_promotion_status);

            // if($request->branch_id){
            //     foreach($request->branch_id as $branch_id){
            //         $product_check_branch['branch_id'] = $branch_id;
            //         $product_check_branch['product_check_uuid'] = $new_product_check->uuid;
            //         ProductCheckBranch::create($product_check_branch);
            //     }
            // }
            return redirect()->route('view_invoice_check', [$request->promotion_uuid,$request->sub_promotion_uuid]);
        }
    }

    public function edit_invoice_check($uuid)
    {
        $luckydraw_branches =[];
        $branches = [];

        $check = ProductCheck::where('uuid', $uuid)->first();
        if($check){
            AmountCheck::where('uuid',$uuid)->first();
        }
        $promotion_sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $check->sub_promotion_uuid)
        ->where('promotion_uuid', $check->promotion_uuid)->first();
        $promotion_uuid = $check->promotion_uuid;
        if($promotion_sub_promotion){
            if($promotion_sub_promotion->invoice_check_type == 1){
                return view('promotion.edit_check_invoice_amount',compact('promotion_uuid','promotion_sub_promotion','luckydraw_branches','branches'));
            }else{

                return view('promotion.edit_check_invoice_product',compact('promotion_uuid','promotion_sub_promotion','luckydraw_branches','branches','check'));
            }
        }else{
            return 'Error';
        }
    }
    public function update_invoice_check(Request $request,$uuid)
    {
        if($request->invoice_check_type == 1){
            $amount_checks = AmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->get();

            if($amount_checks->isNotEmpty()){
                foreach($product_checks as $product_check){
                    // AmountCheckBranch::where('product_check_uuid', $product_check->uuid)->delete();
                    $product_check->delete();
                }
            }
        }
        if($request->invoice_check_type == 2){

            // $product_check_branches = ProductCheckBranch::where('product_check_uuid', $uuid)->get();

            // if($product_check_branches->isNotEmpty()){
            //     $product_check_branches->delete();
            // }

            $product_checks = ProductCheck::where('uuid', $uuid)->first();

            $product_check['check_product_name'] = $request->product_name;
            $product_check['check_product_code'] = $request->product_code;
            $product_check['check_product_qty'] = $request->product_qty;
            $product_checks->update($product_check);

            // if($request->branch_id){
            //     foreach($request->branch_id as $branch_id){
            //         $product_check_branch['branch_id'] = $branch_id;
            //         $product_check_branch['product_check_uuid'] = $new_product_check->uuid;
            //         ProductCheckBranch::create($product_check_branch);
            //     }
            // }
            return redirect()->route('view_invoice_check', [$request->promotion_uuid,$request->sub_promotion_uuid]);
        }
    }
}
