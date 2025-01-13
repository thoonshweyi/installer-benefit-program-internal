<?php

namespace App\Http\Controllers\NewCreateTicket;

use App\Http\Controllers\Controller;
use App\Models\AmountCheck;
use App\Models\ClaimHistory;
use App\Models\Customer;
use App\Models\FixedPrizeAmountCheck;
use App\Models\POS101\{Pos101Amphur,Pos101GbhCustomer};
use App\Models\POS101\Pos101Province;
use App\Models\POS102\Pos102Amphur;
use App\Models\POS102\Pos102Province;
use App\Models\POS103\Pos103Amphur;
use App\Models\POS103\Pos103Province;
use App\Models\POS104\Pos104Amphur;
use App\Models\POS104\Pos104Province;
use App\Models\POS105\Pos105Amphur;
use App\Models\POS105\Pos105Province;
use App\Models\POS106\Pos106Amphur;
use App\Models\POS106\Pos106Province;
use App\Models\POS107\Pos107Amphur;
use App\Models\POS107\Pos107Province;
use App\Models\POS108\Pos108Amphur;
use App\Models\POS108\Pos108Province;
use App\Models\POS112\Pos112Amphur;
use App\Models\POS112\Pos112Province;
use App\Models\POS113\Pos113Amphur;
use App\Models\POS113\Pos113Province;
use App\Models\POS114\Pos114Amphur;
use App\Models\POS114\Pos114Province;
use App\Models\POS110\Pos110Amphur;
use App\Models\POS110\Pos110Province;
use App\Models\PromotionSubPromotion;
use App\Models\PromotionSubTicketHeader;
use App\Models\Ticket;
use App\Models\TicketHeader;
use App\Models\TicketHeaderInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\{Province,Amphur, NRC,NRCName};

class InvoicesController extends Controller
{
    public function invoices($ticket_header_uuid)
    {
        $ticket_header      = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        $customer           = Customer::where('uuid', $ticket_header->customer_uuid)->first();
        $gbh_customer       = Pos101GbhCustomer::where('customer_barcode', $customer->customer_no)->first();
        $nrc_name           = $gbh_customer!=null?$gbh_customer->nrc_name:'';
        $branch_id          = $ticket_header->branch_id;
        $provinces          = Province::get();
        $nrc_state          = $nrc_name!=null && (NRC::where('nrc_id',$nrc_name)->first()!=null)? NRC::where('nrc_id',$nrc_name)->first()->state_id:$customer->nrc_no;
        $data               = NRCName::where('nrc_number_id',$nrc_state)->get();
        // dd($data);
        $amphurs            = Amphur::where('province_id', $customer->province_id)->get();

        return view('new_create_tickets.layout', compact('ticket_header_uuid', 'customer', 'branch_id', 'provinces', 'amphurs','data','gbh_customer'));
    }

    public function new_result_for_collect_invoice(Request $request)
    {
        $ticket_header_uuid = (!empty($_GET["ticket_header_uuid"])) ? ($_GET["ticket_header_uuid"]) : ('');
        if ($ticket_header_uuid !== '') {
            $result = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header_uuid)->get();
            // dd($result);
        } else {
            $result = [];
        }

        return DataTables::of($result)
            ->editColumn('valid_amount', function ($data) {
                if (isset($data->valid_amount)) {
                    return number_format($data->valid_amount);
                }
                return '';
            })
            ->editColumn('status', function ($data)
            {
                $invoice = PromotionSubTicketHeader::where('invoice_no',$data->invoice_no)->first();
                // dd($invoice);
                $status = $invoice? $invoice->status: '';
                return $status;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function check_customer(Request $request)
    {
        //Find customer
        $invoice_no = $request->invoice_no;
        $branch_id = findBrandId($invoice_no);
        if (!$branch_id) {
            return response()->json(['error' => 'invoice_is_not_format'], 200);
        }
        // $db_ext = getConnection($branch_id);
        $db_ext = DB::connection('pos_pgsql');

        //Find in Sale Cash Document
        $CustomerData = $db_ext->select("
        select gbh_customer_id from sale_cash.sale_cash_document where sale_cash_document_no= '$invoice_no'
        ");

        if (!$CustomerData) {
            $CustomerData = $db_ext->select("
            select gbh_customer_id from sale_cash.sale_cash_document where sale_cash_document_no= '$invoice_no'
            ");
            if (!$CustomerData) {
                return response()->json(['error' => 'invoice_is_not_found'], 200);
            }
        }

        $gbh_customer_id = $CustomerData[0]->gbh_customer_id;

        $gbh_customer = get_customer($gbh_customer_id, $branch_id);

        $data = [
            'gbh_customer_id' => $gbh_customer_id,
            'new_user_name' => $gbh_customer->firstname,
            'new_phone_no' => $gbh_customer->phone_no,
        ];

        //return ticket_header_uuid,
        return response()->json(['data' => $data], 200);
    }

    public function add_more_invoice_old(Request $request)
    {
        // dd($request->all());
        $invoice_no = $request->invoice_no;
        $ticket_header_uuid = $request->ticket_header_uuid;
        $gbh_customer_id = $request->gbh_customer_id;

        //check valid ticket header uuid
        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->with('customers')->first();
        if (!isset($ticket_header)) {
            return response()->json(['error' => 'ticket_header_uuid_error'], 200);
        }
        $tickets = Ticket::where('ticket_header_uuid', $ticket_header->uuid)->first();
        if (isset($tickets)) {
            return response()->json(['error' => 'can_not_add_invoice_when_ticket_is_generated'], 200);
        }
        $claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_uuid)->where('choose_status', 2)->first();
        if ($claim_history) {
            return response()->json(['error' => 'can_not_add_invoice_when_ticket_is_generated'], 200);
        }

        $ticket_header_invoices = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header->uuid)->get();
        if ($ticket_header_invoices->count() == 10 || $ticket_header_invoices->count() > 10) {
            return response()->json(['error' => 'accept_adding_only_10_invoices'], 200);
        }

        //check used invoice
        $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)
            ->whereHas('ticket_headers', function ($q) {
                $q->where('status', '=', 1);
            })->first();

        if (isset($ticket_header_invoice)) {
            return response()->json(['error' => 'invoice_is_used'], 200);
        }

        //chcek 10 invoices
        $ticket_header_invoice_count = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header_uuid)->count();
        if ($ticket_header_invoice_count > 10) {
            return response()->json(['error' => 'cannot_more_than_10_invoices'], 200);
        }

        //Find Sub Promoiton if(status==1)=>'active', else => 'inactive'
        $promoiton_sub_promotions = PromotionSubPromotion::whereHas('promotion', function ($q) {
            $q->where('status', '=', 1);
        })->where('deleted_at', null)->with('promotion')->get();
        // dd($invoice_no);
        $branch_id = findBrandId($invoice_no);
        if (!$branch_id) {
            return response()->json(['error' => 'invoice_is_not_format'], 200);
        }
        $invoice_nos = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header_uuid)->get()->pluck('invoice_no')->toArray();
        $invoice_nos[] = $invoice_no;
        // dd($invoice_no);
        $ticket_header_total = 0;

        foreach ($promoiton_sub_promotions as $promoiton_sub_promotion)
        {
            $promotion_data = findPromotionData($promoiton_sub_promotion->promotion);
            if ($promoiton_sub_promotion->prize_check_type == 3) {
                //Check Gold Ring - Fixed Prize Type - 1 for Gold Ring, 2 for Gold Coin
                $check_gold_ring = FixedPrizeAmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)
                    ->pluck('fixed_prize_type')->first();

            } else {
                $check_gold_ring = 2;
            }

            //check same promotion in Pro Sub TicketHeader
            $same_promotion = PromotionSubTicketHeader::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('invoice_no', $invoice_no)->first();
            // dd($same_promotion);
            if (!$same_promotion || $check_gold_ring == 1)
            {
                //Calculate Available Amount based on active promoition
                 $total_amount = findValidTotalAmountOfProductsFromInvoice($invoice_nos, $promoiton_sub_promotion,$promotion_data,$check_gold_ring);
                 // $total_amount = findValidTotalAmountOfProductsFromInvoiceWithSingleQuery($invoice_no, $invoice_nos, $promoiton_sub_promotion, $promotion_data, $check_gold_ring);

                 $sub_promotion_uuid = $promoiton_sub_promotion->sub_promotion_uuid;
            } else
            {
                $total_amount['invoice_id'] = $same_promotion->invoice_id;
                $total_amount['totalprice'] = $same_promotion->valid_amount;
                $total_amount['gbh_customer_id'] = $same_promotion->gbh_customer_id;
                $sub_promotion_uuid = $same_promotion->sub_promotion_uuid;
            }
                // dd($total_amount);

            //Update in Pro Sub TicketHeader
            $same_sub_promotion = PromotionSubTicketHeader::where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->where('invoice_no', $invoice_no)->first();
            // dd($total_amount);
            if (!$same_sub_promotion) {
                $same_sub_promotion         = PromotionSubTicketHeader::create([
                    'promotion_uuid'        => $promoiton_sub_promotion->promotion_uuid,
                    'sub_promotion_uuid'    => $sub_promotion_uuid,
                    'ticket_header_uuid'    => $ticket_header_uuid,
                    'gbh_customer_id'       => $total_amount['gbh_customer_id'],
                    'invoice_id'            => $total_amount['invoice_id'],
                    'invoice_no'            => $invoice_no,
                    'valid_amount'          => $total_amount['totalprice'] ?? 0,
                    'status'                =>$total_amount['status']
                ]);
                $update_total_amount = $total_amount['totalprice'];
            }
            else
            {

                $update_total_amount = $total_amount['totalprice'];
            }

            if ($promoiton_sub_promotion->invoice_check_type == 1) {

                $amount_check = AmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
                    ->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();
                $one_qty_amount = $amount_check->amount;
            } else {
                $one_qty_amount = 0;
            }
            //Store or Update in claim_history
            $one_claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_uuid)
                ->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
                ->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();
            // dd($one_claim_history);
            if ($one_qty_amount != 0) {
                $valid_total_qty = $update_total_amount / $one_qty_amount;
            } else {
                $valid_total_qty = 0;
            }

            //Get Old Total Valid Amouunt
            $claim_history['valid_qty'] = (int) $valid_total_qty;
            $claim_history['remain_choose_qty'] = (int) $valid_total_qty;
            if ($one_claim_history) {
                // dd($claim_history);
                $one_claim_history->update($claim_history);
                // dd($one_claim_history);
            } else {
                // dd('new');
                $claim_history['promotion_uuid'] = $promoiton_sub_promotion->promotion_uuid;
                $claim_history['sub_promotion_uuid'] = $promoiton_sub_promotion->sub_promotion_uuid;
                $claim_history['ticket_header_uuid'] = $ticket_header_uuid;
                $claim_history['valid_qty'] = (int) $valid_total_qty;
                $claim_history['one_qty_amount'] = $one_qty_amount;
                $claim_history['invoice_check_type'] = $promoiton_sub_promotion->invoice_check_type;
                $claim_history['prize_check_type'] = $promoiton_sub_promotion->prize_check_type;
                $claim_history['uuid'] = (string) Str::uuid();
                ClaimHistory::create($claim_history);
            }
            $ticket_header_total +=$total_amount['totalprice'];
        }
        //Get Customer
        $ldcustomer = get_customer($gbh_customer_id, $branch_id);

        //Update Customer in Ticket Header
        $update_ticket_header['customer_uuid'] = $ldcustomer->uuid;
        $ticket_header->update($update_ticket_header);

        //store in ticket_header_invoice
        TicketHeaderInvoice::create([
            'uuid' => (string) Str::uuid(),
            'ticket_header_uuid' => $ticket_header_uuid,
            'invoice_id' => $ticket_header_total,
            'invoice_no' => $invoice_no,
            'valid_amount' => $ticket_header_total,
        ]);

        $data = [
            'ticket_header_uuid' => $ticket_header_uuid,
            'branch_id' => $branch_id,
            'customer' => $ldcustomer,
        ];

        // dd($test);
        //return ticket_header_uuid,
        return response()->json(['data' => $data], 200);
    }

    public function delete_invoice($uuid)
    {

        $ticket_header_invoice = TicketHeaderInvoice::where('uuid', $uuid)->first();
        $invoice_amount = $ticket_header_invoice->valid_amount;

        //Check Ticke Generate
        $tickets = Ticket::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->first();
        if ($tickets) {
            return response()->json([
                'error' => 'can_not_remove_invoice_when_ticket_is_generated',
            ]);
        }
        $claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->where('choose_status', 2)->first();
        if ($claim_history) {
            return response()->json([
                'error' => 'can_not_remove_invoice_when_promotion_is_choosed',
            ]);
        }
        $ticket_header = TicketHeader::where('uuid', $ticket_header_invoice->ticket_header_uuid)->first();

        //update amount and ticke qty in ticket header
        DB::beginTransaction();
        $update_total_valid_amount = $ticket_header->total_valid_amount - $invoice_amount;

        $ticket_header->update([
            'total_valid_amount' => $update_total_valid_amount,
            'total_remain_amount' => $update_total_valid_amount,
        ]);
        //delete promotion sub tickether
        PromotionSubTicketHeader::where('invoice_no', $ticket_header_invoice->invoice_no)->delete();

        //update Claim History
        ClaimHistory::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->delete();

        //delete invoice in ticket header invoice
        $ticket_header_invoice->delete();

        DB::commit();

        return response()->json([
            'success' => 'Invoice is removed!',
            'data' => $ticket_header_invoice,
        ]);
    }

    public function add_more_invoice(Request $request)
    {
        // dd($request->all());
        $invoice_no         = $request->invoice_no;
        $ticket_header_uuid = $request->ticket_header_uuid;
        $gbh_customer_id    = $request->gbh_customer_id;
        //check valid ticket header uuid
        $ticket_header      = TicketHeader::where('uuid', $ticket_header_uuid)->with('customers')->first();
        if (!isset($ticket_header)) {
            return response()->json(['error' => 'ticket_header_uuid_error'], 200);
        }

        //check ticket is generated or not
        $tickets = Ticket::where('ticket_header_uuid', $ticket_header->uuid)->first();
        if (isset($tickets)) {

            return response()->json(['error' => 'can_not_add_invoice_when_ticket_is_generated'], 200);
        }

        //check promotion is already choosed or not
        $claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_uuid)->where('choose_status', 2)->first();
        if ($claim_history) {
            // dd('hiiii');
            return response()->json(['error' => 'can_not_add_invoice_when_ticket_is_generated'], 200);
        }

        //check ticket invoices are more than 10 or not
        $ticket_header_invoices = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header->uuid)->get();
        if ($ticket_header_invoices->count() == 10 || $ticket_header_invoices->count() > 10) {
            return response()->json(['error' => 'accept_adding_only_10_invoices'], 200);
        }

        //check invoice is already used or not
        $ticket_header_invoice = check_used_invoice($invoice_no);

        //check invoice and branch are same or not.
        $branch_id = findBrandId($invoice_no);
        if (!$branch_id) {
            return response()->json(['error' => 'invoice_is_not_format'], 200);
        }

        //Get All Active ProSubPromotions
        $promoiton_sub_promotions = PromotionSubPromotion::whereHas('promotion', function ($q) {
            $q->where('status', '=', 1);
        })->where('deleted_at', null)->with('promotion')->get();

        //Find Aavailable promotions among active promotions

        foreach($promoiton_sub_promotions as $promoiton_sub_promotion)
        {
            $promotion_data = findPromotionData($promoiton_sub_promotion->promotion);

            if ($promoiton_sub_promotion->prize_check_type == 3) {
                //Check Gold Ring - Fixed Prize Type - 1 for Gold Ring, 2 for Gold Coin
                $check_gold_ring = FixedPrizeAmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)
                    ->pluck('fixed_prize_type')->first();

            } else {
                $check_gold_ring = 2;
            }

            //check same promotion in Pro Sub TicketHeader
            $same_promotion = PromotionSubTicketHeader::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('invoice_no', $invoice_no)->first();

            if (!$same_promotion || $check_gold_ring == 1)
            {
                $total_amount = findValidTotalAmountOfProductsFromInvoice($invoice_no, $promoiton_sub_promotion,$promotion_data,$check_gold_ring);
                // dd($total_amount);
                $sub_promotion_uuid = $promoiton_sub_promotion->sub_promotion_uuid;

            }
            else
            {
                $total_amount['invoice_id']     = $same_promotion->invoice_id;
                $total_amount['totalprice']     = $same_promotion->valid_amount;
                $total_amount['gbh_customer_id']= $same_promotion->gbh_customer_id;
                $total_amount['status']         = $same_promotion->status;
                $sub_promotion_uuid             = $same_promotion->sub_promotion_uuid;
                // dd('hi');
            }
            // dd($total_amount,'net');
            //Update or Store in Pro Sub TicketHeader
            $same_sub_promotion = PromotionSubTicketHeader::where(['sub_promotion_uuid'=>$promoiton_sub_promotion->sub_promotion_uuid,'invoice_no'=>$invoice_no,'ticket_header_uuid'=>$ticket_header_uuid])->first();

            if (!$same_sub_promotion) {
                    $same_sub_promotion  = PromotionSubTicketHeader::create([
                    'promotion_uuid'     => $promoiton_sub_promotion->promotion_uuid,
                    'sub_promotion_uuid' => $sub_promotion_uuid,
                    'ticket_header_uuid' => $ticket_header_uuid,
                    'gbh_customer_id'    => $total_amount['gbh_customer_id'],
                    'invoice_id'         => $total_amount['invoice_id'],
                    'invoice_no'         => $invoice_no,
                    'valid_amount'       => $total_amount['totalprice'] ? $total_amount['totalprice']: 0,
                    'status'             => $total_amount['status']
                ]);
            }

            $update_total_amount = PromotionSubTicketHeader::where([
                'promotion_uuid' => $promoiton_sub_promotion->promotion_uuid,
                'sub_promotion_uuid' => $sub_promotion_uuid,
                'ticket_header_uuid' => $ticket_header_uuid
            ])->sum('valid_amount');


            if ($promoiton_sub_promotion->invoice_check_type == 1) {
                $amount_check = AmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
                ->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();
                $one_qty_amount = $amount_check->amount;
            } else {
                $one_qty_amount = 0;
            }

            if ($one_qty_amount != 0) {
                $valid_total_qty = $update_total_amount / $one_qty_amount;
            } else {
                $valid_total_qty = 0;
            }

            $claim_history['valid_qty']                         = (int) $valid_total_qty;
            $claim_history['remain_choose_qty']                 = (int) $valid_total_qty;
            $claim_history['promotion_sub_promotion_id']        = $promoiton_sub_promotion->id;

            $one_claim_history      = ClaimHistory::where(['promotion_sub_promotion_id'=>$promoiton_sub_promotion->id,'ticket_header_uuid'=>$ticket_header_uuid])->first();


            if ($one_claim_history) {
                // dd($claim_history);
                $one_claim_history->update($claim_history);
                // dd($one_claim_history);
            }
            else {
                // dd('new');
                $claim_history['promotion_uuid']                = $promoiton_sub_promotion->promotion_uuid;
                $claim_history['sub_promotion_uuid']            = $promoiton_sub_promotion->sub_promotion_uuid;
                $claim_history['ticket_header_uuid']            = $ticket_header_uuid;
                $claim_history['valid_qty']                     = (int) $valid_total_qty;
                $claim_history['one_qty_amount']                = $one_qty_amount;
                $claim_history['invoice_check_type']            = $promoiton_sub_promotion->invoice_check_type;
                $claim_history['prize_check_type']              = $promoiton_sub_promotion->prize_check_type;
                $claim_history['promotion_sub_promotion_id']    = $promoiton_sub_promotion->id;
                $claim_history['uuid'] = (string) Str::uuid();
                ClaimHistory::create($claim_history);
            }
            //Get Customer
            $ldcustomer = get_customer($gbh_customer_id, $branch_id);

            //Update Customer in Ticket Header
            $update_ticket_header['customer_uuid'] = $ldcustomer->uuid;
            $ticket_header->update($update_ticket_header);
            $ticket_header_invoice = TicketHeaderInvoice::where(['invoice_no'=>$invoice_no,'ticket_header_uuid'=>$ticket_header_uuid])->first();

            if(!$ticket_header_invoice && $total_amount['totalprice']!=0)
            {
                $data = TicketHeaderInvoice::create([
                    'uuid'                  => (string) Str::uuid(),
                    'ticket_header_uuid'    => $ticket_header_uuid,
                    'invoice_id'            => $total_amount['invoice_id'],
                    'invoice_no'            => $invoice_no,
                    'valid_amount'          => $total_amount['totalprice'],
                ]);
            }

        }

        $data = [
            'ticket_header_uuid' => $ticket_header_uuid,
            'branch_id' => $branch_id,
            'customer' => $ldcustomer,
        ];
        return response()->json(['data' => $data], 200);


    }

}
