<?php

namespace App\Http\Controllers\NewCreateTicket;

use App\Http\Controllers\Controller;
use App\Models\AmountCheck;
use App\Models\Branch;
use App\Models\ClaimHistory;
use App\Models\FixedPrizeAmountCheck;
use App\Models\PromotionSubPromotion;
use App\Models\PromotionSubTicketHeader;
use App\Models\TicketHeader;
use App\Models\TicketHeaderInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateInvoiceController extends Controller
{
    public function create_new_ticket(Request $request)
    {
        // dd($request->all());
        $invoice_no = $request->invoice_no;

        //check used invoice
        $ticket_header_invoice = check_used_invoice($invoice_no);

        if (isset($ticket_header_invoice)) {
            return response()->json(['error' => 'invoice_is_used'], 200);
        }
        //Find Sub Promoiton

        $promoiton_sub_promotions = PromotionSubPromotion::whereHas('promotion', function ($q) {
            $q->where('status', '=', 1);
        })->where('deleted_at', null)->with('promotion')->get();
        // dd('sub');
        //Create Ticket Header uuid
        $ticket_header_uuid = (string) Str::uuid();

        //Find Branch
        $branch_id = findBrandId($invoice_no);
        if (!$branch_id) {
            return response()->json(['error' => 'invoice_is_not_format'], 200);
        }

        // dd($invoice_nos);
        $invoice_nos[] = $invoice_no;
        $ticket_header_total =0;
        // dd($invoice_nos);
        foreach ($promoiton_sub_promotions as $promoiton_sub_promotion) {
            $promotion_data = findPromotionData($promoiton_sub_promotion->promotion);

            if ($promoiton_sub_promotion->prize_check_type == 3) {
                //Check Gold Ring - Fixed Prize Type - 1 for Gold Ring, 2 fo Gold Coin
                $check_gold_ring = FixedPrizeAmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)
                    ->pluck('fixed_prize_type')->first();

            } else {
                $check_gold_ring = 2;
            }

            //check same promotion in Pro Sub TicketHeader
            $same_promotion = PromotionSubTicketHeader::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('invoice_no', $invoice_no)->latest()->first();

            if (!$same_promotion || $check_gold_ring == 1) {
                //Calculate Available Amount based on active promoition

                $total_amount = findValidTotalAmountOfProductsFromInvoice($invoice_no, $promoiton_sub_promotion,$promotion_data,$check_gold_ring);
                $sub_promotion_uuid = $promoiton_sub_promotion->sub_promotion_uuid;

            } else {

                $total_amount['invoice_id'] = $same_promotion->invoice_id;
                $total_amount['totalprice'] = $same_promotion->valid_amount;
                $total_amount['gbh_customer_id'] = $same_promotion->gbh_customer_id;
                $sub_promotion_uuid = $same_promotion->sub_promotion_uuid;
            }
            // Return Error
            if (isset($total_amount['message'])) {
                return response()->json(['error' => $total_amount['message']], 200);
            }
            //Store or Update in Pro Sub TicketHeader
            $same_sub_promotion = PromotionSubTicketHeader::where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->where('invoice_no', $invoice_no)->first();
            // dd($total_amount);

            if (!$same_sub_promotion) {
                $sub_pro = PromotionSubTicketHeader::create([
                    'promotion_uuid' => $promoiton_sub_promotion->promotion_uuid,
                    'sub_promotion_uuid' => $sub_promotion_uuid,
                    'ticket_header_uuid' => $ticket_header_uuid,
                    'gbh_customer_id' => $total_amount['gbh_customer_id'],
                    'invoice_id' => $total_amount['invoice_id'],
                    'invoice_no' => $invoice_no,
                    'valid_amount' => $total_amount['totalprice'] ? $total_amount['totalprice']:0,
                    'status'            =>$total_amount['status']
                ]);
                // dd($sub_pro);
            }

            if ($promoiton_sub_promotion->invoice_check_type == 1) {
                $amount_check = AmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
                ->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();
                $one_qty_amount = $amount_check->amount;
            } else {
                $one_qty_amount = 0;
            }

            //Store or Update in claim_history
            $one_claim_history      = ClaimHistory::where(['promotion_sub_promotion_id'=>$promoiton_sub_promotion->id,'ticket_header_uuid'=>$ticket_header_uuid])->first();
            // dd($one_claim_history);

            if ($one_qty_amount != 0) {
                $valid_total_qty = $total_amount['totalprice'] / $one_qty_amount;
            } else {
                $valid_total_qty = 0;
            }
            // dd($valid_total_qty);

            $claim_history['promotion_uuid'] = $promoiton_sub_promotion->promotion_uuid;
            $claim_history['sub_promotion_uuid'] = $promoiton_sub_promotion->sub_promotion_uuid;
            $claim_history['ticket_header_uuid'] = $ticket_header_uuid;
            $claim_history['valid_qty'] = (int) $valid_total_qty;
            $claim_history['one_qty_amount'] = $one_qty_amount;
            $claim_history['invoice_check_type'] = $promoiton_sub_promotion->invoice_check_type;
            $claim_history['prize_check_type'] = $promoiton_sub_promotion->prize_check_type;
            $claim_history['promotion_sub_promotion_id']        = $promoiton_sub_promotion->id;
            if ($one_claim_history) {
                if ($one_claim_history->choose_status != 2 && $one_claim_history->remain_choose_qty == $one_claim_history->valid_qty) {
                    $aa[] = $one_claim_history;
                    $claim_history['remain_choose_qty'] = (int) $valid_total_qty;
                    $claim_history['choose_status'] = 1;
                } else {
                    $claim_history['remain_choose_qty'] = (int) $one_claim_history->remain_choose_qty;
                    $claim_history['choose_status'] = 2;
                }
                //update
                $one_claim_history->update($claim_history);
            } else {
                $claim_history['remain_choose_qty'] = (int) $valid_total_qty;
                $claim_history['choose_status'] = 1;
                //create
                $claim_history['uuid'] = (string) Str::uuid();
                ClaimHistory::create($claim_history);
            }

            $ticket_header_total +=$total_amount['totalprice'];
        }


        //Create Ticket Header uuid or not
        $ticket_header_no = $this->generate_ticket_header_no(date(now()), $branch_id);

       if($total_amount['gbh_customer_id'])
       {
        $ldcustomer = get_customer($total_amount['gbh_customer_id'],$branch_id);
       }
       else{
        $ldcustomer = get_customer('10547',$branch_id);
       }
    //    dd($ldcustomer,'hi');
        $ticket_header = TicketHeader::create([
            'uuid' => $ticket_header_uuid,
            'ticket_header_no' => $ticket_header_no,
            'customer_uuid' => $ldcustomer->uuid,
            'created_at' => date(now()),
            'created_by' => Auth::user()->uuid,
            'status' => 1,
            'branch_id' => $branch_id,
            'total_valid_amount' => $ticket_header_total,
            'total_remain_amount' => $ticket_header_total,
        ]);
        // dd($ticket_header);
        //store in ticket_header_invoice
        $ticket_header_invoice = TicketHeaderInvoice::create([
            'uuid' => (string) Str::uuid(),
            'ticket_header_uuid' => $ticket_header_uuid,
            'invoice_id' => $total_amount['invoice_id'],
            'invoice_no' => $invoice_no,
            'valid_amount' => $ticket_header_total,
        ]);

        // dd($ticket_header_invoice);
        $data = [
            'ticket_header_uuid' => $ticket_header_uuid,
            'branch_id' => $branch_id,
            'customer' => $ldcustomer,
        ];

        //return ticket_header_uuid,
        return response()->json(['data' => $data], 200);
    }

    public static function generate_ticket_header_no($date, $branch_id)
    {
        try {
            $prefix = 'PRO';
            $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
            $dateStr = str_replace("/", "-", $date);
            $date = date('Y/m/d H:i:s', strtotime($dateStr));

            $prefix = $prefix . $branch_prefix;
            $last_id = TicketHeader::select('id', 'ticket_header_no')
                ->latest()->get()->take(1);
            if (isset($last_id[0]) == false) {
                return $ticket_header_no = $prefix . date('ymd-', strtotime($date)) . '0001';
            } else {

                $ticket_header_no = $last_id[0]->ticket_header_no;
                $ticket_header_no_arr = explode("-", $ticket_header_no);
                $old_ymd = substr($ticket_header_no_arr[0], -6);

                if ($old_ymd == date('ymd', strtotime($date))) {
                    $last_no = str_pad($ticket_header_no_arr[1] + 1, 4, 0, STR_PAD_LEFT);
                } else {
                    $last_no = '0001';
                }
                return $ticket_header_no = $prefix . date('ymd-', strtotime($date)) . $last_no;
            }
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("ticket_header.index"))
                ->with('error', 'Fail to generate Document No!');
        }
    }

}
