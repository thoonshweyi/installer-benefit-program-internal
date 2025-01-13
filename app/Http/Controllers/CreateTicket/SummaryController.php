<?php

namespace App\Http\Controllers\CreateTicket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerViewController;
use App\Models\Branch;
use App\Models\ClaimHistory;
use App\Models\ClaimHistoryDetail;
use App\Models\FixedPrizeAmountCheck;
use App\Models\IssuedPrize;
use App\Models\LuckyDraw;
use App\Models\LuckyDrawType;
use App\Models\Ticket;
use App\Models\TicketHeaderInvoice;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PDF as MPDF;

class SummaryController extends Controller
{
    public function summary($ticket_header_uuid)
    {
        $customer_data = new CustomerViewController;
        $customer_data->store_customer_view();

        $promotions = LuckyDraw::where('status', 1)->with('promotion_sub_promotions')
            ->with(['claim_histories' => function ($query) use ($ticket_header_uuid) {
                $query->where('ticket_header_uuid', $ticket_header_uuid)
                    ->where('claim_status', 2)
                    ->orderby('created_at', 'ASC');
            }])->get();
        foreach ($promotions as $promotion) {
            if ($promotion->claim_histories->count() > 0) {
                foreach ($promotion->claim_histories as $claim_history) {
                    //Check Prize Check Type
                    if ($claim_history->print_status == 1 || $claim_history->print_status == null) {
                        // For By Ticket and By Fixed Amount
                        if ($claim_history->prize_check_type == 1) {
                            //Find Valid Qty
                            $claimed_qty = $claim_history->valid_qty - $claim_history->remain_claim_qty - $claim_history->remain_choose_qty;
                            //Create Ticket
                            if ($claimed_qty > 0) {
                                $luckydraw = LuckyDraw::where('uuid', $claim_history->promotion_uuid)->first();
                                $words = explode(" ", $luckydraw->name);
                                $prefix = "";

                                foreach ($words as $w) {
                                    $prefix .= $w[0];
                                }
                                $tickets = Ticket::where('ticket_header_uuid', $claim_history->ticket_header_uuid)->first();
                                if (!isset($tickets)) {
                                    $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $claim_history->ticket_header->branch_id)->first()->branch_short_name;
                                    $prefix = $prefix . $branch_prefix;
                                    $last_id = Ticket::select('id', 'ticket_no')->where('promotion_uuid', $claim_history->promotion_uuid)
                                        ->latest('id')->first();

                                    if ($last_id == null) {
                                        $ticket_no = $prefix . '-' . '00001';
                                    } else {
                                        $ticket_no = $last_id['ticket_no'];
                                        $ticket_no_arr = explode("-", $ticket_no);
                                        $last_no = str_pad($ticket_no_arr[1] + 1, 5, 0, STR_PAD_LEFT);
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
                                        $last_no = str_pad($ticket_no_arr[1] + 1, 5, 0, STR_PAD_LEFT);
                                        $ticket_no = $prefix . '-' . $last_no;
                                    }
                                }
                                //Generate PDF
                                $tickets = Ticket::where('ticket_header_uuid', $claim_history->ticket_header_uuid)
                                    ->where('claim_history_uuid', $claim_history->uuid)->get()->pluck('ticket_no')->toarray();
                                //Generate PDF
                                $first = reset($tickets);
                                $last = end($tickets);

                                $customer = $claim_history->ticket_header->customers;
                                $titlename = $customer->titlename ?? '';
                                $firstname = $customer->firstname ?? '';
                                $lastname = $customer->lastname ?? '';
                                $nrc_number_name = $customer->NRCNumbers->nrc_number_name ?? '';
                                $district = $customer->NRCNames->district ?? '';
                                $shortname = $customer->NRCNaings->shortname ?? '';
                                $nrc_number = $customer->nrc_number ?? '';

                                $currentURL = URL::current();
                                if (str_contains($currentURL, '192.168.21.242') || str_contains($currentURL,'tpluckydraw')) {
                                    $amphur_name = $customer->POS102amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS102provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.25.242') || str_contains($currentURL,'tpwluckydraw')) {
                                    $amphur_name = $customer->POS106amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS106provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.3.242') || str_contains($currentURL,'ltluckydraw')
                                // ||str_contains($currentURL, '192.168.2.23')
                                 || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
                                    $amphur_name = $customer->POS101amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS101provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.11.242') || str_contains($currentURL,'ssluckydraw')
                                // ||str_contains($currentURL, '192.168.2.23')
                                ) {
                                    $amphur_name = $customer->POS103amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS103provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.16.242') || str_contains($currentURL,'edluckydraw')
                                ||str_contains($currentURL, '192.168.2.23')) {
                                    $amphur_name = $customer->POS104amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS104provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.36.242') || str_contains($currentURL,'htyluckydraw')) {
                                    $amphur_name = $customer->POS107amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS107provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.31.242') || str_contains($currentURL,'mlmluckydraw')) {
                                    $amphur_name = $customer->POS105amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS105provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.41.242') || str_contains($currentURL,'atyluckydraw')) {
                                    $amphur_name = $customer->POS108amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS108provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.46.242') || str_contains($currentURL,'tmluckydraw')) {
                                    $amphur_name = $customer->POS112amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS112provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.51.243') || str_contains($currentURL,'sdgluckydraw')) {
                                    $amphur_name = $customer->POS113amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS113provinces->province_name ?? '';
                                }
                                if (str_contains($currentURL, '192.168.61.242') || str_contains($currentURL,'bagoluckydraw')) {
                                    $amphur_name = $customer->POS110amphurs->amphur_name ?? '';
                                    $province_name = $customer->POS110provinces->province_name ?? '';
                                }
                                $customer_name = $titlename . ' ' . $firstname . ' ' . $lastname;
                                if ($nrc_number_name == '' || $district == '' || $shortname == '' || $nrc_number == '') {
                                    $customer_nrc = '';
                                } else {
                                    $customer_nrc = $nrc_number_name . $district . $shortname . $nrc_number;
                                }

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

                            } else {
                                // dd('Error');
                            }

                        }
                        // For By Grab the Chance
                        if ($claim_history->prize_check_type == 2) {

                            //Find Claimed Product in Claim History Detail
                            $claim_history_details = ClaimHistoryDetail::select(
                                'claim_histories.ticket_header_uuid as ticket_header_uuid',
                                'prize_items.uuid as prize_item_uuid',
                                'prize_items.type as prize_type','prize_items.name', 'prize_items.gp_code', DB::raw('count(*) as total'), 'claim_history_details.price_cc_check_uuid',
                                'claim_history_details.uuid as claim_history_detail_uuid'
                                )
                                ->where('claim_history_details.claim_history_uuid', $claim_history->uuid)
                                ->join('prize_items', 'prize_items.uuid', 'claim_history_details.prize_item_uuid')
                                ->join('claim_histories', 'claim_histories.uuid','claim_history_details.claim_history_uuid')
                                ->groupby('prize_items.name', 'prize_items.gp_code', 'claim_history_details.price_cc_check_uuid','prize_type',
                                'claim_histories.ticket_header_uuid','prize_items.uuid','claim_history_details.uuid')
                                ->get();

                            $data = [];
                            foreach ($claim_history_details as $claim_history_detail) {
                                $series_no = IssuedPrize::where('issued_prizes.ticket_header_uuid',$claim_history_detail->ticket_header_uuid)
                                    ->leftjoin('prize_items','prize_items.gp_code','issued_prizes.prize_code')
                                    ->select('issued_prizes.serial_no','issued_prizes.prize_code')
                                    ->where('issued_prizes.prize_code',$claim_history_detail->gp_code)
                                    ->first();
                                if($series_no){
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
                            for ($i = 0; $i < $claim_qty; $i++) {
                                $data[] = [
                                    "ticket_date" => date('d/m/Y'),
                                    "series_no" => $gp_code[$i],
                                    'expire_date' => $final,
                                    'gp_code' => $type->fixed_prize_gp_code,
                                ];
                            }
                            if ($type->fixed_prize_type == 1) {
                                $pdf = MPDF::loadView('tickets.gold_ring_ticket', compact('data'), [], [
                                    'title' => 'Certificate',
                                    'format' => 'A6',
                                    'orientation' => 'L',
                                    'margin_left' => 5,
                                    'margin_right' => 5,
                                    'margin_top' => 5,
                                    'margin_bottom' => 5,
                                ]);
                            } else {
                                $pdf = MPDF::loadView('tickets.gold_coupon_ticket', compact('data'), [], [
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
            }
        }
        $promotion_types = LuckyDrawType::where('status', 1)
            ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
                $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
                    $query2->where('ticket_header_uuid', $ticket_header_uuid)
                        ->where('claim_status', 2)
                        ->orderby('created_at', 'ASC');
                }])
                    ->where('status', 1);
            }])
            ->get();
        return view('create_tickets.summary', compact('ticket_header_uuid', 'promotion_types'));
    }

    public function new_remove_ticket_file(Request $request)
    {
        // try {
        $uuid = $request->claim_history_uuid;
        $claim_history = ClaimHistory::where('uuid', $uuid)->first();
        $claim_history->update([
            'printed_at' => date('Y-m-d H:i:s'),
            // 'print_status' => 2,
        ]);
        $filename = $uuid . '.pdf';
        File::delete(public_path('tickets/' . $filename));
        $ticket_header_uuid = $claim_history->ticket_header_uuid;

        $promotion_types = LuckyDrawType::where('status', 1)
            ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
                $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
                    $query2->where('ticket_header_uuid', $ticket_header_uuid)
                        ->orderby('created_at', 'ASC');
                }])
                    ->where('status', 1);
            }])
            ->get();
        return view('create_tickets.summary', compact('ticket_header_uuid', 'promotion_types'));
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function search_claim_history_product_detail($claim_history_uuid)
    {
        $claim_history = ClaimHistory::where('uuid', $claim_history_uuid)
            ->where('claim_status', '2')->first();
        if ($claim_history) {
            if ($claim_history->prize_check_type == 1) {
                $claim_history_detail[] = [
                    'name' => 'ticket',
                    'qty' => $claim_history->valid_qty - $claim_history->remain_claim_qty,
                ];
            }
            if ($claim_history->prize_check_type == 2) {
                $claim_history_detail = ClaimHistoryDetail::select('prize_items.name', DB::raw('count(*) as qty'))
                    ->where('claim_history_details.claim_history_uuid', $claim_history->uuid)
                    ->join('prize_items', 'prize_items.uuid', 'claim_history_details.prize_item_uuid')
                    ->groupby('prize_items.name')
                    ->get()->toarray();
            }
            if ($claim_history->prize_check_type == 3) {
                $name = FixedPrizeAmountCheck::where('promotion_uuid', $claim_history->promotion_uuid)->where('sub_promotion_uuid', $claim_history->sub_promotion_uuid)->first()->fixed_prize_name;
                $claim_history_detail[] = [
                    'name' => $name,
                    'qty' => $claim_history->valid_qty - $claim_history->remain_claim_qty,
                ];
            }
        } else {
            $claim_history_detail = [
                [
                    'name' => '',
                    'qty' => '',
                ],
            ];
        }
        return $claim_history_detail;
    }

}
