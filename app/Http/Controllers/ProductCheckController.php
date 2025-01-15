<?php

namespace App\Http\Controllers;

use App\Models\AmountCheck;
use App\Models\AmountCheckBranch;
use App\Models\ProductCheck;
use App\Models\ProductCheckBranch;
use App\Models\PromotionSubPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCheckController extends Controller
{
    public function create(Request $request)
    {
        return view('promotion.edit');
    }
    public function store(Request $request)
    {
        $check_product['promotion_uuid'] = $request->promotion_uuid;
        $check_product['sub_promotion_uuid'] = $request->sub_promotion_uuid;
        $check_product['check_product_name'] = $request->product_name;
        $check_product['check_product_code'] = $request->product_code;
        $check_product['check_product_qty'] = $request->product_qty;
        $check_product['uuid'] = (string) Str::uuid();
        $check_product = ProductCheck::create($check_product);
   
     
        $sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)
            ->where('promotion_uuid', $request->promotion_uuid)->first();
        $check_type_update['invoice_check_type'] = $request->invoice_check_status;

        $sub_promotion->update($check_type_update);
    
        if ($request->p_branch_id) {
            foreach ($p_branch_id as $branch_id) {
                $check_product_branch['product_check_uuid'] = $check_product_uuid;
                $check_product_branch['branch_id'] = $branch_id;
                ProductCheckBranch::create($check_product_branch);
            }
        }
       
        $amount_check = AmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
        if ($amount_check) {
            AmountCheckBranch::where('amount_check_uuid', $amount_check->uuid)->delete();
            $amount_check->delete();
        }
        return json_encode(array(
            "statusCode" => 200,
        ));
    }
   
    public function check_products_destory($uuid)
    {
       ProductCheck::where('uuid', $uuid)->delete();
       return response()->json([
        'success', 'Check Product deleted successfully'
    ]);

    }
}
