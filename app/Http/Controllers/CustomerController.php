<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Ticket;
use App\Models\NRCName;
use App\Models\Customer;
use App\Models\LuckyDraw;
use Illuminate\Support\Str;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\POS101\Pos101Amphur;
use App\Models\POS102\Pos102Amphur;
use App\Models\POS103\Pos103Amphur;
use App\Models\POS104\Pos104Amphur;
use App\Models\POS105\Pos105Amphur;
use App\Models\POS106\Pos106Amphur;
use App\Models\POS107\Pos107Amphur;
use App\Models\POS108\Pos108Amphur;
use App\Models\POS112\Pos112Amphur;
use App\Models\POS113\Pos113Amphur;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\TicketHeaderStepSale;
use App\Models\POS101\Pos101GbhCustomer;
use App\Models\POS102\Pos102GbhCustomer;
use App\Models\POS103\Pos103GbhCustomer;
use App\Models\POS104\Pos104GbhCustomer;
use App\Models\POS105\Pos105GbhCustomer;
use App\Models\POS106\Pos106GbhCustomer;
use App\Models\POS107\Pos107GbhCustomer;
use App\Models\POS108\Pos108GbhCustomer;
use App\Models\POS112\Pos112GbhCustomer;
use App\Models\POS113\Pos113GbhCustomer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:view-customer',['only' => ['index','search_result','show']]);
        // $this->middleware('permission:find-customer', ['only' => ['get_customer_by_phone_no']]);

    }
    protected function customer_connection()
    {
        return new Customer();
    }
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $customer_name = (!empty($_GET["customer_name"])) ? ($_GET["customer_name"]) : ('');
                $customer_phone_no = (!empty($_GET["customer_phone_no"])) ? ($_GET["customer_phone_no"]) : ('');
                $result = $this->customer_connection();
                if ($customer_name != "") {
                    $result = $result->where('firstname', 'ilike', '%' . $customer_name . '%');
                }
                if ($customer_phone_no != "") {
                    $result = $result->whereOr('phone_no', 'ilike', '%' . $customer_phone_no . '%')->whereOr('phone_no_2', 'ilike', '%' . $customer_phone_no . '%');
                }
                // dd($result->get());
                $result = $result->get();
                return DataTables::of($result)

                    ->addColumn('action', function ($data) {
                        return 'action';
                    })
                    ->make(true);
            }
            return view('customers.index');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function search_result(Request $request)
    {
        try{
            $lucky_draw_name = (!empty($_GET["lucky_draw_name"])) ? ($_GET["lucky_draw_name"]) : ('');
            $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
            $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
            $lucky_draw_status = (!empty($_GET["lucky_draw_status"])) ? ($_GET["lucky_draw_status"]) : ('');
            $result = $this->connection();
            if ($lucky_draw_name != "") {
                $result = $result->where('name', 'like', '%' . $lucky_draw_name . '%');
            }
            if ($start_date != "") {
                $dateStr = str_replace("/", "-", $start_date);
                $start_date = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('start_date', '>=', $start_date);
            }
            if ($end_date != "") {
                $dateStr = str_replace("/", "-", $end_date);
                $end_date = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('end_date', '>=', $end_date);
            }
            if ($lucky_draw_status != "") {
                $result = $result->where('status', $lucky_draw_status);
            }
            $result = $result->get();
            return DataTables::of($result)
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to Search Lucky Draw!');
        }
    }
    public function create()
    {

    }

    public function show($uuid)
    {
        try {
            $customer = Customer::with('NRCNumbers', 'NRCNames', 'NRCNaings', 'amphurs', 'provinces')->find($uuid);
            return view('customers.show', compact('customer'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to Load User!');
        }
    }

    public function store(Request $request)
    {
        try {
            $check_user = Customer::where('phone_no', $request->customer_phone_no)->first();
            $ticket_header = TicketHeader::where('uuid', $request->ticket_header_uuid)->first();
            if($ticket_header->printed_at == null){
                if ($request->customer_phone_no == '09777777777' || $request->firstname == 'Cash') {
                    return redirect()
                        ->back()->withInput()
                        ->with('error', 'Please Use Correct User Info');
                }
                //check not to save without ticket
                $tickets = Ticket::where('ticket_header_uuid', $ticket_header->uuid)->first();

                if ($ticket_header->total_valid_ticket_qty == 0) {
                    return redirect()
                        ->back()->withInput()
                        ->with('error', 'Ticket Qty must be at least one Qty');
                }
                $luckydraw_number_of_ticket = LuckyDraw::where('uuid', $ticket_header->promotion_uuid)->first()->number_of_ticket;
                if ($check_user || $request->old_customer_id == '0') {

                    if ($request->old_customer_id == '0') {
                        $check_user = Customer::where('customer_id', $request->old_customer_id)->first();
                    } else {
                        $request->customer_id = $check_user->customer_id;
                    }
                    $check_user->update([
                        'phone_no' => $request->customer_phone_no,
                        'customer_id' => $request->customer_id,
                        'titlename' => $request->titlename,
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'customer_no' => $request->customer_no,
                        'customer_type' => $request->customer_type,
                        'nrc_no' => $request->nrc_no,
                        'nrc_name' => $request->nrc_name,
                        'nrc_short' => $request->nrc_short,
                        'nrc_number' => $request->nrc_number,
                        'phone_no_2' => $request->phone_no2,
                        'province_id' => $request->customer_division,
                        'amphur_id' => $request->customer_township,
                        'address' => $request->customer_address,
                        'email' => $request->email,
                        'passport' => $request->passport,
                    ]);
                } else {
                    $check_user = Customer::create([
                        'uuid' => (string) Str::uuid(),
                        'customer_id' => $request->customer_id,
                        'phone_no' => $request->customer_phone_no,
                        'titlename' => $request->titlename,
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'customer_no' => $request->customer_no,
                        'customer_type' => $request->customer_type,
                        'nrc_no' => $request->nrc_no,
                        'nrc_name' => $request->nrc_name,
                        'nrc_short' => $request->nrc_short,
                        'nrc_number' => $request->nrc_number,
                        'phone_no_2' => $request->phone_no2,
                        'province_id' => $request->customer_division,
                        'amphur_id' => $request->customer_township,
                        'address' => $request->customer_address,
                        'email' => $request->email,
                        'passport' => $request->passport,
                    ]);
                }
                $ticket_header->update([
                    'customer_uuid' => $check_user->uuid,
                ]);
                //generate ticket
                if ($ticket_header->total_valid_ticket_qty > 0) {
                    $luckydraw = LuckyDraw::where('uuid', $ticket_header->promotion_uuid)->first();
                    $words = explode(" ", $luckydraw->name);
                    $prefix = "";

                    foreach ($words as $w) {
                        $prefix .= $w[0];
                    }
                    if (!isset($tickets)) {
                        $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $ticket_header->branch_id)->first()->branch_short_name;
                        $prefix = $prefix . $branch_prefix;

                        $last_id = Ticket::select('id', 'ticket_no')->where('promotion_uuid', $ticket_header->promotion_uuid)
                            ->latest('id')->first();
                        if ($last_id == null) {
                            $ticket_no = $prefix . '-' . '00001';
                        } else {
                            $ticket_no = $last_id['ticket_no'];
                            $ticket_no_arr = explode("-", $ticket_no);
                            $last_no = str_pad($ticket_no_arr[1] + 1, 5, 0, STR_PAD_LEFT);
                            $ticket_no = $prefix . '-' . $last_no;
                        }
                        for ($x = 0; $x < $ticket_header->total_valid_ticket_qty; $x++) {
                            Ticket::create([
                                'uuid' => (string) Str::uuid(),
                                'ticket_no' => $ticket_no,
                                'ticket_header_uuid' => $ticket_header->uuid,
                                'promotion_uuid' => $ticket_header->promotion_uuid,
                            ]);
                            $ticket_no_arr = explode("-", $ticket_no);
                            $last_no = str_pad($ticket_no_arr[1] + 1, 5, 0, STR_PAD_LEFT);
                            $ticket_no = $prefix . '-' . $last_no;
                        }
                    }
                }
            }
            return redirect()->route('tickets.summary_ticket_header', $request->ticket_header_uuid);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to Store User!');
        }
    }

    public static function get_customer_by_phone_no($branch_id, $phone_no)
    {
        try{
            if ($branch_id == 1) {
                $customer = Pos101GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 2) {
                $customer = Pos102GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 3) {
                $customer = Pos103GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 9) {
                $customer = Pos104GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 10) {
                $customer = Pos105GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 11) {
                $customer = Pos106GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 19) {
                $customer = Pos107GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 21) {
                $customer = Pos108GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 27) {
                $customer = Pos112GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }
            if ($branch_id == 28) {
                $customer = Pos113GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
            }

            $ld_customer = Customer::orwhere('phone_no', $phone_no)->orwhere('phone_no_2', $phone_no)->first();

            if (!$customer) {
                if($ld_customer){
                    $customer = $ld_customer;
                }
            }else{
                if (isset($customer->identification_card) && $customer->member_active == true) {
                    $member_type = 'Member';
                } else {
                    $member_type = 'Old';
                }
                if($ld_customer){
                    $customer->customer_type = $member_type;
                }else{
                    $customer->phone_no = $customer->mobile;
                    $customer->phone_no_2 = $customer->house_no;
                    $customer->customer_type = $member_type;
                    $customer->customer_id = $customer->gbh_customer_id;
                    $customer->customer_no = $customer->customer_barcode;
                }
            }
            if (isset($customer)) {
                return response()->json(['data' => $customer], 200);
            } else {
                return response()->json(null, 200);
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to generate Document No!');
        }
    }

    public function nrc_name_by_nrc_no(Request $request)
    {
        try{
            $nrc_no = $request->nrc_no;
            $nrc_names = NRCName::where('nrc_number_id', $nrc_no)->get()->toarray();
            $output = [];
            foreach ($nrc_names as $r) {
                $output[$r['id']] = $r['district'];
            }
            return $output;
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to generate Document No!');
        }
    }

    public function customer_township_by_customer_division(Request $request)
    {
        // try{
            $customer_division = $request->customer_division;

            $branch_id = $request->branch_id;
            if ($branch_id == 1) {
                $customer_townships = Pos101Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 2) {
                $customer_townships = Pos102Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 3) {
                $customer_townships = Pos103Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 9) {
                $customer_townships = Pos104Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 10) {
                $customer_townships = Pos105Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 11) {
                $customer_townships = Pos106Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 19) {
                $customer_townships = Pos107Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 21) {
                $customer_townships = Pos108Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 23) {
                $customer_townships = Pos110Amphur::where('province_id', $customer_division);
                // dd($customer_townships);
            }
            if ($branch_id == 27) {
                $customer_townships = Pos112Amphur::where('province_id', $customer_division);
            }
            if ($branch_id == 28) {
                $customer_townships = Pos113Amphur::where('province_id', $customer_division);
            }
            $customer_townships = $customer_townships->get()->toarray();
            $output = [];
            foreach ($customer_townships as $r) {
                $output[$r['amphur_id']] = $r['amphur_name'];
            }
            return $output;
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to generate Document No!');
        // }
    }

    public function getCustomer(Request $request)
    {
        $customers = Customer::all();
        return view('customers.index',compact('customers'));
    }

}
