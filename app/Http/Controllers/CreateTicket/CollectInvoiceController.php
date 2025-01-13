<?php

namespace App\Http\Controllers\CreateTicket;

use App\Models\Branch;
use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TicketHeaderInvoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\POS101\Pos101GbhCustomer;
use Yajra\DataTables\Facades\DataTables;
use App\Models\POS101\Pos101SaleCashDocument;
use App\Models\POS110\Pos110SaleCashDocument;
use App\Http\Controllers\CustomerViewController;

class CollectInvoiceController extends Controller
{

    protected function connection()
    {
        return new TicketHeaderInvoice();
    }

    public function collect_invoice_view(Request $request)
    {
        ///store customer view route////
        $customer_data = new CustomerViewController;
        $customer_data->store_customer_view();
        //Store Customer View
        $currentURL = URL::current();
        if (str_contains($currentURL, '192.168.21.242') || str_contains($currentURL,'tpluckydraw')) {
            $branch_id = 2;
        }
        if (str_contains($currentURL, '192.168.25.242') || str_contains($currentURL,'tpwluckydraw')) {
            $branch_id = 11;
        }
        if (str_contains($currentURL, '192.168.3.242') || str_contains($currentURL,'ltluckydraw')
        // ||str_contains($currentURL, '192.168.2.23')
        || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
            $branch_id = 1;
        }
        if (str_contains($currentURL, '192.168.11.242') || str_contains($currentURL,'ssluckydraw')
        // ||str_contains($currentURL, '192.168.2.23')
        ) {
            $branch_id = 3;
        }
        if (str_contains($currentURL, '192.168.16.242') || str_contains($currentURL,'edluckydraw')
        ||str_contains($currentURL, '192.168.2.23')) {
            $branch_id = 9;
        }
        if (str_contains($currentURL, '192.168.36.242') || str_contains($currentURL,'htyluckydraw')) {
            $branch_id = 19;
        }
        if (str_contains($currentURL, '192.168.31.242') || str_contains($currentURL,'mlmluckydraw')) {
            $branch_id = 10;

        }
        if (str_contains($currentURL, '192.168.41.242') || str_contains($currentURL,'atyluckydraw')) {
            $branch_id = 21;

        }
        if (str_contains($currentURL, '192.168.46.242') || str_contains($currentURL,'tmluckydraw')) {
            $branch_id = 27;

        }
        if (str_contains($currentURL, '192.168.51.243') || str_contains($currentURL,'sdgluckydraw')) {
            $branch_id = 28;
        }
        if ($branch_id) {
            return view('create_tickets.create-collect-invoice', compact('branch_id'));
        }
        return redirect(route('home'));
    }

    public function add_invoice(Request $request)
    {
        //Get Request
        $invoice_no = $request->invoice_no;

        if (isset($request->ticket_header_uuid)) {
            //check valid ticket header uuid
            $ticket_header = TicketHeader::where('uuid', $request->ticket_header_uuid)->with('customers')->first();
            if (!isset($ticket_header)) {
                return response()->json(['error' => 'ticket_header_uuid_error'], 200);
            }
            $branch_id = $ticket_header->branch_id;
            $ticket_header_uuid = $ticket_header->uuid;
            //check generated ticket
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
        }

        //check used invoice
        $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)
            ->whereHas('ticket_headers', function ($q) {
                $q->where('status', '=', 1);
            })->first();

        if (isset($ticket_header_invoice)) {
            return response()->json(['error' => 'invoice_is_used'], 200);
        }
        $currentURL = URL::current();
        if (str_contains($currentURL, '192.168.21.242')) {
            $checkInvoiceNo = Pos102SaleCashDocument::query();
            $db_ext = DB::connection('pos102_pgsql');
            $branch_id = '2';
        }
        if (str_contains($currentURL, '192.168.25.242')) {
            $checkInvoiceNo = Pos106SaleCashDocument::query();
            $db_ext = DB::connection('pos106_pgsql');
            $branch_id = '11';
        }
        if (str_contains($currentURL, '192.168.3.242')
        // ||str_contains($currentURL, '192.168.2.23')
        || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
            $checkInvoiceNo = Pos101SaleCashDocument::query();
            $db_ext = DB::connection('pos101_pgsql');
            $branch_id = '1';
        }
        if (str_contains($currentURL, '192.168.11.242')
        // ||str_contains($currentURL, '192.168.2.23')
        ) {
            $checkInvoiceNo = Pos103SaleCashDocument::query();
            $db_ext = DB::connection('pos103_pgsql');
            $branch_id = '3';
        }
        if (str_contains($currentURL, '192.168.16.242')
        ||str_contains($currentURL, '192.168.2.23')) {
            $checkInvoiceNo = Pos104SaleCashDocument::query();
            $db_ext = DB::connection('pos104_pgsql');
            $branch_id = '9';
        }
        if (str_contains($currentURL, '192.168.36.242')
        ) {
            $checkInvoiceNo = Pos107SaleCashDocument::query();
            $db_ext = DB::connection('pos107_pgsql');
            $branch_id = '19';
        }
        if (str_contains($currentURL, '192.168.31.242')) {
            $checkInvoiceNo = Pos105SaleCashDocument::query();
            $db_ext = DB::connection('pos105_pgsql');
            $branch_id = '10';
        }
        if (str_contains($currentURL, '192.168.41.242')) {
            $checkInvoiceNo = Pos108SaleCashDocument::query();
            $db_ext = DB::connection('pos108_pgsql');
            $branch_id = '21';
        }
        if (str_contains($currentURL, '192.168.46.242')) {
            $checkInvoiceNo = Pos112SaleCashDocument::query();
            $db_ext = DB::connection('pos112_pgsql');
            $branch_id = '27';
        }
        if (str_contains($currentURL, '192.168.51.243')) {
            $checkInvoiceNo = Pos113SaleCashDocument::query();
            $db_ext = DB::connection('pos113_pgsql');
            $branch_id = '28';
        }
        if (str_contains($currentURL, '192.168.61.242')) {
            $checkInvoiceNo = Pos110SaleCashDocument::query();
            $db_ext = DB::connection('pos110_pgsql');
            $branch_id = '23';
        }
        $checkInvoiceNo = $checkInvoiceNo->select('sale_cash_document_status_id', 'sale_cash_document_id',
            'gbh_customer_id', 'sale_cash_document_no', 'branch_code', 'customer_code', 'sale_cash_document_datenow', 'voucher_value', 'net_amount')->where('sale_cash_document_no', $invoice_no)
            ->first();

        if (!$checkInvoiceNo) {
            //deposit invoice
            $checkInvoiceNo = $db_ext->table('pledge.pledge_document')
                ->where('pledge.pledge_document.sale_command_document_no', $invoice_no)
                ->first();
            if ($checkInvoiceNo) {
                $check_deposit_inovice = $checkInvoiceNo->pledge_document_docno;
                $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $check_deposit_inovice)->first();
                $net_amount = $check_deposit_inovice->net_amount;
            } else {
                return response()->json(['error' => 'invoice_is_not_found'], 200);
            }
            if (isset($ticket_header_invoice)) {
                return response()->json(['error' => 'invoice_is_used_for_deposit_invoice'], 200);
            }

        } else {
            $net_amount = $checkInvoiceNo->net_amount;
        }
        if ($branch_id == 1) {
            $customer = Pos101GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 2) {
            $customer = Pos102GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 3) {
            $customer = Pos103GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 9) {
            $customer = Pos104GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 10) {
            $customer = Pos105GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 11) {
            $customer = Pos106GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 19) {
            $customer = Pos107GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 21) {
            $customer = Pos108GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 27) {
            $customer = Pos112GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($branch_id == 28) {
            $customer = Pos113GbhCustomer::where('gbh_customer_id', $checkInvoiceNo['gbh_customer_id'])->first();
        }
        if ($customer->customer_barcode != '10547') {
            //check customer in ldcustomer
            $ldcustomer = Customer::where('phone_no', $customer->mobile)->first();
            if ($customer->identification_card != '') {
                $member_type = 'Member';
            } else {
                $member_type = 'Old';
            }
            if (!isset($ldcustomer)) {
                $ldcustomer = Customer::create([
                    'uuid' => (string) Str::uuid(),
                    'customer_id' => $customer->gbh_customer_id,
                    'titlename' => $customer->titlename,
                    'firstname' => $customer->firstname,
                    'lastname' => $customer->lastname,
                    'phone_no' => $customer->mobile,
                    'nrc_no' => (int) $customer->nrc_no,
                    'nrc_name' => (int) $customer->nrc_name,
                    'nrc_short' => (int) $customer->nrc_short,
                    'nrc_number' => (int) $customer->nrc_number,
                    'passport' => $customer->passport,
                    'email' => $customer->email,
                    'phone_no_2' => $customer->homephone,
                    'national_id' => $customer->nationlity_id,
                    'member_no' => $customer->identification_card,
                    'amphur_id' => $customer->amphur_id,
                    'province_id' => $customer->province_id,
                    'address' => $customer->full_address,
                    'customer_no' => $customer->customer_barcode,
                    'customer_type' => $member_type,
                ]);
            }
        } else {
            $ldcustomer = Customer::create([
                'uuid' => (string) Str::uuid(),
                'customer_id' => $customer->gbh_customer_id,
                'firstname' => $customer->firstname,
                'phone_no' => $customer->mobile,
                'customer_type' => 'New',
            ]);
        }
        $net_amount = intval(preg_replace('/[^\d.]/', '', ltrim($net_amount, '฿')));
        $get_voucher_value = intval(preg_replace('/[^\d.]/', '', ltrim($checkInvoiceNo->voucher_value, '฿')));
        if ($get_voucher_value > 0) {
            $valid_total_price = $valid_total_price - $get_voucher_value;
        }
        if (isset($request->ticket_header_uuid)) {
            // $request->product_accessary_price = (int) str_replace(',', '', $request->product_accessary_price);
            $total_amount = TicketHeaderInvoice::where('ticket_header_uuid', $request->ticket_header_uuid)->sum('valid_amount');
            $new_total_amount = $net_amount + $total_amount;
            $ticket_header->update([
                'total_valid_amount' => $new_total_amount,
                'total_remain_amount' => $new_total_amount,
            ]);
        } else {
            $ticket_header_no = $this->generate_ticket_header_no(date(now()), $branch_id);
            $ticket_header = TicketHeader::create([
                'uuid' => (string) Str::uuid(),
                'ticket_header_no' => $ticket_header_no,
                'customer_uuid' => $ldcustomer->uuid,
                'created_at' => date(now()),
                'created_by' => Auth::user()->uuid,
                'status' => 1,
                'branch_id' => $branch_id,
                'total_valid_amount' => $net_amount,
                'total_remain_amount' => $net_amount,
            ]);
            $ticket_header_uuid = $ticket_header->uuid;
        }
        //Store in Ticket Header Invoice
        $invoice_id = $checkDeposit[0]->pledge_document_id ?? $checkInvoiceNo->sale_cash_document_id;

        TicketHeaderInvoice::create([
            'uuid' => (string) Str::uuid(),
            'ticket_header_uuid' => $ticket_header_uuid,
            'invoice_id' => $invoice_id,
            'invoice_no' => $invoice_no,
            'valid_amount' => $net_amount,
        ]);
        ////check customer////
        if (isset($request->ticket_header_uuid)) {
            if ($ticket_header->customers) {
                if ($checkInvoiceNo->gbh_customer_id != $ticket_header->customers->customer_id) {
                    $data['messsage'] = 'customer_is_not_same';
                    $data['ticket_header_uuid'] = $ticket_header->uuid;
                    $data['old_user_name'] = $ticket_header->customers->firstname;
                    $data['new_user_name'] = $customer->firstname;
                    $data['old_customer_id'] = $ticket_header->customers->customer_id;
                    $data['new_customer_id'] = $checkInvoiceNo->gbh_customer_id;
                    $data['old_phone_no'] = $ticket_header->customers->phone_no;
                    $data['new_phone_no'] = $customer->mobile;
                    $data['invoice_id'] = $checkInvoiceNo->sale_cash_document_id;
                    return response()->json(['data' => $data], 200);
                }
                if ($ticket_header->customers->phone_no != '09777777777' && $checkInvoiceNo->gbh_customer_id != $ticket_header->customers->customer_id) {
                    return response()->json(['error' => 'customer_is_not_same'], 200);
                }
            } else {
                if ($checkInvoiceNo->customer_code != '09777777777') {
                    return response()->json(['error' => 'customer_is_not_same'], 200);
                }
            }
        }

        $spr['ticket_header_uuid'] = $ticket_header->uuid;
        $spr['message'] = 'successfully_created';
        return response()->json(['data' => $spr], 200);
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

    public function edit_collect_invoice($ticket_header_uuid)
    {
        ///store customer view route////
        $customer_data = new CustomerViewController;
        $customer_data->store_customer_view();
        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        return view('create_tickets.edit-collect-invoice', compact('ticket_header', 'ticket_header_uuid'));

    }

    public function result_for_collect_invoice(Request $request)
    {
        // try {
        $ticket_header_uuid = (!empty($_GET["ticket_header_uuid"])) ? ($_GET["ticket_header_uuid"]) : ('');

        $result = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header_uuid)->get();

        return DataTables::of($result)
            ->editColumn('valid_amount', function ($data) {
                if (isset($data->valid_amount)) {
                    return number_format($data->valid_amount);
                }
                return '';
            })
            ->addIndexColumn()
            ->make(true);
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("ticktes.index"))
        //         ->with('error', 'Fail to Search Document!');
        // }
    }

    public function update_ticket_header_customer(Request $request)
    {

        // try {
        $ticket_header = TicketHeader::where('uuid', $request->ticket_header_uuid)->first();
        $branch_id = $ticket_header->branch_id;

        if ($branch_id == 1) {
            $customer = Pos101GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 2) {
            $customer = Pos102GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 3) {
            $customer = Pos103GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 9) {
            $customer = Pos104GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 10) {
            $customer = Pos105GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 11) {
            $customer = Pos106GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 19) {
            $customer = Pos107GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 21) {
            $customer = Pos108GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 27) {
            $customer = Pos112GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if ($branch_id == 28) {
            $customer = Pos113GbhCustomer::where('gbh_customer_id', $request->customer_id)->first();
        }
        if (isset($customer->identification_card)) {
            $member_type = 'Member';
        } else {
            $member_type = 'Old';
        }
        if (!$customer) {
            $ldcustomer = Customer::where('customer_id', $request->customer_id)->first();
            $ldcustomer->update([
                'uuid' => (string) Str::uuid(),
                'customer_type' => $member_type,
            ]);
        } else {
            $ldcustomer = Customer::create([
                'uuid' => (string) Str::uuid(),
                'customer_id' => $customer->gbh_customer_id,
                'titlename' => $customer->titlename,
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'phone_no' => $customer->mobile,
                'nrc_no' => (int) $customer->nrc_no,
                'nrc_name' => (int) $customer->nrc_name,
                'nrc_short' => (int) $customer->nrc_short,
                'nrc_number' => (int) $customer->nrc_number,
                'passport' => $customer->passport,
                'email' => $customer->email,
                'phone_no_2' => $customer->homephone,
                'national_id' => $customer->nationlity_id,
                'member_no' => $customer->identification_card,
                'amphur_id' => $customer->amphur_id,
                'province_id' => $customer->province_id,
                'address' => $customer->full_address,
                'customer_no' => $customer->customer_barcode,
                'customer_type' => $member_type,
            ]);
        }

        $ticket_header->update([
            'customer_uuid' => $ldcustomer->uuid,
        ]);

        return true;
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function delete_collect_invoice($id)
    {
        // try {
        //find invoice in ticket header invoice

        $ticket_header_invoice = TicketHeaderInvoice::where('uuid', $id)->first();
        $invoice_amount = $ticket_header_invoice->valid_amount;

        //Check Ticke Generate
        $tickets = Ticket::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->first();
        if ($tickets) {
            return redirect()
                ->back()->withInput()
                ->with('error', 'Can not Remove invoice when ticket is generated');
        }
        $claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->where('choose_status', 2)->first();
        if ($claim_history) {
            return redirect()
                ->back()->withInput()
                ->with('error', 'Can not Remove invoice when ticket is generated');
        }

        $ticket_header = TicketHeader::where('uuid', $ticket_header_invoice->ticket_header_uuid)->first();

        //update amount and ticke qty in ticket header
        DB::beginTransaction();
        $update_total_valid_amount = $ticket_header->total_valid_amount - $invoice_amount;

        $ticket_header->update([
            'total_valid_amount' => $update_total_valid_amount,
            'total_remain_amount' => $update_total_valid_amount,
        ]);

        //delete invoice in ticket header invoice
        $ticket_header_invoice->delete();
        DB::commit();
        return response()->json([
            'success' => 'Invoice is removed!',
            'data' => $ticket_header_invoice,
        ]);
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }


}
