<?php

namespace App\Http\Controllers;

use File;
use PDF as MPDF;
use App\Models\User;
use App\Models\Branch;
use App\Models\Ticket;
use App\Models\NRCName;
use App\Models\Category;
use App\Models\Customer;
use App\Models\NRCNaing;
use App\Models\LuckyDraw;
use App\Models\NRCNumber;
use App\Models\BranchUser;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\LuckyDrawBrand;
use App\Models\ReprintHistory;
use App\Models\LuckyDrawBranch;
use App\Models\LuckyDrawCategory;
use App\Models\Satsan\SatsanBrand;
use Illuminate\Support\Facades\DB;
use App\Models\POS101\Pos101Amphur;
use App\Models\POS102\Pos102Amphur;
use App\Models\POS103\Pos103Amphur;
use App\Models\POS104\Pos104Amphur;
use App\Models\POS105\Pos105Amphur;
use App\Models\POS106\Pos106Amphur;
use App\Models\POS107\Pos107Amphur;
use App\Models\POS108\Pos108Amphur;
use App\Models\POS110\Pos110Amphur;
use App\Models\POS112\Pos112Amphur;
use App\Models\POS113\Pos113Amphur;
use App\Models\TicketHeaderInvoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Lanthit\LanthitBrand;
use App\Models\TicketHeaderStepSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DoNotUseTicketExport;
use App\Models\POS101\Pos101Province;
use App\Models\POS102\Pos102Province;
use App\Models\POS103\Pos103Province;
use App\Models\POS104\Pos104Province;
use App\Models\POS105\Pos105Province;
use App\Models\POS106\Pos106Province;
use App\Models\POS107\Pos107Province;
use App\Models\POS108\Pos108Province;
use App\Models\POS110\Pos110Province;
use App\Models\POS112\Pos112Province;
use App\Models\POS113\Pos113Province;
use App\Models\TheikPan\TheikPanBrand;
use Illuminate\Support\Facades\Storage;
use App\Models\EastDagon\EastDagonBrand;
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
use App\Models\POS114\Pos114GbhCustomer;
use App\Models\Tampawady\TampawadyBrand;
use App\Models\TerminalM\TerminalMBrand;
use Yajra\DataTables\Facades\DataTables;
use App\Models\POS101\Pos101SaleCashItems;
use App\Models\POS102\Pos102SaleCashItems;
use App\Models\POS103\Pos103SaleCashItems;
use App\Models\POS104\Pos104SaleCashItems;
use App\Models\POS105\Pos105SaleCashItems;
use App\Models\POS106\Pos106SaleCashItems;
use App\Models\POS107\Pos107SaleCashItems;
use App\Models\POS108\Pos108SaleCashItems;
use App\Models\POS112\Pos112SaleCashItems;
use App\Models\POS113\Pos113SaleCashItems;
use App\Models\POS101\Pos101SaleCashDocument;
use App\Models\POS102\Pos102SaleCashDocument;
use App\Models\POS103\Pos103SaleCashDocument;
use App\Models\POS104\Pos104SaleCashDocument;
use App\Models\POS105\Pos105SaleCashDocument;
use App\Models\POS106\Pos106SaleCashDocument;
use App\Models\POS107\Pos107SaleCashDocument;
use App\Models\POS108\Pos108SaleCashDocument;
use App\Models\POS110\Pos110SaleCashDocument;
use App\Models\POS112\Pos112SaleCashDocument;
use App\Models\POS113\Pos113SaleCashDocument;
use App\Http\Controllers\NewCreateTicket\MyPromotionController;

class TicketController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('permission:view-ticket', ['only' => ['index', 'search_result']]);
        // $this->middleware('permission:view-ticket|create-ticket', ['only' => ['create_ticket_header', 'edit_ticket_header', 'generate_ticket_header_no', 'invoice_list_by_ticket_header', 'delete_invoice', 'create']]);
        // $this->middleware('permission:print-ticket', ['only' => ['print_ticket']]);
        // $this->middleware('permission:reprint-ticket', ['only' => ['reprint_ticket']]);

    }

    protected function connection()
    {
        return new TicketHeader();
    }
    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('tickets.index');
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function search_result(Request $request)
    {
        try {
            $ticket_header_no = (!empty($_GET["ticket_header_no"])) ? ($_GET["ticket_header_no"]) : ('');
            $invoice_no = (!empty($_GET["invoice_no"])) ? ($_GET["invoice_no"]) : ('');

            $result = $this->connection();
            if ($ticket_header_no != "") {
                $result = $result->where('ticket_header_no', 'ilike', '%' . $ticket_header_no . '%');
            }
            if ($invoice_no != "") {
                $result = $result->whereHas('invoices', function ($q) use ($invoice_no) {
                    $q->where('invoice_no', 'ilike', '%' . $invoice_no . '%');
                });
            }
            $result = $result->orderby('created_at', 'DESC');
            $result = $result->with('customers', 'invoices');
            return DataTables::of($result)
                ->editColumn('invoice_no', function ($data) {
                    if (isset($data->invoices)) {
                        $invoices = '';
                        foreach ($data->invoices as $invoice) {
                            $invoices .= $invoice->invoice_no . ',';
                        }
                        return rtrim($invoices, ", ");
                    }
                    return '';
                })
                ->editColumn('promotion_name', function ($data) {
                    if (isset($data->promotions)) {
                        return $data->promotions->name;
                    }
                    return '';
                })
                ->editColumn('customer_name', function ($data) {
                    if (isset($data->customers)) {
                        return $data->customers->firstname;
                    }
                    return '';
                })
                ->editColumn('customer_phone_no', function ($data) {
                    if (isset($data->customers)) {
                        return $data->customers->phone_no;
                    }
                    return '';
                })
                ->editColumn('created_user', function ($data) {
                    if (isset($data->created_users)) {
                        return $data->created_users->name;
                    }
                    return '';
                })
                ->editColumn('printed_status', function ($data) {
                    if (isset($data->printed_at)) {
                        return 'Printed';
                    }
                    return 'Not Printed';
                })
                ->editColumn('cancel_status', function ($data) {
                    if ($data->status == 2) {
                        return 'Canceled';
                    }
                    return 'Normal';
                })
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("ticktes.index"))
                ->with('error', 'Fail to Search Document!');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
            $today = date('Y-m-d');
            $luckydraws = LuckyDraw::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->where('status', 1)->get();

            return view('tickets.create', compact('branches', 'luckydraws'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function show_check_ticket()
    {
        return view('tickets.check_ticket');
    }

    public function check_ticket()
    {
        return view('tickets.check_ticket');
    }

    public function new_add_invoice(Request $request)
    {
        // try{
        //check permission
        if (!Gate::allows('create-ticket')) {
            return response()->json(['error' => 'permission_denied'], 200);
        };
        //Get Request
        $branch_id = $request->branch_id;
        $ticket_type = $request->ticket_type;
        $lucky_draw_uuid = $request->lucky_draw_uuid;
        $invoice_no = $request->invoice_no;

        //Get Luckdraw Info
        $lucky_draw = LuckyDraw::where('uuid', $lucky_draw_uuid)->first();

        //check promtion image
        if (!file_exists('images/promotion_image/' . $lucky_draw_uuid . '.png')) {
            return response()->json(['error' => 'promotion_image_is_not_uploaded'], 200);
        };

        if (isset($request->ticket_header_uuid)) {
            //check valid ticket header uuid
            $ticket_header = TicketHeader::where('uuid', $request->ticket_header_uuid)->with('customers')->first();
            if (!isset($ticket_header)) {
                return response()->json(['error' => 'ticket_header_uuid_error'], 200);
            }
            $branch_id = $ticket_header->branch_id;
            $ticket_type = $request->ticket_type;
            $lucky_draw_uuid = $ticket_header->promotion_uuid;
            $ticket_header_uuid = $ticket_header->uuid;
            //check generated ticket
            $tickets = Ticket::where('ticket_header_uuid', $ticket_header->uuid)->first();
            if (isset($tickets)) {
                return response()->json(['error' => 'can_not_add_invoice_when_ticket_is_generated'], 200);
            }

            $ticket_header_invoices = TicketHeaderInvoice::where('promotion_uuid', $lucky_draw_uuid)->where('ticket_header_uuid', $ticket_header->uuid)->get();
            if ($ticket_header_invoices->count() == 10 || $ticket_header_invoices->count() > 10) {
                return response()->json(['error' => 'accept_adding_only_10_invoices'], 200);
            }
        } else {
            $branch_id = $request->branch_id;
            $ticket_type = $request->ticket_type;
            $lucky_draw_uuid = $request->lucky_draw_uuid;
        }

        //check used invoice
        $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)->where('promotion_uuid', $lucky_draw_uuid)
            ->whereHas('ticket_headers', function ($q) {
                $q->where('status', '=', 1);
            })->first();
        if (isset($ticket_header_invoice)) {
            return response()->json(['error' => 'invoice_is_used'], 200);
        }

        $luckydraw_branches = LuckyDrawBranch::where('promotion_uuid', $lucky_draw_uuid)->with('branches')->get();
        $checkInvoiceNo = null;
        $l_branches_count = [];

        if ($luckydraw_branches->count() > 0) {
            foreach ($luckydraw_branches as $luckydraw_branch) {
                if ($luckydraw_branch->branches) {
                    $l_branch_id = $luckydraw_branch->branches->branch_id;
                    $l_branches_count[] = $l_branch_id;
                }
            }
        }
        $luckydraw_branches_count = count($l_branches_count);

        $l_categories_count = [];
        $luckydraw_categories = LuckyDrawCategory::where('promotion_uuid', $lucky_draw_uuid)->with('categories')->get();
        if ($luckydraw_categories->count() > 0) {
            foreach ($luckydraw_categories as $luckydraw_category) {
                if ($luckydraw_category->categories) {
                    $l_category = $luckydraw_category->categories->erp_category_id;
                    $l_categories_count[] = $l_category;
                }
            }
        }
        $luckydraw_categories_count = count($l_categories_count);

        $l_brands_count = [];
        $luckydraw_brands = LuckyDrawBrand::where('promotion_uuid', $lucky_draw_uuid)->with('brands')->get();
        if ($luckydraw_brands->count() > 0) {
            foreach ($luckydraw_brands as $luckydraw_brand) {
                if ($luckydraw_brand->brands) {
                    $l_brand = $luckydraw_brand->brands->product_brand_id;
                    $l_brands_count[] = $l_brand;
                }
            }
        }
        $luckydraw_brands_count = count($l_brands_count);

        $valid_productdata = [];
        $valid_total_price = 0;
        $valid = [];
        $db_ext = DB::connection('pos_pgsql');
        $checkInvoiceNo = Pos101SaleCashDocument::select('sale_cash_document_status_id', 'sale_cash_document_id', 'gbh_customer_id', 'sale_cash_document_no', 'branch_code', 'customer_code', 'sale_cash_document_datenow', 'voucher_value');
        //Get Invoice Info

        //check used invoice of deposit invoice
        $check_deposit_inovice = $db_ext->table('pledge.pledge_document')
            ->where('pledge.pledge_document.sale_command_document_no', $invoice_no)
            ->first();
        if ($check_deposit_inovice) {
            $check_deposit_inovice = $check_deposit_inovice->pledge_document_docno;
        }

        $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $check_deposit_inovice)->where('promotion_uuid', $lucky_draw_uuid)->first();

        if (isset($ticket_header_invoice)) {
            return response()->json(['error' => 'invoice_is_used_for_deposit_invoice'], 200);
        }
        $checkDeposit = null;
        if ($request->ticket_type == 3) {
            $checkDeposit = $db_ext->table('pledge.pledge_document')
                ->select('sale_command.sale_command_item.product_code', 'sale_command.sale_command_item.barcode_code', 'sale_command.sale_command_item.barcode_bill_name'
                    , 'sale_command.sale_command_document.net_amount as sale_price', 'temp_master_product.*', 'sale_command.sale_command_item.*', 'pledge.pledge_document.*')
                ->join('sale_command.sale_command_document', 'pledge.pledge_document.sale_command_document_no', 'sale_command.sale_command_document.sale_command_document_no')
                ->join('sale_command.sale_command_item', 'sale_command.sale_command_document.sale_command_document_id', 'sale_command.sale_command_item.sale_command_document_id')
                ->join('temp_master_product', 'temp_master_product.barcode_code', 'sale_command.sale_command_item.barcode_code')
                ->where('pledge.pledge_document.pledge_document_docno', $invoice_no)
                ->get();
        } else {
            $checkDeposit = collect([]);
            // //Check Member Coupun, Cash Coupon
            // $check_used_coupon =  $db_ext->table('sale_cash.sale_cash_document')

            // select voucher_value::numeric(19,0)
            // from sale_cash.sale_cash_document aa
            // inner join sale_cash.sale_cash_items bb
            // on aa.sale_cash_document_id= bb.sale_cash_document_id
            // where aa.sale_cash_document_no= 'ATY1ATY04CA-220401-0110'
            // limit 1

        }
        $checkInvoiceNo = $checkInvoiceNo->where('', $invoice_no)
        // ->whereDate('sale_cash_document_datenow', '<=', $lucky_draw->end_date)
            ->whereIn('sale_cash_document_type_id', ['1', '3', '4', '5', '10'])
            ->first();

        if ($checkDeposit->count() != 0) {
            foreach ($checkDeposit as $product) {
                //Find Customer Info
                if ($branch_id == 1) {
                    $customer = Pos101GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 2) {
                    $customer = Pos102GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 3) {
                    $customer = Pos103GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 9) {
                    $customer = Pos104GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 10) {
                    $customer = Pos105GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 11) {
                    $customer = Pos106GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 19) {
                    $customer = Pos107GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 21) {
                    $customer = Pos108GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 27) {
                    $customer = Pos112GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                if ($branch_id == 28) {
                    $customer = Pos113GbhCustomer::where('gbh_customer_id', $product->gbh_customer_id)->first();
                }
                //check Promotion time only on Special Ticket
                if ($ticket_type == 1) {
                    $invoice_date_time = strtotime(substr($product->sale_cash_document_datenow, 0, -19));

                    $lucky_draw_end_date = strtotime($lucky_draw->end_date);
                    $lucky_draw_start_date = strtotime($lucky_draw->start_date);
                    $today = strtotime(date('Y-m-d'));
                    if ($invoice_date_time > $lucky_draw_end_date) {
                        return response()->json(['error' => 'invoice_is_expired'], 200);
                    };
                    if ($lucky_draw_end_date < $today) {
                        return response()->json(['error' => 'promotion_expired'], 200);
                    };
                    if ($lucky_draw_start_date > $today) {
                        return response()->json(['error' => 'promotion_is_not_start'], 200);
                    };
                }

                //Check Discon Condition
                if ($lucky_draw->discon_status == 2) {

                    if (str_contains($product->barcode_bill_name, '(Discon)')) {
                        continue;
                    }
                };
                //Find with Product's Category and Brand
                if ($luckydraw_categories_count > 0 && $luckydraw_brands_count > 0) {
                    $product_category_id = $product->category_id;
                    $c_status = in_array($product_category_id, $l_categories_count);

                    $product_brand_id = $product->good_brand_id;
                    $b_status = in_array($product_brand_id, $l_brands_count);

                    if ($b_status == true && $c_status == true) {
                        if (!in_array($product, $valid_productdata, true)) {
                            $valid_productdata[] = $product;
                            $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product->price_sale_amount, '฿')));
                            if (str_contains($product->barcode_bill_name, 'Discount')) {
                                $valid_total_price -= $product_price;
                            } elseif (str_contains($product->barcode_code, 'GP')) {
                            } else {
                                $valid_total_price += $product_price;
                            }
                        }
                    }
                } else if ($luckydraw_categories_count > 0 && $luckydraw_brands_count == 0) {
                    $product_category_id = $product->category_id;
                    $c_status = in_array($product_category_id, $l_categories_count);

                    if ($c_status == true) {
                        if (!in_array($product, $valid_productdata, true)) {
                            $valid_productdata[] = $product;
                            $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product->price_sale_amount, '฿')));
                            if (str_contains($product->barcode_bill_name, 'Discount')) {
                                $valid_total_price -= $product_price;
                            } elseif (str_contains($product->barcode_code, 'GP')) {
                            } else {
                                $valid_total_price += $product_price;
                            }
                        }
                    }
                } else if ($luckydraw_categories_count == 0 && $luckydraw_brands_count > 0) {
                    $product_brand_id = $product->products['good_brand_id'];
                    $b_status = in_array($product_brand_id, $l_brands_count);
                    if ($b_status == true) {
                        if (!in_array($product, $valid_productdata, true)) {
                            $valid_productdata[] = $product;
                            $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product->price_sale_amount, '฿')));
                            if (str_contains($product->barcode_bill_name, 'Discount')) {
                                $valid_total_price -= $product_price;
                            } elseif (str_contains($product->barcode_code, 'GP')) {
                            } else {
                                $valid_total_price += $product_price;
                            }
                        }
                    }
                } else if ($luckydraw_categories_count == 0 && $luckydraw_brands_count == 0) {
                    if (!in_array($product, $valid_productdata, true)) {
                        $valid_productdata[] = $product;
                        $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product->price_sale_amount, '฿')));
                        if (str_contains($product->barcode_bill_name, 'Discount')) {
                            $valid_total_price -= $product_price;
                            $valid[] -= $product_price;
                        } elseif (str_contains($product->barcode_code, 'GP')) {
                        } else {
                            $valid_total_price += $product_price;
                            $valid[] += $product_price;
                        }
                    }
                }
                $c_status = false;
                $b_status = false;
            }

            //Check Deposit Amount or Valid Amount
            $get_pledge_document_total = intval(preg_replace('/[^\d.]/', '', ltrim($product->pledge_document_total, '฿')));
            if ($get_pledge_document_total < $valid_total_price) {
                $valid_total_price = $get_pledge_document_total;
            }

        } else {
            if ($checkInvoiceNo) {
                //1 New
                if ($request->ticket_type == 3) {
                    return response()->json(['error' => 'can_not_use_this_invoice'], 200);
                }
                if ($checkInvoiceNo->sale_cash_document_status_id != 1) {
                    return response()->json([' ' => 'can_not_use_this_invoice'], 200);
                }
                //Find Customer Info
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

                //check Promotion time only on Special Ticket
                if ($ticket_type == 1) {
                    $invoice_date_time = strtotime(substr($checkInvoiceNo->sale_cash_document_datenow, 0, -19));

                    $lucky_draw_end_date = strtotime($lucky_draw->end_date);
                    $lucky_draw_start_date = strtotime($lucky_draw->start_date);
                    $today = strtotime(date('Y-m-d'));
                    if ($invoice_date_time > $lucky_draw_end_date) {
                        return response()->json(['error' => 'invoice_is_expired'], 200);
                    };
                    if ($lucky_draw_end_date < $today) {
                        return response()->json(['error' => 'promotion_expired'], 200);
                    };
                    if ($lucky_draw_start_date > $today) {
                        return response()->json(['error' => 'promotion_is_not_start'], 200);
                    };
                }
                //Find Products
                if ($branch_id == 1) {
                    $products = Pos101SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 2) {
                    $products = Pos102SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 3) {
                    $products = Pos103SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 9) {
                    $products = Pos104SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 10) {
                    $products = Pos105SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 11) {
                    $products = Pos106SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 19) {
                    $products = Pos107SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 21) {
                    $products = Pos108SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 27) {
                    $products = Pos112SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                if ($branch_id == 28) {
                    $products = Pos113SaleCashItems::select('barcode_code', 'barcode_bill_name', 'sale_qty', 'sale_amount');
                }
                $products = $products->where('sale_cash_document_id', $checkInvoiceNo->sale_cash_document_id);

                //Check Discon Condition
                if ($lucky_draw->discon_status == 2) {
                    $products = $products->where('barcode_bill_name', 'not LIKE', '%(Discon)%');
                };
                $products = $products->with('products')->get();
                foreach ($products as $product) {
                    //check Promotion branch
                    if (isset($product->products)) {
                        //Find with Product's Category and Brand
                        if ($luckydraw_categories_count > 0 && $luckydraw_brands_count > 0) {
                            $product_category_id = $product->products['category_id'];
                            $c_status = in_array($product_category_id, $l_categories_count);

                            $product_brand_id = $product->products['good_brand_id'];
                            $b_status = in_array($product_brand_id, $l_brands_count);
                            if ($b_status == true && $c_status == true) {
                                if (!in_array($product, $valid_productdata, true)) {
                                    $valid_productdata[] = $product;

                                    $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product['sale_amount'], '฿')));
                                    if (str_contains($product->barcode_bill_name, 'Discount')) {
                                        $valid_total_price -= $product_price;
                                    } elseif (str_contains($product->barcode_code, 'GP')) {
                                    } else {
                                        $valid_total_price += $product_price;
                                    }
                                }
                            }
                        } else if ($luckydraw_categories_count > 0 && $luckydraw_brands_count == 0) {
                            $product_category_id = $product->products['category_id'];
                            $c_status = in_array($product_category_id, $l_categories_count);
                            if ($c_status == true) {
                                if (!in_array($product, $valid_productdata, true)) {
                                    $valid_productdata[] = $product;
                                    $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product['sale_amount'], '฿')));
                                    if (str_contains($product->barcode_bill_name, 'Discount')) {
                                        $valid_total_price -= $product_price;
                                    } elseif (str_contains($product->barcode_code, 'GP')) {
                                    } else {
                                        $valid_total_price += $product_price;
                                    }
                                }
                            }
                        } else if ($luckydraw_categories_count == 0 && $luckydraw_brands_count > 0) {
                            $product_brand_id = $product->products['good_brand_id'];
                            $b_status = in_array($product_brand_id, $l_brands_count);
                            if ($b_status == true) {
                                if (!in_array($product, $valid_productdata, true)) {
                                    $valid_productdata[] = $product;
                                    $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product['sale_amount'], '฿')));
                                    if (str_contains($product->barcode_bill_name, 'Discount')) {
                                        $valid_total_price -= $product_price;
                                    } elseif (str_contains($product->barcode_code, 'GP')) {
                                    } else {
                                        $valid_total_price += $product_price;
                                    }
                                }
                            }
                        } else if ($luckydraw_categories_count == 0 && $luckydraw_brands_count == 0) {
                            if (!in_array($product, $valid_productdata, true)) {
                                $valid_productdata[] = $product;
                                $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product['sale_amount'], '฿')));
                                if (str_contains($product->barcode_bill_name, 'Discount')) {
                                    $valid_total_price -= $product_price;
                                } elseif (str_contains($product->barcode_code, 'GP')) {
                                } else {
                                    $valid_total_price += $product_price;
                                }
                            }
                        }
                    } else {

                        if(!str_contains($product->barcode_code, 'GP')){
                            if (!in_array($product, $valid_productdata, true)) {
                                $b_status = false;
                                if ($product->products != null) {
                                    if ($luckydraw_categories_count > 0) {
                                        $product_category_id = $product->products['category_id'];
                                        $c_status = in_array($product_category_id, $l_categories_count);
                                    } else {
                                        $c_status = true;
                                    }
                                    if ($luckydraw_brands_count > 0) {
                                        $product_brand_id = $product->products['good_brand_id'];
                                        $b_status = in_array($product_brand_id, $l_brands_count);
                                    } else {
                                        $b_status = true;
                                    }
                                } else {
                                    $b_status = true;
                                    $c_status = true;
                                }
                                if ($b_status == true && $c_status == true) {
                                    $valid_productdata[] = $product;
                                    $product_price = intval(preg_replace('/[^\d.]/', '', ltrim($product['sale_amount'], '฿')));
                                    if (str_contains($product->barcode_bill_name, 'Discount')) {
                                        $valid_total_price -= $product_price;
                                    } else {
                                        $valid_total_price += $product_price;
                                    }
                                }
                            }
                        }
                    }
                    $c_status = false;
                    $b_status = false;
                }

                //Check Coupon
                //Get Voucher Value
                $get_voucher_value = intval(preg_replace('/[^\d.]/', '', ltrim($checkInvoiceNo->voucher_value, '฿')));
                if ($get_voucher_value > 0) {
                    $valid_total_price = $valid_total_price - $get_voucher_value;
                }
            }
        }

        //HERE
        if ($checkDeposit->count() != 0 || $checkInvoiceNo != null) {
            //Check Promotion Type
            if ($lucky_draw->lucky_draw_type_uuid == null) {
                //Calcualte valid total amount and valid qty of ticket based on valid product data
                $total_valid_ticket_qty = $valid_total_price / $lucky_draw['amount_for_one_ticket'];
                $total_valid_ticket_qty = (int) $total_valid_ticket_qty;
            } else {
                //Calcualte valid total amount and valid qty of ticket based on valid product data
                if ($valid_total_price >= $lucky_draw['amount_for_one_ticket']) {
                    $total_valid_ticket_qty = 1;
                } else {
                    $total_valid_ticket_qty = 0;
                }
            }

            DB::beginTransaction();
            $ticket_header_no = $this->generate_ticket_header_no(date(now()), $branch_id);

            //Store in Customer
            if (isset($request->ticket_header_uuid)) {
            } else {
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
            }

            //check ticket header to create or update in ticket header and ticket header invoice
            if (isset($request->ticket_header_uuid)) {
                $old_total_price = $ticket_header->total_valid_amount;
                $old_ticket_qty = $ticket_header->total_valid_ticket_qty;
                $new_valid_total_price = $valid_total_price + $old_total_price;
                //Check Promotion Type
                if ($lucky_draw->lucky_draw_type_uuid == null) {
                    //Calcualte valid total amount and valid qty of ticket based on valid product data
                    $new_total_valid_ticket_qty = $new_valid_total_price / $lucky_draw['amount_for_one_ticket'];
                    $new_total_valid_ticket_qty = (int) $new_total_valid_ticket_qty;
                } else {
                    //Calcualte valid total amount and valid qty of ticket based on valid product data
                    if ($new_valid_total_price >= $lucky_draw['amount_for_one_ticket']) {
                        $new_total_valid_ticket_qty = 1;
                    } else {
                        $new_total_valid_ticket_qty = 0;
                    }
                }

                $ticket_header->update([
                    'total_valid_amount' => $new_valid_total_price,
                    'total_valid_ticket_qty' => $new_total_valid_ticket_qty,
                ]);

            } else {
                $ticket_header = TicketHeader::create([
                    'uuid' => (string) Str::uuid(),
                    'promotion_uuid' => $lucky_draw_uuid,
                    'ticket_header_no' => $ticket_header_no,
                    'customer_uuid' => $ldcustomer->uuid,
                    'total_valid_amount' => $valid_total_price,
                    'total_valid_ticket_qty' => $total_valid_ticket_qty,
                    'created_at' => date(now()),
                    'created_by' => Auth::user()->uuid,
                    'status' => 1,
                    'branch_id' => $branch_id,
                    'ticket_type' => $ticket_type,
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
                'promotion_uuid' => $lucky_draw_uuid,
                'valid_amount' => $valid_total_price,
                'valid_ticket_qty' => $total_valid_ticket_qty,
            ]);

            DB::commit();
            //check same user
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

            //return total valid amount, total valid qty, invoice_id, invoice_no, invoive valid amount, ivoice valid qyt, customer info
            $spr['ticket_header_uuid'] = $ticket_header_uuid;
            $spr['message'] = 'successfully_created';
            return response()->json(['data' => $spr], 200);
        } else {
            return response()->json(['error' => 'invoice_is_not_found'], 200);
        }
        // }catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }


    public function update_ticket_header_customer(Request $request)
    {
        try {
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
            $ticket_header = $ticket_header->update([
                'customer_uuid' => $ldcustomer->uuid,
            ]);

            return true;
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public static function edit_ticket_header($uuid)
    {
        try {
            $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
            $luckydraws = LuckyDraw::where('status', 1)->get();
            $ticket_header = TicketHeader::where('uuid', $uuid)->first();
            $customer = null;
            if ($ticket_header->customer_uuid != 0) {
                $customer = Customer::where('uuid', $ticket_header->customer_uuid)->first();
            }
            $titles = ['Mr.', 'Ms.', 'Mrs.', 'LTD .', 'Plc .'];
            $nrc_nos = NRCNumber::get()->take(14);
            $nrc_names = NRCName::select('id', 'district')->get();
            $nrc_naings = NRCNaing::select('id', 'shortname')->get();

            $currentURL = URL::current();
            if (str_contains($currentURL, '192.168.21.242') || str_contains($currentURL,'tpluckydraw')) {
                $provinces = Pos102Province::get();
                $amphurs = Pos102Amphur::get();
            }
            if (str_contains($currentURL, '192.168.25.242') || str_contains($currentURL,'tpwluckydraw')) {
                $provinces = Pos106Province::get();
                $amphurs = Pos106Amphur::get();
            }
            if (str_contains($currentURL, '192.168.3.242')
            // ||str_contains($currentURL, '192.168.2.23')
            || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221') || str_contains($currentURL,'ltluckydraw')) {
                $provinces = Pos101Province::get();
                $amphurs = Pos101Amphur::get();
            }
            if (str_contains($currentURL, '192.168.11.242') || str_contains($currentURL,'ssluckydraw')
            // ||str_contains($currentURL, '192.168.2.23')
            ) {
                $provinces = Pos103Province::get();
                $amphurs = Pos103Amphur::get();
            }
            if (str_contains($currentURL, '192.168.16.242') || str_contains($currentURL,'edluckydraw')
            ||str_contains($currentURL, '192.168.2.23')) {
                $provinces = Pos104Province::get();
                $amphurs = Pos104Amphur::get();
            }
            if (str_contains($currentURL, '192.168.36.242') || str_contains($currentURL,'htyluckydraw')) {
                $provinces = Pos107Province::get();
                $amphurs = Pos107Amphur::get();
            }
            if (str_contains($currentURL, '192.168.31.242') || str_contains($currentURL,'mlmluckydraw')) {
                $provinces = Pos105Province::get();
                $amphurs = Pos105Amphur::get();
            }
            if (str_contains($currentURL, '192.168.41.242') || str_contains($currentURL,'atyluckydraw')) {
                $provinces = Pos108Province::get();
                $amphurs = Pos108Amphur::orderby('amphur_name', 'ASC')->get();
            }
            if (str_contains($currentURL, '192.168.46.242') || str_contains($currentURL,'tmluckydraw')) {
                $provinces = Pos112Province::get();
                $amphurs = Pos112Amphur::get();
            }
            if (str_contains($currentURL, '192.168.51.243') || str_contains($currentURL,'sdgluckydraw')) {
                $provinces = Pos113Province::get();
                $amphurs = Pos113Amphur::get();
            }
            if (str_contains($currentURL, '192.168.61.242') || str_contains($currentURL,'bagoluckydraw')) {
                $provinces = Pos110Province::get();
                $amphurs = Pos110Amphur::get();
            }
            $ticket_header_step_sales = TicketHeaderStepSale::where('ticket_header_uuid', $ticket_header->uuid)->get();
            $ticket_nos = Ticket::where('ticket_header_uuid', $ticket_header->uuid)->get();
            return view('tickets.edit', compact('branches', 'luckydraws', 'ticket_header', 'customer', 'titles', 'nrc_nos', 'nrc_names', 'nrc_naings', 'provinces', 'amphurs', 'ticket_nos', 'ticket_header_step_sales'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
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
    public function invoice_list_by_ticket_header(Request $request)
    {
        try {
            $ticket_header_uuid = (!empty($_GET["ticket_header_uuid"])) ? ($_GET["ticket_header_uuid"]) : ('');
            $result = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header_uuid)->get();
            // dd($result);
            return DataTables::of($result)
                ->addColumn('action', function ($result) {
                    return '<a class="badge bg-warning mr-2"  title="Delete" href="../../delete_invoice/' . $result->id . '"><i class="ri-delete-bin-line mr-0"></i></a>';
                })
                ->addColumn('valid_amount', function ($result) {
                    return number_format($result->valid_amount);
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function delete_invoice($id)
    {
        // try {
        //find invoice in ticket header invoice
        $ticket_header_invoice = TicketHeaderInvoice::where('id', $id)->first();

        $invoice_amount = $ticket_header_invoice->valid_amount;
        $invoice_ticket_qty = $ticket_header_invoice->valid_ticket_qty;

        //Check Ticke Generate
        $tickets = Ticket::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->first();
        if ($tickets) {
            return redirect()
                ->back()->withInput()
                ->with('error', 'Can not Remove invoice when ticket is generated');
        }
        //check generated step sale
        $t_h_step_sale = TicketHeaderStepSale::where('ticket_header_uuid', $ticket_header_invoice->ticket_header_uuid)->first();
        if (isset($t_h_step_sale)) {
            return redirect()
                ->back()->withInput()
                ->with('error', 'Can not Remove invoice when Step Sale is generated');
        }

        $ticket_header = TicketHeader::where('uuid', $ticket_header_invoice->ticket_header_uuid)->first();
        //Check Same User
        if ($ticket_header->created_by != Auth::user()->uuid) {
            return redirect()
                ->back()->withInput()
                ->with('error', 'Cannot Remove. This invoice is created by Other User');
        }

        //update amount and ticke qty in ticket header
        DB::beginTransaction();
        $lucky_draw = LuckyDraw::where('uuid', $ticket_header->promotion_uuid)->first();

        $update_total_valid_amount = $ticket_header->total_valid_amount - $invoice_amount;
        //Check Promotion Type
        if ($lucky_draw->lucky_draw_type_uuid == 'f782e7f2-39dd-42a3-98b4-de6e4430b6c2') {
            //Calcualte valid total amount and valid qty of ticket based on valid product data
            $update_invoice_ticket_qty = $update_total_valid_amount / $lucky_draw->amount_for_one_ticket;
            $update_invoice_ticket_qty = (int) $update_invoice_ticket_qty;
        } else {
            $update_invoice_ticket_qty = (int)$lucky_draw->total_valid_ticket_qty - 1;
        }
        $ticket_header->update([
            'total_valid_amount' => $update_total_valid_amount,
            'total_valid_ticket_qty' => $update_invoice_ticket_qty < 0 ? 0 : (int)$update_invoice_ticket_qty,
        ]);
        //delete invoice in ticket header invoice
        $ticket_header_invoice->delete();
        DB::commit();
        return redirect()->route('tickets.edit_ticket_header', $ticket_header->uuid)->with('success', 'Invoice is removed');
        // } catch (\Exception$e) {
        //     DB::rollback();
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function summary_ticket_header($uuid)
    {
        // try {
        $ticket_header = TicketHeader::where('uuid', $uuid)->first();

        $tickets = Ticket::where('ticket_header_uuid', $ticket_header->uuid)->get()->pluck('ticket_no')->toarray();
        //Generate PDF
        $first = reset($tickets);
        $last = end($tickets);

        $customer = $ticket_header->customers;
        $titlename = $customer->titlename ?? '';
        $firstname = $customer->firstname ?? '';
        $lastname = $customer->lastname ?? '';
        $nrc_number_name = $customer->NRCNumbers->nrc_number_name ?? '';
        $district = $customer->NRCNames->district ?? '';
        $shortname = $customer->NRCNaings->shortname ?? '';
        $nrc_number = $customer->nrc_number ?? '';

        $currentURL = URL::current();
            $amphur_name = $customer->amphurs->amphur_name ?? '';
            $province_name = $customer->provinces->province_name ?? '';

        $customer_name = $titlename . ' ' . $firstname . ' ' . $lastname;
        if ($nrc_number_name == '' || $district == '' || $shortname == '' || $nrc_number == '') {
            $customer_nrc = '';
        } else {
            $customer_nrc = $nrc_number_name . $district . $shortname . $nrc_number;
        }

        $customer_township = $amphur_name;
        $customer_region = $province_name;
        $invoice_nos = implode(", ", TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header->uuid)->get()->pluck('invoice_no')->toarray());

        $promotion_name = $ticket_header->promotions->name;
        $promotion_uuid = $ticket_header->promotions->uuid;
        $customers = [
            "ticket_nos" => $first . ' to ' . $last,
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
        // $mpdf = new MPDF(['mode' => 'utf-8', 'format' => [190, 236]]);
        $pdf = MPDF::loadView('tickets.tickets1', compact('data', 'customers', 'promotion_uuid'));


        $file = Storage::put('tickets/' . $ticket_header->uuid . '.pdf', $pdf->output());
        File::move(storage_path('app/tickets/' . $ticket_header->uuid . '.pdf'), public_path('tickets/' . $ticket_header->uuid . '.pdf'));

        $filename = $ticket_header->uuid;
        $ticket_header_step_sales = TicketHeaderStepSale::where('ticket_header_uuid', $ticket_header->uuid)->get();
        return view('tickets.summary', compact('ticket_header', 'customer', 'tickets', 'filename', 'ticket_header_step_sales'));
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function remove_ticket_file(Request $request)
    {
        try {
            $uuid = $request->ticket_header_uuid;
            $ticket_header = TicketHeader::where('uuid', $uuid)->first();
            $ticket_header->update([
                'printed_at' => date('Y-m-d H:i:s'),
                'printed_by' => Auth::user()->uuid,
            ]);
            $filename = $uuid . '.pdf';
            File::delete(public_path('tickets/' . $filename));
            return redirect()->route('tickets.edit_ticket_header', $uuid);
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public static function imagenABase64($ruta_relativa_al_public)
    {
        // try {
            $path = $ruta_relativa_al_public;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = File::get($path);

            $base64 = "";
            if ($type == "svg") {
                $base64 = "data:image/svg+xml;base64," . base64_encode($data);
            } else {
                $base64 = "data:image/" . $type . ";base64," . base64_encode($data);
            }
            return $base64;
        // } catch (\Exception$e) {
        //     return redirect()
        //         ->intended(route("ticket_header.index"))
        //         ->with('error', 'Fail to Download PDF!');
        // }
    }

    public function make_ticket_not_use(Request $request)
    {
        $uuid = $request->ticket_header_uuid;
        //Check Claimed
        $claim_history = ClaimHistory::where('ticket_header_uuid',$uuid)->first();

        // dd($claim_history);
        if($claim_history->print_status === 2){

            return response()->json(['error' => 'can_not_cancel_when_promotion_is_printed'], 200);
        }
        // dd('false');
        $ticket_header = TicketHeader::where('uuid', $uuid)->first();
        $ticket_header->update([
            'status' => 2,
            'canceled_at' => date('Y-m-d H:i:s'),
            'canceled_by' => Auth::user()->uuid,
        ]);
        return true;
    }

    public function do_not_use_ticket_listing(Request $request)
    {
        // try {
        $branch_array = BranchUser::where('user_uuid', auth()->user()->uuid)->get()->pluck('branch_id')->toarray();
        $promotions = LuckyDrawBranch::whereIn('branch_id', $branch_array)->with('promotions')->get();

        return view('tickets.do_not_use_ticket_listing', compact('promotions'));
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function search_do_not_use_ticket_list(Request $request)
    {
        $promotion_uuid = $request->lucky_draw_uuid;

        $ticket_no = Ticket::with('ticket_headers')->where('promotion_uuid', $promotion_uuid)
            ->whereHas('ticket_headers', function ($q) {
                $q->where('status', '=', 2);
            })
            ->get();

        return DataTables::of($ticket_no)
            ->editColumn('ticket_no', function ($data) {
                return $data->ticket_no;
            })
            ->editColumn('promotion_name', function ($data) {
                if (isset($data->ticket_headers->promotions)) {
                    return $data->ticket_headers->promotions->name;
                }
                return '';
            })
            ->editColumn('ticket_header_no', function ($data) {
                if (isset($data->ticket_headers->ticket_header_no)) {
                    return $data->ticket_headers->ticket_header_no;
                }
                return '';
            })
            ->editColumn('customer_name', function ($data) {
                if (isset($data->ticket_headers->customers)) {
                    return $data->ticket_headers->customers->firstname . $data->ticket_headers->customers->lastname;
                }
                return '';
            })
            ->editColumn('customer_phone_no', function ($data) {
                if (isset($data->ticket_headers->customers)) {
                    return $data->ticket_headers->customers->phone_no;
                }
                return '';
            })
            ->editColumn('cancel_at', function ($data) {
                if (isset($data->ticket_headers)) {
                    return $data->ticket_headers->canceled_at;
                }
                return '';
            })
            ->editColumn('cancel_user', function ($data) {
                // return $data->ticket_headers->canceled_users;
                if (isset($data->ticket_headers->canceled_users)) {
                    return $data->ticket_headers->canceled_users->name;
                }
                return '2';
            })
            ->addIndexColumn()
            ->make(true);
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function export_not_use_tickets(Request $request)
    {
        //check permission
        if (!Gate::allows('export-not-use-tickets')) {
            return response()->json(['error' => 'permission_denied'], 200);
        };
        // try {
        $promotion_uuid = $request->lucky_draw_uuid;
        return Excel::download(new DoNotUseTicketExport($promotion_uuid), 'DoNotUseTicket-Export.xlsx');
        // } catch (\Exception $e) {
        //     return redirect()
        //         ->intended(route("documents.index"))
        //         ->with('error', 'Fail to Excel Export!');
        // }
    }

    protected function my_promotion_connection()
    {
        return new MyPromotionController();
    }

    public function reprint($claim_history_uuid)
    {
        $claim_history = ClaimHistory::where('uuid',$claim_history_uuid)->first();

        //Generate Ticket Pdf
        $myPromotionController = $this->my_promotion_connection()->create_pdf($claim_history);
        return view('tickets.reprint_history', compact('claim_history'));
    }

    public function show($ticket_header_uuid)
    {
        $ticket_header = TicketHeader::with('customers')->where('uuid',$ticket_header_uuid)->first();

        return view('tickets.show', compact('ticket_header'));
    }

    public function claim_history_list()
    {
        // try {
            $ticket_header_uuid = (!empty($_GET["ticket_header_uuid"])) ? ($_GET["ticket_header_uuid"]) : ('');

            $result = ClaimHistory::with('promotion','sub_promotion')->where('ticket_header_uuid',$ticket_header_uuid)
            ->where('valid_qty','>',0)->get();

            return DataTables::of($result)
                ->addColumn('promotion',function($data){
                    if(isset($data->promotion)){
                        return $data->promotion->name;
                    }else{
                        return '';
                    }
                })
                ->addColumn('sub_promotion',function($data){
                    if(isset($data->sub_promotion)){
                        return $data->sub_promotion->name;
                    }else{
                        return '';
                    }
                })
                ->addColumn('choosed_qty',function($data){
                    if($data->choose_status == 2){
                        return (int)$data->valid_qty - (int)$data->remain_choose_qty;
                    }
                    return 0;
                })
                ->addColumn('claimed_qty',function($data){
                    if($data->claim_status == 2){
                        return (int)$data->valid_qty - (int)$data->remain_claim_qty;
                    }
                    return 0;
                })
                ->addColumn('printed_at',function($data){
                    if($data->print_status == 2){
                        return date('Y-m-d-h:i:s',strtotime($data->printed_at));
                    }
                    return '';
                })
                ->make(true);
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function add_reprint_history(Request $request)
    {
        $user_uuid =  $request->user_uuid;
        $claim_history_uuid =  $request->claim_history_uuid;
        ReprintHistory::create([
            'uuid' => (string) Str::uuid(),
            'printed_user_uuid' => $user_uuid,
            'claim_history_uuid' => $claim_history_uuid,
        ]);

        $filename = $claim_history_uuid . '.pdf';
        File::delete(public_path('tickets/' . $filename));
    }

    public function reprint_history_list()
    {
        // try {
            $claim_history_uuid = (!empty($_GET["claim_history_uuid"])) ? ($_GET["claim_history_uuid"]) : ('');

            $result = ReprintHistory::with('printed_user')->where('claim_history_uuid',$claim_history_uuid);

            return DataTables::of($result->orderby('created_at','desc'))
                ->addColumn('printed_user',function($data){
                    if(isset($data->printed_user)){
                        return $data->printed_user->name;
                    }else{
                        return '';
                    }
                })
                ->addColumn('printed_at',function($data){
                    return date('Y-m-d-h:i:s',strtotime($data->created_at));
                })
                ->make(true);
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }
}
