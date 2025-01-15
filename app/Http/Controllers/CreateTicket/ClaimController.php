<?php

namespace App\Http\Controllers\CreateTicket;

use App\Models\Branch;
use App\Models\PrizeItem;
use App\Models\IssuedPrize;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\PrizeCCCheck;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\LuckyDrawType;
use App\Models\PrizeCCBranch;
use App\Models\CCWinningChance;
use App\Models\ClaimHistoryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FixedPrizeAmountCheck;
use App\Http\Controllers\CustomerViewController;

class ClaimController extends Controller
{
    public function claim_prize($ticket_header_uuid)
    {
        //store customer view route////
        $customer_data = new CustomerViewController;
        $customer_data->store_customer_view();

        $promotion_types = LuckyDrawType::where('status', 1)
            ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
                $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
                    $query2->where('ticket_header_uuid', $ticket_header_uuid)
                        ->orderby('created_at', 'ASC');
                }])
                    ->where('status', 1);
            }])
            ->get();

        $branch_id = get_current_branch_id();
        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        return view('create_tickets.claim-prizes', compact('ticket_header','ticket_header_uuid', 'promotion_types', 'branch_id'));
    }

    public function update_claim_record(Request $request)
    {
        try{
            $ticket_header_uuid = $request->ticket_header_uuid;
            $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        
            //Update Remain Qty and Choose Status
            if ($request->choose_qty != 0) {
                $u_claim_history = ClaimHistory::where('uuid', $request->uuid)->first();
                if ($u_claim_history->claim_status == 2) {
                    return redirect()->back()->with('error', 'It is Claimed, Can not Change Choose Qty');
                }
                $choose_qty = (int) $request->choose_qty;
                $remain_qty = (int) $u_claim_history->remain_claim_qty - $choose_qty;
                if ($remain_qty < 0) {
                  
                    if ($choose_qty <= $u_claim_history->valid_qty) {
                        $remain_qty = (int) $u_claim_history->valid_qty - $choose_qty;
                    }
                }
                if ($remain_qty >= 0) {
                    $update_claime_history['remain_choose_qty'] = (int) $remain_qty;
                    //check old choose
                    $update_claime_history['choose_qty'] = $u_claim_history->choose_qty + (int) $choose_qty;

                    $update_claime_history['remain_claim_qty'] = $u_claim_history->valid_qty - $remain_qty;
                    // $update_claime_history['remain_claim_qty'] =  $u_claim_history->choose_qty + (int) $choose_qty;
                    DB::beginTransaction();
                    $update_claime_history['choose_status'] = 2;
                    $u_claim_history->update($update_claime_history);

                    //Find Reduce Amount
                    $reduce_amount = $choose_qty * $u_claim_history->one_qty_amount;

                    //update total remain amount
                    $new_remain_amount = $ticket_header->total_remain_amount - $reduce_amount;
                    $ticket_header->update(
                        ['total_remain_amount' => $new_remain_amount]
                    );
                    //Update Other
                    $other_claim_histories = ClaimHistory::where('uuid', '!=', $request->uuid)->where('promotion_uuid', $u_claim_history->promotion_uuid)
                        ->where('ticket_header_uuid', $ticket_header_uuid)
                        ->whereHas('promotion', function ($q) {
                            $q->where('lucky_draw_type_uuid', '=', '310ea3a5-2af6-40e6-96ba-d1d90e12d48d');
                        })
                        ->where('valid_qty', '!=', 0)
                        ->get();

                    foreach ($other_claim_histories as $other_claim_history) {
                        //find reduce qty
                        if ($other_claim_history->invoice_check_type == 1) {

                            $total_reduce_amount = $ticket_header->total_remain_amount;
                            $other_new_qty = $total_reduce_amount / $other_claim_history->one_qty_amount;
                            if ($other_new_qty <= 0) {
                                $other_new_qty = 0;
                            }
                            $update_other_claim_history['remain_choose_qty'] = (int) $other_new_qty;
                            // update remain claim qty
                            if ($other_new_qty == 0) {
                                $new_remain_claim_qty = 0;
                            } else {
                                if ($other_claim_history->remain_claim_qty == null || $other_claim_history->remain_claim_qty == 0) {
                                    $new_remain_claim_qty = $other_claim_history->remain_choose_qty - $other_new_qty;
                                } else {
                                    $new_remain_claim_qty = $other_claim_history->remain_claim_qty - $other_new_qty;
                                }
                            }
                            $update_other_claim_history['remain_claim_qty'] = (int) $new_remain_claim_qty;
                            $other_claim_history->update($update_other_claim_history);
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('tickets.choose_promotion', $request->ticket_header_uuid);
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to Claim!');
        }
    }

    public function claim_ticket(Request $request, $ticket_header_uuid, $claim_history_uuid)
    {
        $claim_history = ClaimHistory::where('uuid', $claim_history_uuid)->first();
        $update_claime_history['claim_status'] = 2;
        $update_claime_history['remain_claim_qty'] = 0;
        $update_claime_history['claimed_at'] = date('Y-m-d H:i:s');
        $claim_history->update($update_claime_history);

        return redirect()->route('tickets.claim_prize', $ticket_header_uuid)->with('success', 'Claimed Prize!');
    }

    public function get_price_cc_products(Request $request)
    {
        $claim_history = ClaimHistory::where('uuid', $request->uuid)->first();
        $prizeCCCheck = PrizeCCCheck::select('uuid', 'ticket_image')->where('promotion_uuid', $claim_history->promotion_uuid)->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->orderby('id', 'DESC')->get()->toArray();
        $claim_history_detail = ClaimHistoryDetail::select('prize_items.name', DB::raw('count(*) as total'))
            ->where('claim_history_details.claim_history_uuid', $claim_history->uuid)
            ->join('prize_items', 'prize_items.uuid', 'claim_history_details.prize_item_uuid')
            ->groupby('prize_items.name')
            ->get();
        return
            [
            'prizeCCCheck' => $prizeCCCheck,
            'claim_history' => $claim_history,
            'claim_history_detail' => $claim_history_detail,
        ];
    }

    public function get_winning_div(Request $request)
    {
        $claim_history = ClaimHistory::where('uuid', $request->uuid)->first();
        $prizeCCChecks = PrizeCCCheck::select('uuid', 'ticket_image')->where('promotion_uuid', $claim_history->promotion_uuid)->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->orderby('id', 'DESC')->get()->toArray();
        $branch_id = $claim_history->ticket_header->branch_id;
        //Get Amount for Minimal Amount
        $minimum_amounts = CCWinningChance::select('minimum_amount')->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->where('promotion_uuid', $claim_history->promotion_uuid)
            ->orderBy('minimum_amount', 'ASC')->groupBy('minimum_amount')->get()->toArray();

        //Find Suitable Minimal Amount
        $total_valid_amount = TicketHeader::where('uuid', $claim_history->ticket_header_uuid)->first()->total_valid_amount;
        if ($minimum_amounts) {
            $suitable_minimal_amount = $minimum_amounts[0]['minimum_amount'];
            foreach ($minimum_amounts as $minimum_amount) {
                if ($minimum_amount['minimum_amount'] < $total_valid_amount) {
                    $suitable_minimal_amount = $minimum_amount['minimum_amount'];
                }
            }
        } else {
            $suitable_minimal_amount = null;
        }
        $randomArray = [];
        foreach ($prizeCCChecks as $prizeCCCheck) {
            if ($suitable_minimal_amount) {
                $getWinningChance = CCWinningChance::where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->where('prize_cc_check_uuid', $prizeCCCheck['uuid'])
                    ->where('minimum_amount', $suitable_minimal_amount)
                    ->where('branch_id', $branch_id)
                    ->first();
                if (!$getWinningChance) {
                    $getWinningChance = (int) count($prizeCCChecks) / 100;
                } else {
                    $getWinningChance = $getWinningChance->winning_percentage;
                }

            } else {
                $getWinningChance = (int) count($prizeCCChecks) / 100;
            }
            //Save in RandomArray
            $times = $getWinningChance / 10;
            for ($i = 0; $i < $times; $i++) {
                array_push($randomArray, $prizeCCCheck['uuid']);
            }
        }
        $arrayCount = count($randomArray);
        $randomUuid = $this->FindRandomUUID($arrayCount, $randomArray, $branch_id);
        if ($randomUuid == 'all_prize_qty_is_low') {
            return 'all_prize_qty_is_low';
        }
        return $randomUuid;
    }

    public function get_winning_div_by_all_start(Request $request)
    {
        if($request->remain_qty > 0){
            for($i = 0; $i < (int)$request->remain_qty; $i++){
                $final_one =  $this->get_winning_div($request);
                $request['price_cc_check_uuid']= $final_one;
                $status = $this->save_claim_detail($request);
            }
            return [
                'winning_number' => $final_one,
                'detail' => $status];
        }
    }
    public function FindRandomUUID($arrayCount, $randomArray, $branch_id)
    {
        if (count($randomArray) > 0) {
            $randomUuid = $randomArray[array_rand($randomArray)];
            // //remove randomuuid from array
            if (($key = array_search($randomUuid, $randomArray)) !== false) {
                unset($randomArray[$key]);
            }

            $prizeCCBranch = PrizeCCBranch::where('prize_c_c_uuid', $randomUuid)->where('branch_id', $branch_id)->first();
            if ($arrayCount > 0) {
                if ($prizeCCBranch->remain_qty <= 0) {
                    $arrayCount--;
                    $randomUuid = $this->FindRandomUUID($arrayCount, $randomArray, $branch_id);
                }
            } else {
                if (count($randomArray) == 0) {
                    return 'all_prize_qty_is_low';
                }
            }
        } else {
            return 'all_prize_qty_is_low';
        }
        return $randomUuid;
    }

    public function save_claim_detail(Request $request)
    {
        try{
            $claim_history = ClaimHistory::where('uuid', $request->claim_history_uuid)->first();
            $prizeCCBranch = PrizeCCBranch::where('prize_c_c_uuid', $request->price_cc_check_uuid)->where('branch_id', $request->branch_id)->first();
            $prizeCCCheck = PrizeCCCheck::where('uuid', $request->price_cc_check_uuid)->first();
            //insert in Claim History Detail
            $serial_no = $this->generate_grab_chance_serial_no($claim_history->promotion_uuid, $claim_history->sub_promotion_uuid, $request->branch_id);
            DB::beginTransaction();

            $claim_history_detail['uuid'] = (string) Str::uuid();
            $claim_history_detail['claim_history_uuid'] = $request->claim_history_uuid;
            $claim_history_detail['times'] = ($claim_history->valid_qty - $request->remain_claim_qty) + 1;
            $claim_history_detail['price_cc_check_uuid'] = $request->price_cc_check_uuid;
            $claim_history_detail['prize_item_uuid'] = $prizeCCCheck->prizeItem->uuid;
            $claim_history_detail['serial_no'] = $serial_no;

            ClaimHistoryDetail::create($claim_history_detail);
            //Update in Claim History
            if ($claim_history->remain_claim_qty != null) {
                $update_claime_history['remain_claim_qty'] = $claim_history->remain_claim_qty - 1;
                $update_claime_history['claimed_at'] = date(now());

                $claim_history->update($update_claime_history);

                //reduce qty in Price CC Check
                $update_price_cc_branch['qty'] = $prizeCCBranch->remain_qty - 1;
                $prizeCCBranch->update($update_price_cc_branch);

                /////store issued///
                //claim history, prize_check_type 2 for gtc 3 for fixd, prize_code;
                $this->store_issued_prize($claim_history, 2, $prizeCCCheck->prizeItem->gp_code,$serial_no);

                //Claim History
                $claim_history_detail = ClaimHistoryDetail::
                    select('prize_items.name', DB::raw('count(*) as total'))
                    ->where('claim_history_details.claim_history_uuid', $claim_history->uuid)
                    ->join('prize_items', 'prize_items.uuid', 'claim_history_details.prize_item_uuid')
                    ->groupby('prize_items.name')
                    ->get();
                if ($claim_history->remain_claim_qty == 0) {
                    $update_claime_history['claim_status'] = 2;
                    $claim_history->update($update_claime_history);
                }
                DB::commit();
                return [
                    'remain_times' => $claim_history->remain_claim_qty,
                    'claimed_name' => $prizeCCCheck->prizeItem->name,
                    'claim_history_detail' => $claim_history_detail,
                ];
            } else {
                DB::commit();
                return [
                    'remain_times' => 0,
                    'claimed_name' => [],
                    'claim_history_detail' => [],
                ];
            }
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to Claim!');
        }
    }

    public function claim_fixed_amount(Request $request, $ticket_header_uuid, $claim_history_uuid)
    {

        $claim_history = ClaimHistory::where('uuid', $claim_history_uuid)->first();
        $update_claime_history['claim_status'] = 2;
        $update_claime_history['remain_claim_qty'] = 0;
        $update_claime_history['claimed_at'] = date('Y-m-d H:i:s');
        $claim_history->update($update_claime_history);
        /////store issued///
        //claim history, prize_check_type 2 for gtc 3 for fixd
        $this->store_issued_prize($claim_history, 3);
        return redirect()->route('tickets.claim_prize', $ticket_header_uuid)->with('success', 'Claimed Prize!');
    }

    public function store_issued_prize($claim_history, $prize_check_type, $prize_code = null, $serial_no = null)
    {
        //Prize Type 1 for Gold Ring 2 for Gold Coin//////
        $ticket_header = TicketHeader::where('uuid', $claim_history->ticket_header_uuid)->first();
        if ($prize_check_type == 3) {
            $prize = FixedPrizeAmountCheck::where('promotion_uuid', $claim_history->promotion_uuid)
                ->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->first();
            $prize_code = $prize->fixed_prize_gp_code;
            $prize_type = $prize->fixed_prize_type;
            $prize_qty = $claim_history->valid_qty - $claim_history->remain_choose_qty;

            for ($i = 0; $i < $prize_qty; $i++) {
                $issued_prize['uuid'] = (string) Str::uuid();
                $issued_prize['branch_id'] = $claim_history->ticket_header->branch_id;
                $issued_prize['prize_date'] = date('Y-m-d H:i:s');
                $issued_prize['prize_code'] = $prize_code; ///prize_check_type
                $issued_prize['customer_uuid'] = $ticket_header->customer_uuid;
                $issued_prize['prize_qty'] = $prize_qty;
                $issued_prize['prize_amount'] = $claim_history->one_qty_amount; ////one_qty_amount///claim history
                $issued_prize['ticket_header_uuid'] = $ticket_header->uuid;
                $issued_prize['promotion_uuid'] = $claim_history->promotion_uuid;
                $issued_prize['sub_promotion_uuid'] = $claim_history->sub_promotion_uuid;
                $issued_prize['sale_amount'] = $ticket_header->total_valid_amount;
                $issued_prize['prize_type'] = $prize_type;
                $issued_prize['serial_no'] = $this->generate_fix_serial_no($claim_history->promotion_uuid, $claim_history->sub_promotion_uuid, $ticket_header->branch_id, $prize_type);

                IssuedPrize::create($issued_prize);
            }

        } else {
            $prize_type = '3'; // for cashcoupon;
            $prize_code = $prize_code;
            $prize_qty = 1;

            $issued_prize['uuid'] = (string) Str::uuid();
            $issued_prize['branch_id'] = $claim_history->ticket_header->branch_id;
            $issued_prize['prize_date'] = date('Y-m-d H:i:s');
            $issued_prize['prize_code'] = $prize_code; ///prize_check_type
            $issued_prize['customer_uuid'] = $ticket_header->customer_uuid;
            $issued_prize['prize_qty'] = $prize_qty;
            $issued_prize['prize_amount'] = $claim_history->one_qty_amount; ////one_qty_amount///claim history
            $issued_prize['ticket_header_uuid'] = $ticket_header->uuid;
            $issued_prize['promotion_uuid'] = $claim_history->promotion_uuid;
            $issued_prize['sub_promotion_uuid'] = $claim_history->sub_promotion_uuid;
            $issued_prize['sale_amount'] = $ticket_header->total_valid_amount;
            $issued_prize['prize_type'] = $prize_type;
            $issued_prize['serial_no'] = $serial_no;

            IssuedPrize::create($issued_prize);
        }


    }

    public static function generate_fix_serial_no($promotion_uuid, $sub_promotion_uuid, $branch_id, $prize_type)
    {
        // try {
        $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
        $dateStr = str_replace("/", "-", date('Y/m/d H:i:s'));
        $date = date('Y/m/d H:i:s', strtotime($dateStr));
        $last_id = IssuedPrize::select('id', 'serial_no')->where('prize_type', $prize_type)
            ->where('promotion_uuid', $promotion_uuid)->where('sub_promotion_uuid', $sub_promotion_uuid)
            ->where('branch_id', $branch_id)
            ->orderBy('id', 'DESC')->latest()->get()->take(1);

        if (isset($last_id[0]) == false) {
            return $doc_no = $branch_prefix . date('ymd-', strtotime($date)) . '0001';
        } else {
            $serial_no = $last_id[0]->serial_no;
            $serial_no_arr = explode("-", $serial_no);
            $old_ymd = substr($serial_no_arr[0], -6);
            if ($old_ymd == date('ymd', strtotime($date))) {
                $last_no = str_pad($serial_no_arr[1] + 1, 4, 0, STR_PAD_LEFT);

            } else {
                $last_no = '0001';
            }
            return $doc_no = $branch_prefix . date('ymd-', strtotime($date)) . $last_no;
        }
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("documents.index"))
        //         ->with('error', 'Fail to generate Document No!');
        // }
    }

    public static function generate_grab_chance_serial_no($promotion_uuid, $sub_promotion_uuid, $branch_id)
    {
        try {
            $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;

            $dateStr = str_replace("/", "-", date('Y/m/d H:i:s'));
            $date = date('Y/m/d H:i:s', strtotime($dateStr));
            //Claim History Detail

            $last_id = IssuedPrize::select('id', 'serial_no')->where('prize_type', 3)
            ->where('promotion_uuid', $promotion_uuid)->where('sub_promotion_uuid', $sub_promotion_uuid)
            ->where('branch_id', $branch_id)
            ->orderBy('id', 'DESC')->latest()->get()->take(1);
            if (isset($last_id[0]) == false) {
                return $doc_no = $branch_prefix . date('ymd-', strtotime($date)) . '0001';
            } else {
                $serial_no = $last_id[0]->serial_no;
                $serial_no_arr = explode("-", $serial_no);
                $old_ymd = substr($serial_no_arr[0], -6);

                if ($old_ymd == date('ymd', strtotime($date))) {
                    $last_no = str_pad($serial_no_arr[1] + 1, 4, 0, STR_PAD_LEFT);
                } else {
                    $last_no = '0001';
                }
                return $doc_no = $branch_prefix . date('ymd-', strtotime($date)) . $last_no;
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to generate Document No!');
        }
    }

}
