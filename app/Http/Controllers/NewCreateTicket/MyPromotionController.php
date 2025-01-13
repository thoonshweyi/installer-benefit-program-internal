<?php

namespace App\Http\Controllers\NewCreateTicket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerViewController;
use App\Models\Branch;
use App\Models\CCWinningChance;
use App\Models\ClaimHistory;
use App\Models\ClaimHistoryDetail;
use App\Models\FixedPrizeAmountCheck;
use App\Models\IssuedPrize;
use App\Models\LuckyDrawType;
use App\Models\PrizeCCBranch;
use App\Models\PrizeCCCheck;
use App\Models\PrizeItem;
use App\Models\Ticket;
use App\Models\TicketHeader;
use App\Models\TicketHeaderInvoice;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PDF as MPDF;

class MyPromotionController extends Controller
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
        return view('create_tickets.claim-prizes', compact('ticket_header', 'ticket_header_uuid', 'promotion_types', 'branch_id'));
    }

    public function update_claim_record(Request $request)
    {
        // try{
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

                // $update_claime_history['remain_claim_qty'] = $u_claim_history->valid_qty - $remain_qty;
                $update_claime_history['remain_claim_qty'] = $u_claim_history->choose_qty + (int) $choose_qty;
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
        return redirect()->route('invoices', $ticket_header_uuid . '#available_promotions');
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     DB::rollBack();
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to Claim!');
        // }
    }

    public function claim_ticket(Request $request, $ticket_header_uuid, $claim_history_uuid)
    {
        $claim_history = ClaimHistory::where('uuid', $claim_history_uuid)->first();
        $update_claime_history['claim_status'] = 2;
        $update_claime_history['remain_claim_qty'] = 0;
        $update_claime_history['claimed_at'] = date('Y-m-d H:i:s');
        $claim_history->update($update_claime_history);

        $this->create_pdf($claim_history);

        return redirect()->route('invoices', $ticket_header_uuid . '#my_promotions');
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
            'promotion_uuid' => $claim_history->promotion_uuid,
            'sub_promotion_uuid' => $claim_history->sub_promotion_uuid,
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
        if ($request->remain_qty > 0) {
            for ($i = 0; $i < (int) $request->remain_qty; $i++) {
                $final_one = $this->get_winning_div($request);
                $request['price_cc_check_uuid'] = $final_one;
                $status = $this->save_claim_detail($request);
            }
            return [
                'winning_number' => $final_one,
                'detail' => $status,
            ];
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
        // try{
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
            $update_price_cc_branch['remain_qty'] = $prizeCCBranch->remain_qty - 1;
            $prizeCCBranch->update($update_price_cc_branch);

            /////store issued///
            //claim history, prize_check_type 2 for gtc 3 for fixd, prize_code;
            $this->store_issued_prize($claim_history, 2, $prizeCCCheck->prizeItem->gp_code, $serial_no);

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

            //Generate Ticket Pdf
            $this->create_pdf($claim_history);

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
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     DB::rollBack();
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to Claim!');
        // }
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
        //Generate Ticket Pdf
        $this->create_pdf($claim_history);

        $data = [
            'ticket_header_uuid' => $ticket_header_uuid,
        ];
        //return ticket_header_uuid,
        return response()->json(['data' => $data], 200);
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
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to generate Document No!');
        }
    }

    public function create_pdf($claim_history)
    {
        // For By Ticket and By Fixed Amount
        if ($claim_history->prize_check_type == 1) {
            //Find Valid Qty
            $claimed_qty = $claim_history->valid_qty - $claim_history->remain_claim_qty - $claim_history->remain_choose_qty;
            //Create Ticket
            if ($claimed_qty > 0) {
                // $luckydraw = LuckyDraw::where('uuid', $claim_history->promotion_uuid)->first();
                // $words = explode(" ", $luckydraw->name);
                // $prefix = "";

                // foreach ($words as $w) {
                //     $prefix .= $w[0];
                // }

                //Generate PDF
                $tickets = Ticket::where('ticket_header_uuid', $claim_history->ticket_header_uuid)
                ->where('claim_history_uuid', $claim_history->uuid)->pluck('ticket_no')->toarray();
                if (!isset($tickets) || count($tickets)==0) {
                    $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $claim_history->ticket_header->branch_id)->first()->branch_short_name;
                    $prefix = $branch_prefix . date('ymd');
                    $last_id = Ticket::select('id', 'ticket_no')->where('promotion_uuid', $claim_history->promotion_uuid)
                        ->latest('id')->first();

                    if ($last_id == null) {
                        $ticket_no = $prefix . '-' . '0001';
                    } else {
                        $ticket_no = $last_id['ticket_no'];
                        $ticket_no_arr = explode("-", $ticket_no);
                        $last_no = str_pad($ticket_no_arr[1] + 1, 4, 0, STR_PAD_LEFT);
                        $ticket_no = $prefix . '-' . $last_no;
                    }
                    for ($x = 0; $x < $claimed_qty; $x++) {
                        Ticket::create([
                            'uuid' => (string) Str::uuid(),
                            'ticket_no' => $ticket_no,
                            'ticket_header_uuid' => $claim_history->ticket_header->uuid,
                            'promotion_uuid' => $claim_history->promotion_uuid,
                            'claim_history_uuid' => $claim_history->uuid,
                        ]);
                        $ticket_no_arr = explode("-", $ticket_no);
                        $last_no = str_pad($ticket_no_arr[1] + 1, 4, 0, STR_PAD_LEFT);
                        $ticket_no = $prefix . '-' . $last_no;
                    }
                }
                //Generate PDF
                $tickets = Ticket::where('ticket_header_uuid', $claim_history->ticket_header_uuid)
                ->where('claim_history_uuid', $claim_history->uuid)->pluck('ticket_no')->toarray();
                //Generate PDF
                $first = reset($tickets);
                $last = end($tickets);

                $customer = $claim_history->ticket_header->customers;
                $titlename = $customer->titlename ?? '';
                $firstname = $customer->firstname ?? '';
                $lastname = $customer->lastname ?? '';
                // $nrc_number_name = $customer->NRCNumbers->nrc_number_name ?? '';
                // $district = $customer->NRCNames->district ?? '';
                // $shortname = $customer->NRCNaings->shortname ?? '';
                // $nrc_number = $customer->nrc_number ?? '';

                $currentURL = URL::current();
                $amphur_name = $customer->amphurs->amphur_name ?? '';
                $province_name = $customer->provinces->province_name ?? '';

                $customer_name = $titlename . ' ' . $firstname . ' ' . $lastname;
                $customer_nrc  = $customer->nrc;
                // if ($nrc_number_name == '' || $district == '' || $shortname == '' || $nrc_number == '') {
                //     $customer_nrc = '';
                // } else {
                //     $customer_nrc = $nrc_number_name . $district . $shortname . $nrc_number;
                // }
                // dd($customer);
                $customer_township = $amphur_name;
                $customer_region = $province_name;
                $invoice_nos = implode(", ", TicketHeaderInvoice::where('ticket_header_uuid', $claim_history->ticket_header_uuid)->get()->pluck('invoice_no')->toarray());

                $promotion_name = $claim_history->promotion->name;
                $promotion_uuid = $claim_history->promotion_uuid;
                if ($first == $last) {
                    $ticket_nos = $first;
                } else {
                    $ticket_nos = $first . ' to ' . $last;
                }
                $customers = [
                    "ticket_nos" => $ticket_nos,
                    'customer_name' => $customer_name,
                    'nrc' => $customer_nrc,
                    'phone_no' => $customer->phone_no,
                    'phone_no_2' => $customer->phone_no_2,
                    'invoice_no' => $invoice_nos,
                    'date' => date('d-m-Y'),
                    'promotion_name' => $promotion_name,
                ];
                $data = [];
                ini_set("pcre.backtrack_limit", "5000000");
                foreach ($tickets as $ticket) {
                    $data[] = [
                        "ticket_no" => $ticket,
                        'customer_name' => $customer_name,
                        'nrc' => $customer_nrc,
                        'phone_no' => $customer->phone_no,
                        'phone_no_2' => $customer->phone_no_2,
                        'township' => $customer_township,
                        'region' => $customer_region,
                        'invoice_no' => $invoice_nos,
                        'date' => date('d-m-Y'),
                        'promotion_name' => $promotion_name,
                    ];
                }
                // dd($customers);
                // $pdf = MPDF::loadView('tickets.tickets', compact('data', 'customers', 'promotion_uuid'));
                $pdf = MPDF::loadView('tickets.lucky_draw_ticket', compact('data', 'customers', 'promotion_uuid'), [], [], [
                    'title' => 'Certificate',
                    'format' => 'A6',
                    'orientation' => 'L',
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_top' => 5,
                    'margin_bottom' => 5,
                ]);

                $file = Storage::put('tickets/' . $claim_history->uuid . '.pdf', $pdf->output());
                File::move(storage_path('app/tickets/' . $claim_history->uuid . '.pdf'), public_path('tickets/' . $claim_history->uuid . '.pdf'));
                // dd($file);
            } else {
                return redirect()->intended(route("tickets.show"))->with('error', 'There is no claimed qty');
            }

        }
        // For By Grab the Chance
        if ($claim_history->prize_check_type == 2) {

            //Find Claimed Product in Claim History Detail
            $claim_history_details = ClaimHistoryDetail::select(
                'claim_histories.ticket_header_uuid as ticket_header_uuid',
                'prize_items.uuid as prize_item_uuid',
                'prize_items.type as prize_type', 'prize_items.name', 'prize_items.gp_code', DB::raw('count(*) as total'), 'claim_history_details.price_cc_check_uuid',
                'claim_history_details.uuid as claim_history_detail_uuid'
            )
                ->where('claim_history_details.claim_history_uuid', $claim_history->uuid)
                ->join('prize_items', 'prize_items.uuid', 'claim_history_details.prize_item_uuid')
                ->join('claim_histories', 'claim_histories.uuid', 'claim_history_details.claim_history_uuid')
                ->groupby('prize_items.name', 'prize_items.gp_code', 'claim_history_details.price_cc_check_uuid', 'prize_type',
                    'claim_histories.ticket_header_uuid', 'prize_items.uuid', 'claim_history_details.uuid')
                ->get();

            $data = [];
            foreach ($claim_history_details as $claim_history_detail) {
                $series_no = IssuedPrize::where('issued_prizes.ticket_header_uuid', $claim_history_detail->ticket_header_uuid)
                    ->leftjoin('prize_items', 'prize_items.gp_code', 'issued_prizes.prize_code')
                    ->select('issued_prizes.serial_no', 'issued_prizes.prize_code')
                    ->where('issued_prizes.prize_code', $claim_history_detail->gp_code)
                    ->first();
                if ($series_no) {
                    $series_no = $series_no->serial_no;
                } else {
                    $series_no = '0000';
                }
                for ($i = 0; $i < $claim_history_detail->total; $i++) {
                    $final = date("d/m/Y", strtotime("+1 month"));
                    $data[] = [
                        "name" => $claim_history_detail->name,
                        "gp_code" => $claim_history_detail->gp_code,
                        'price_cc_check_uuid' => $claim_history_detail->price_cc_check_uuid,
                        "ticket_date" => date('d/m/Y'),
                        "series_no" => $series_no,
                        "expire_date" => $final,
                        "image" => $claim_history_detail->prize_cc_check->ticket_image,
                        "promotion_uuid" => $claim_history_detail->prize_cc_check->promotion_uuid,
                        "sub_promotion_uuid" => $claim_history_detail->prize_cc_check->sub_promotion_uuid,
                        "prize_type" => $claim_history_detail->prize_type,
                    ];
                }

            }

            $pdf = MPDF::loadView('tickets.grand_the_chance_pdf', compact('data'), [], [
                'title' => 'Certificate',
                'format' => 'A6',
                'orientation' => 'L',
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
            ]);
            $file = Storage::put('tickets/' . $claim_history->uuid . '.pdf', $pdf->output());
            File::move(storage_path('app/tickets/' . $claim_history->uuid . '.pdf'), public_path('tickets/' . $claim_history->uuid . '.pdf'));
        }
        if ($claim_history->prize_check_type == 3) {
            $type = FixedPrizeAmountCheck::where('promotion_uuid', $claim_history->promotion_uuid)
                ->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->first();

            $gp_code = IssuedPrize::where('promotion_uuid', $claim_history->promotion_uuid)
                ->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->pluck('serial_no');

            $data = [];
            $claim_qty = ($claim_history->valid_qty - $claim_history->remain_claim_qty) * $type->fixed_prize_qty;
            $time = strtotime(date("d/m/Y"));
            $final = date("d/m/Y", strtotime("+1 month"));

            if ($type->fixed_prize_type == 1) {
                for ($i = 0; $i < $claim_qty; $i++) {
                    $data[] = [
                        "ticket_date" => date('d/m/Y'),
                        "series_no" => $gp_code[$i],
                        'expire_date' => $final,
                        'gp_code' => $type->fixed_prize_gp_code,
                    ];
                }
                $pdf = MPDF::loadView('tickets.gold_ring_ticket', compact('data'), [], [
                    'title' => 'Certificate',
                    'format' => 'A6',
                    'orientation' => 'L',
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_top' => 5,
                    'margin_bottom' => 5,
                ]);
            } else if ($type->fixed_prize_type == 2) {
                for ($i = 0; $i < $claim_qty; $i++) {
                    $data[] = [
                        "ticket_date" => date('d/m/Y'),
                        "series_no" => $gp_code[$i],
                        'expire_date' => $final,
                        'gp_code' => $type->fixed_prize_gp_code,
                    ];
                }
                $pdf = MPDF::loadView('tickets.gold_coupon_ticket', compact('data'), [], [
                    'title' => 'Certificate',
                    'format' => 'A6',
                    'orientation' => 'L',
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_top' => 5,
                    'margin_bottom' => 5,
                ]);
            } else {
                for ($i = 0; $i < $claim_qty; $i++) {
                    $data[] = [
                        "ticket_date" => date('d/m/Y'),
                        "series_no" => $gp_code[$i],
                        'expire_date' => $final,
                        'gp_code' => $type->fixed_prize_gp_code,
                        'image' => $type->uuid,
                        'name' => $type->fixed_prize_name,
                        'promotion_uuid' => $type->promotion_uuid,
                        'sub_promotion_uuid' => $type->sub_promotion_uuid,
                        'fixed_prize_name' => $type->fixed_prize_name,
                        'prize_type' => 2,
                    ];
                }
                $pdf = MPDF::loadView('tickets.other_fixed_amount', compact('data'), [], [
                    'title' => 'Certificate',
                    'format' => 'A6',
                    'orientation' => 'L',
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_top' => 5,
                    'margin_bottom' => 5,
                ]);
            }
            $file = Storage::put('tickets/' . $claim_history->uuid . '.pdf', $pdf->output());
            File::move(storage_path('app/tickets/' . $claim_history->uuid . '.pdf'), public_path('tickets/' . $claim_history->uuid . '.pdf'));
        }
    }

}
