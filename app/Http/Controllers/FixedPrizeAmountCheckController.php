<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PrizeTicketCheck;
use Illuminate\Support\Facades\File;
use App\Models\FixedPrizeAmountCheck;
use App\Http\Controllers\FixedPrizeAmountCheckController;

class FixedPrizeAmountCheckController extends Controller
{
    public function store(Request $request)
    {
        $aa = (string) Str::uuid();
        $fixed_prize_check['uuid'] = $aa;
        $filename = $aa .'.png';
        $fixed_prize_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
        $fixed_prize_check['fixed_prize_ticket_amount'] = $request->fixed_prize_ticket_amount;
        $fixed_prize_check['fixed_prize_ticket_image'] = $filename;
        $fixed_prize_check = FixedPrizeAmountCheck::create($fixed_prize_check);
        if ($request->fixed_prize_ticket_image) {
         File::delete(public_path('images/fixed_prize_image/' . $filename));
         $request->fixed_prize_ticket_image->move(public_path('images/fixed_prize_image'), $filename);
        }

        $check_prize_ticket_check = PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->first();
        if($check_prize_ticket_check){
            $check_prize_ticket_check = PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->delete();
            return response()->json(['success' => 'Name is already'], 200);
        }
    }
    public function fixed_prize_data_search(Request $request)
    {
        $aa = (string) Str::uuid();
        $fixed_prize_check['uuid'] = $aa;
        $filename = $aa .'.png';
        $fixed_prize_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
        $fixed_prize_check['fixed_prize_ticket_amount'] = $request->fixed_prize_ticket_amount;
        $fixed_prize_check['fixed_prize_ticket_image'] = $filename;
        $fixed_prize_check = FixedPrizeAmountCheck::create($fixed_prize_check);
        if ($request->fixed_prize_ticket_image) {
         File::delete(public_path('images/fixed_prize_image/' . $filename));
         $request->fixed_prize_ticket_image->move(public_path('images/fixed_prize_image'), $filename);
        }
       $check_prize_ticket_check = PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->first();
    if($check_prize_ticket_check){
        $check_prize_ticket_check = PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->delete();
        return response()->json(['success' => 'Name is already'], 200);
    }

}


}
