<?php

namespace App\Http\Controllers;

use App\Models\Amphur;
use App\Models\Ticket;
use App\Models\Province;
use App\Models\LuckyDraw;
use App\Models\BranchUser;
use App\Models\IssuedPrize;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\LuckyDrawBranch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\TicketDetailExport;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CashCouponIssuedExport;
use App\Exports\CouponNumberDetailExport;
use App\Exports\CustomerNumberDetailExport;
use App\Exports\CustomerStatusNumberDetailExport;
use App\Exports\CustomerNumberCompareDetailExport;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-report|export-report');
    }

    protected function promotion_connection()
    {
        return new LuckyDraw();
    }

    protected function ticket_header_connection()
    {
        return new TicketHeader();
    }

    protected function ticket_connection()
    {
        return new Ticket();
    }
    protected function issued_prize_connection()
    {
        return new IssuedPrize();
    }
    public function ticket_history(Request $request)
    {
        //check permission
        if (!Gate::allows('view-report')) {
            return response()->json(['error' => 'permission_denied'], 200);
        };
        return view('reports.ticket_history.ticket_history');
    }

    public function ticket_history_search(Type $var = null)
    {
        $promotion_name = (!empty($_GET["promotion_name"])) ? ($_GET["promotion_name"]) : ('');
        $fromDate = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $toDate = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
        $result = $this->promotion_connection();
        if ($promotion_name != "") {
            $result = $result->where('name', 'ilike', '%' . $promotion_name . '%');
        }
        if (!empty($fromDate)) {
            $dateStr = str_replace("/", "-", $fromDate);
            $fromDate = date('Y/m/d H:i:s', strtotime($dateStr));
            $result = $result->whereDate('start_date', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $dateStr = str_replace("/", "-", $toDate);
            $toDate = date('Y/m/d H:i:s', strtotime($dateStr));
            $result = $result->whereDate('end_date', '<=', $toDate);
        }
        $result = $result->get();
        return DataTables::of($result)
            ->addIndexColumn()
            ->make(true);
        # code...
    }

    public function ticket_history_detail(Request $request, $uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();
        return view('reports.ticket_history.ticket_history_detail', compact('promotion'));
    }

    public function ticket_history_detail_search(Type $var = null)
    {
        $promotion_uuid = (!empty($_GET["promotion_uuid"])) ? ($_GET["promotion_uuid"]) : ('');
        $invoice_no = (!empty($_GET["invoice_no"])) ? ($_GET["invoice_no"]) : ('');
        $fromDate = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $toDate = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
        $result = $this->ticket_connection()->whereHas('ticket_headers', function ($q) {
            $q->where('status',1);
        });
        if ($promotion_uuid != "") {
            $result = $result->whereHas('ticket_headers', function ($q) use ($promotion_uuid) {
                $q->where('promotion_uuid', '=', $promotion_uuid)->where('status',1);
            });
        }

        if ($invoice_no != "") {
            $result = $result->whereHas('ticket_headers', function ($q) use ($invoice_no) {
                $q->whereHas('invoices', function ($r) use ($invoice_no) {
                    $r->where('invoice_no', '=', $invoice_no)->where('status',1);
                });
            });
        }

        if (!empty($fromDate)) {
            $dateStr = str_replace("/", "-", $fromDate);
            $fromDate = date('Y/m/d H:i:s', strtotime($dateStr));
            $result = $result->whereDate('created_at', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $dateStr = str_replace("/", "-", $toDate);
            $toDate = date('Y/m/d H:i:s', strtotime($dateStr));
            $result = $result->whereDate('created_at', '<=', $toDate);
        }
        return DataTables::of($result)
            ->editColumn('ticket_no', function ($data) {
                return $data->ticket_no;
            })
            ->editColumn('invoice_no', function ($data) {
                return '';
                if (isset($data->ticket_headers->invoices)) {
                    $invoices = '';
                    foreach ($data->ticket_headers->invoices as $invoice) {

                        $invoices .= $invoice->invoice_no . ',';
                    }
                    return rtrim($invoices, ", ");
                }
                return '';
            })
            ->editColumn('created_date', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->editColumn('branch_name', function ($data) {
                return $data->ticket_headers->branches->branch_name_eng;
            })
            ->editColumn('customer_name', function ($data) {
                return $data->ticket_headers->customers->firstname;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function ticket_detail(Request $request, $uuid)
    {
        $ticket = $this->ticket_connection()->where('uuid', $uuid)->with('ticket_headers')->first();
        return view('reports.ticket_history.ticket_detail', compact('ticket'));
    }

    public function ticket_detail_export(Request $request, $uuid)
    {
        //check permission
        if (!Gate::allows('export-report')) {
            return response()->json(['error' => 'permission_denied'], 200);
        };
        try {
            return Excel::download(new TicketDetailExport($uuid), 'TicketDetail-Export.xlsx');
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Excel Export!');
        }
    }

    public function customer_number(Request $request)
    {

        return view('reports.customer_number.customer_number');
    }

    public function customer_number_detail(Request $request, $uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();

        $lanthit_customer_total = $this->customer_total_by_branch_id($uuid, 1);
        $satsan_customer_total = $this->customer_total_by_branch_id($uuid, 3);
        $eastdagon_customer_total = $this->customer_total_by_branch_id($uuid, 9);
        $hlaingthaya_customer_total = $this->customer_total_by_branch_id($uuid, 19);
        $terminal_m_customer_total = $this->customer_total_by_branch_id($uuid, 27);
        $theikpan_customer_total = $this->customer_total_by_branch_id($uuid, 2);
        $tampawady_customer_total = $this->customer_total_by_branch_id($uuid, 11);
        $aye_thayar_customer_total = $this->customer_total_by_branch_id($uuid, 21);
        $mawlamyine_customer_total = $this->customer_total_by_branch_id($uuid, 10);
        $southdagon_customer_total = $this->customer_total_by_branch_id($uuid, 28);
        $total = $lanthit_customer_total + $satsan_customer_total + $eastdagon_customer_total + $hlaingthaya_customer_total + $terminal_m_customer_total + $theikpan_customer_total + $tampawady_customer_total + $aye_thayar_customer_total + $mawlamyine_customer_total + $southdagon_customer_total;

        return view('reports.customer_number.customer_number_detail', compact('promotion',
            'lanthit_customer_total', 'satsan_customer_total', 'eastdagon_customer_total',
            'hlaingthaya_customer_total', 'terminal_m_customer_total', 'theikpan_customer_total',
            'tampawady_customer_total', 'aye_thayar_customer_total', 'mawlamyine_customer_total', 'southdagon_customer_total', 'total'
        ));
    }

    public function one_customer_number_detail($promotion_uuid, $branch_id)
    {
        $promotion = $this->promotion_connection()->where('uuid', $promotion_uuid)->first();
        $branch_name = LuckyDrawBranch::where('branch_id', $branch_id)->with('branches')->first()->branches->branch_name_eng;
        $data_total = $this->customer_total_detail_by_branch_id($promotion, $branch_id);
        $customer_total = $data_total['customer_total'];
        $date_array = $data_total['date_array'];
        return view('reports.customer_number.one_customer_number_detail', compact('promotion', 'branch_name', 'customer_total', 'date_array'));
    }

    public function customer_number_detail_graph(Request $request, $uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();

        $lanthit_customer_total = $this->customer_total_by_branch_id($uuid, 1);
        $satsan_customer_total = $this->customer_total_by_branch_id($uuid, 3);
        $eastdagon_customer_total = $this->customer_total_by_branch_id($uuid, 9);
        $hlaingthaya_customer_total = $this->customer_total_by_branch_id($uuid, 19);
        $terminal_m_customer_total = $this->customer_total_by_branch_id($uuid, 27);
        $theikpan_customer_total = $this->customer_total_by_branch_id($uuid, 2);
        $tampawady_customer_total = $this->customer_total_by_branch_id($uuid, 11);
        $aye_thayar_customer_total = $this->customer_total_by_branch_id($uuid, 21);
        $mawlamyine_customer_total = $this->customer_total_by_branch_id($uuid, 10);
        $southdagon_customer_total = $this->customer_total_by_branch_id($uuid, 28);

        $lt_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 1);
        $lanthit_customer_total_detail = $lt_customer_total_detail['customer_total'];
        $satsan_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 3)['customer_total'];
        $eastdagon_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 9)['customer_total'];
        $hlaingthaya_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 19)['customer_total'];
        $terminal_m_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 27)['customer_total'];
        $theikpan_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 2)['customer_total'];
        $tampawady_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 11)['customer_total'];
        $aye_thayar_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 21)['customer_total'];
        $mawlamyine_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 10)['customer_total'];
        $southdagon_customer_total_detail = $this->customer_total_detail_by_branch_id($promotion, 28)['customer_total'];

        $days = substr(substr(implode(",", $lt_customer_total_detail['date_array']), 1), 0, -1);
        $days = $lt_customer_total_detail['date_array'];

        return view('reports.customer_number.customer_number_detail_graph', compact('days',
            'lanthit_customer_total', 'satsan_customer_total', 'eastdagon_customer_total',
            'hlaingthaya_customer_total', 'terminal_m_customer_total', 'theikpan_customer_total',
            'tampawady_customer_total', 'aye_thayar_customer_total', 'mawlamyine_customer_total','southdagon_customer_total',
            'lanthit_customer_total_detail', 'satsan_customer_total_detail', 'eastdagon_customer_total_detail',
            'hlaingthaya_customer_total_detail', 'terminal_m_customer_total_detail', 'theikpan_customer_total_detail',
            'tampawady_customer_total_detail', 'aye_thayar_customer_total_detail', 'mawlamyine_customer_total_detail', 'southdagon_customer_total_detail'
        ));
    }

    public function customer_number_detail_export(Request $request, $promotion_uuid)
    {

        try {
            return Excel::download(new CustomerNumberDetailExport($promotion_uuid), 'CustomerNumberDetail-Export.xlsx');
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to Excel Export!');
        }
    }

    public function customer_total_by_branch_id($promotion_uuid, $branch_id)
    {
        return $this->ticket_header_connection()
            ->select('customer_uuid', DB::raw('count(*) as total'))
            ->where('promotion_uuid', $promotion_uuid)
            ->where('branch_id', $branch_id)
            ->groupBy('customer_uuid')
            ->where('status',1)
            ->get()->sum('total');
    }

    public function customer_total_detail_by_branch_id($promotion, $branch_id)
    {
        $promotion_uuid = $promotion->uuid;
        $different_day = $this->get_different_days_by_promotion($promotion) + 1;
        $customer_total = [];
        for ($x = 0; $x < $different_day; $x++) {
            $date = date('Y-m-d H:i:s', strtotime($promotion->start_date . ' + ' . $x . 'day'));
            $udate = date('d-m-y', strtotime($promotion->start_date . ' + ' . $x . 'day'));
            $datearray[] = $udate;
            $customer_total[] = $this->ticket_header_connection()
                ->where('promotion_uuid', $promotion_uuid)
                ->select('customer_uuid', DB::raw('count(*) as total'))
                ->where('branch_id', $branch_id)
                ->whereDate('created_at', '=', $date)
                ->groupBy('customer_uuid')
                ->where('status',1)
                ->get()->sum('total');
        }
        $data['customer_total'] = $customer_total;
        $data['date_array'] = $datearray;
        return $data;
    }

    public function get_different_days_by_promotion($promotion)
    {

        $start_date = date_create($promotion->start_date);
        // $end_date = date_create($promotion->end_date);
        $end_date = $this->ticket_header_connection()->where('promotion_uuid', $promotion->uuid)->where('status',1)->orderBy('created_at', 'desc')->first();
        if($end_date){
            $end_date = $end_date->created_at;
        }else{
            $end_date = date_create(date('Y-m-d H:i:s'));
        }
        $different_day = date_diff($start_date, $end_date);
        $different_day = $different_day->format('%R%a');
        return $different_day = (int) ltrim($different_day, '+');

    }

    public function coupon_number(Request $request)
    {
        return view('reports.coupon_number.coupon_number');
    }

    public function coupon_number_detail(Request $request, $uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();

        $lanthit_coupon_total = $this->coupon_total_by_branch_id($uuid, 1);
        $satsan_coupon_total = $this->coupon_total_by_branch_id($uuid, 3);
        $eastdagon_coupon_total = $this->coupon_total_by_branch_id($uuid, 9);
        $hlaingthaya_coupon_total = $this->coupon_total_by_branch_id($uuid, 19);
        $terminal_m_coupon_total = $this->coupon_total_by_branch_id($uuid, 27);
        $theikpan_coupon_total = $this->coupon_total_by_branch_id($uuid, 2);
        $tampawady_coupon_total = $this->coupon_total_by_branch_id($uuid, 11);
        $aye_thayar_coupon_total = $this->coupon_total_by_branch_id($uuid, 21);
        $mawlamyine_coupon_total = $this->coupon_total_by_branch_id($uuid, 10);
        $southdagon_coupon_total = $this->coupon_total_by_branch_id($uuid, 28);

        $total = $lanthit_coupon_total + $satsan_coupon_total + $eastdagon_coupon_total + $hlaingthaya_coupon_total + $terminal_m_coupon_total + $theikpan_coupon_total + $tampawady_coupon_total + $aye_thayar_coupon_total + $mawlamyine_coupon_total + $southdagon_coupon_total;
        
        return view('reports.coupon_number.coupon_number_detail', compact('promotion', 'total',
            'lanthit_coupon_total', 'satsan_coupon_total', 'eastdagon_coupon_total',
            'hlaingthaya_coupon_total', 'terminal_m_coupon_total', 'theikpan_coupon_total',
            'tampawady_coupon_total', 'aye_thayar_coupon_total', 'mawlamyine_coupon_total', 'southdagon_coupon_total'
        ));
    }

    public function one_coupon_number_detail($promotion_uuid, $branch_id)
    {
        $promotion = $this->promotion_connection()->where('uuid', $promotion_uuid)->first();
        $main_copuon_total = $this->coupon_total_detail_by_branch_id($promotion, $branch_id);
        $copuon_total = $main_copuon_total['coupon_total'];
        $days = $main_copuon_total['date_array'];
        return view('reports.coupon_number.one_coupon_number_detail', compact('promotion', 'copuon_total', 'days'));
    }

    public function coupon_number_detail_graph(Request $request, $uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();

        $lanthit_coupon_total = $this->coupon_total_by_branch_id($uuid, 1);
        $satsan_coupon_total = $this->coupon_total_by_branch_id($uuid, 3);
        $eastdagon_coupon_total = $this->coupon_total_by_branch_id($uuid, 9);
        $hlaingthaya_coupon_total = $this->coupon_total_by_branch_id($uuid, 19);
        $terminal_m_coupon_total = $this->coupon_total_by_branch_id($uuid, 27);
        $theikpan_coupon_total = $this->coupon_total_by_branch_id($uuid, 2);
        $tampawady_coupon_total = $this->coupon_total_by_branch_id($uuid, 11);
        $aye_thayar_coupon_total = $this->coupon_total_by_branch_id($uuid, 21);
        $mawlamyine_coupon_total = $this->coupon_total_by_branch_id($uuid, 10);
        $southdagon_coupon_total = $this->coupon_total_by_branch_id($uuid, 28);

        $lt_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 1);
        $southdagon_coupon_total_detail = $lt_coupon_total_detail['coupon_total'];

        $lanthit_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 1)['coupon_total'];
        $satsan_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 3)['coupon_total'];
        $eastdagon_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 9)['coupon_total'];
        $hlaingthaya_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 19)['coupon_total'];
        $terminal_m_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 27)['coupon_total'];
        $theikpan_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 2)['coupon_total'];
        $tampawady_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 11)['coupon_total'];
        $aye_thayar_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 21)['coupon_total'];
        $mawlamyine_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 10)['coupon_total'];
        $southdagon_coupon_total_detail = $this->coupon_total_detail_by_branch_id($promotion, 28)['coupon_total'];

        $days = $lt_coupon_total_detail['date_array'];
        return view('reports.coupon_number.coupon_number_detail_graph', compact('days',
            'lanthit_coupon_total', 'satsan_coupon_total', 'eastdagon_coupon_total',
            'hlaingthaya_coupon_total', 'terminal_m_coupon_total', 'theikpan_coupon_total',
            'tampawady_coupon_total', 'aye_thayar_coupon_total', 'mawlamyine_coupon_total', 'southdagon_coupon_total',
            'lanthit_coupon_total_detail', 'satsan_coupon_total_detail', 'eastdagon_coupon_total_detail',
            'hlaingthaya_coupon_total_detail', 'terminal_m_coupon_total_detail', 'theikpan_coupon_total_detail',
            'tampawady_coupon_total_detail', 'aye_thayar_coupon_total_detail', 'mawlamyine_coupon_total_detail', 'southdagon_coupon_total_detail'
        ));
    }

    public function coupon_number_detail_export(Request $request, $promotion_uuid)
    {

        try {
            return Excel::download(new CouponNumberDetailExport($promotion_uuid), 'CouponNumberDetail-Export.xlsx');
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to Excel Export!');
        }
    }
    public function coupon_total_by_branch_id($promotion_uuid, $branch_id)
    {
        return $this->ticket_header_connection()
            ->where('ticket_headers.promotion_uuid', $promotion_uuid)
            ->join('tickets', 'tickets.ticket_header_uuid', 'ticket_headers.uuid')
            ->select('tickets.ticket_no', DB::raw('count(*) as total'))
            ->where('ticket_headers.branch_id', $branch_id)
            ->where('ticket_headers.status', 1)
            ->groupBy('tickets.ticket_no')
            ->get()->count();
    }

    public function coupon_total_detail_by_branch_id($promotion, $branch_id)
    {
        $promotion_uuid = $promotion->uuid;
        $different_day = $this->get_different_days_by_promotion($promotion) + 1;
        $coupon_total = [];
        for ($x = 0; $x < $different_day; $x++) {
            $date = date('Y-m-d H:i:s', strtotime($promotion->start_date . ' + ' . $x . 'day'));
            $udate = date('d-m-y', strtotime($promotion->start_date . ' + ' . $x . 'day'));
            $datearray[] = $udate;
            $coupon_total[] = $this->ticket_header_connection()
                ->where('ticket_headers.promotion_uuid', $promotion_uuid)
                ->join('tickets', 'tickets.ticket_header_uuid', 'ticket_headers.uuid')
                ->select('tickets.ticket_no', DB::raw('count(*) as total'))
                ->where('ticket_headers.branch_id', $branch_id)
                ->whereDate('tickets.created_at', '=', $date)
                ->where('ticket_headers.status', 1)
                ->groupBy('tickets.ticket_no')
                ->get()->count();
        }
        $data['coupon_total'] = $coupon_total;
        $data['date_array'] = $datearray;
        return $data;
    }

    public function customer_number_compare(Request $request)
    {
        $promotions = $this->promotion_connection()::all();
        return view('reports.customer_number_compare.customer_number_compare', compact('promotions'));
    }

    public function calculate_customer_number_compare(Request $request)
    {
        $compare_name = (!empty($_GET["compare_name"])) ? ($_GET["compare_name"]) : ('');
        $promotion_uuid = (!empty($_GET["promotion_uuid"])) ? ($_GET["promotion_uuid"]) : ('');
        $promotions = $this->promotion_connection()::all();
        //only two promotion
        if (count($promotion_uuid) > 2) {
            return redirect()->back()->withInput()->with('error', 'Only for two Promotion');
        }
        $header = ['Branches'];
        $lanthit = ['Lanthit'];
        $satsan = ['Satsan'];
        $eastdagon = ['Eastdagon'];
        $hlaingthaya = ['Hlaingthaya'];
        $terminal_m = ['Terminal M'];
        $theikpan = ['Theikpan'];
        $tampawady = ['Tampawady'];
        $aye_thayar = ['Aye Thayar'];
        $mawlamyine = ['Mawlamyine'];
        $southdagon = ['South Dagon'];

        $used_promotions = $this->promotion_connection()->wherein('uuid', $promotion_uuid)->get();

        if ($compare_name == 1) {
            foreach ($used_promotions as $u_promotion) {
                array_push($header, $u_promotion->name);
                $lanthit_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 1);
                array_push($lanthit, $lanthit_customer_total);
                $satsan_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 3);
                array_push($satsan, $satsan_customer_total);
                $eastdagon_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 9);
                array_push($eastdagon, $eastdagon_customer_total);
                $hlaingthaya_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 19);
                array_push($hlaingthaya, $hlaingthaya_customer_total);
                $terminal_m_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 27);
                array_push($terminal_m, $terminal_m_customer_total);
                $theikpan_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 2);
                array_push($theikpan, $theikpan_customer_total);
                $tampawady_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 11);
                array_push($tampawady, $tampawady_customer_total);
                $aye_thayar_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 21);
                array_push($aye_thayar, $aye_thayar_customer_total);
                $mawlamyine_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 10);
                array_push($mawlamyine, $mawlamyine_customer_total);
                $southdagon_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 28);
                array_push($southdagon, $southdagon_customer_total);
            }
            $title_name = 'Customer Number';
        } else {
            foreach ($used_promotions as $u_promotion) {
                array_push($header, $u_promotion->name);
                $lanthit_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 1);
                array_push($lanthit, $lanthit_customer_total);
                $satsan_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 3);
                array_push($satsan, $satsan_customer_total);
                $eastdagon_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 9);
                array_push($eastdagon, $eastdagon_customer_total);
                $hlaingthaya_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 19);
                array_push($hlaingthaya, $hlaingthaya_customer_total);
                $terminal_m_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 27);
                array_push($terminal_m, $terminal_m_customer_total);
                $theikpan_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 2);
                array_push($theikpan, $theikpan_customer_total);
                $tampawady_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 11);
                array_push($tampawady, $tampawady_customer_total);
                $aye_thayar_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 21);
                array_push($aye_thayar, $aye_thayar_customer_total);
                $mawlamyine_customer_total = $this->coupon_total_by_branch_id($u_promotion->uuid, 10);
                array_push($mawlamyine, $mawlamyine_customer_total);
                $southdagon_customer_total = $this->customer_total_by_branch_id($u_promotion->uuid, 28);
                array_push($southdagon, $southdagon_customer_total);
            }
            $title_name = 'Coupon Number';
        }

        array_push($header, 'Gr');
        $lanthit_gr = $lanthit['2'] == 0 ? 0 : ($lanthit['1'] - $lanthit['2']) / $lanthit['2'] * 100;
        array_push($lanthit, $lanthit_gr);
        $satsan_gr = $satsan['2'] == 0 ? 0 : ($satsan['1'] - $satsan['2']) / $satsan['2'] * 100;
        array_push($satsan, $satsan_gr);
        $eastdagon_gr = $eastdagon['2'] == 0 ? 0 : ($eastdagon['1'] - $eastdagon['2']) / $eastdagon['2'] * 100;
        array_push($eastdagon, $eastdagon_gr);
        $hlaingthaya_gr = $hlaingthaya['2'] == 0 ? 0 : ($hlaingthaya['1'] - $hlaingthaya['2']) / $hlaingthaya['2'] * 100;
        array_push($hlaingthaya, $hlaingthaya_gr);
        $terminal_m_gr = $terminal_m['2'] == 0 ? 0 : ($terminal_m['1'] - $terminal_m['2']) / $terminal_m['2'] * 100;
        array_push($terminal_m, $terminal_m_gr);
        $theikpan_gr = $theikpan['2'] == 0 ? 0 : ($theikpan['1'] - $theikpan['2']) / $theikpan['2'] * 100;
        array_push($theikpan, $theikpan_gr);
        $tampawady_gr = $tampawady['2'] == 0 ? 0 : ($tampawady['1'] - $tampawady['2']) / $tampawady['2'] * 100;
        array_push($tampawady, $tampawady_gr);
        $aye_thayar_gr = $aye_thayar['2'] == 0 ? 0 : ($aye_thayar['1'] - $aye_thayar['2']) / $aye_thayar['2'] * 100;
        array_push($aye_thayar, $aye_thayar_gr);
        $mawlamyine_gr = $mawlamyine['2'] == 0 ? 0 : ($mawlamyine['1'] - $mawlamyine['2']) / $mawlamyine['2'] * 100;
        array_push($mawlamyine, $mawlamyine_gr);
        $southdagon_gr = $southdagon['2'] == 0 ? 0 : ($southdagon['1'] - $southdagon['2']) / $southdagon['2'] * 100;
        array_push($southdagon, $southdagon_gr);

        //Graph Data
        $promotion1_name = $header[1];
        $promotion2_name = $header[2];
        $promotion1_lanthit = $lanthit[1];
        $promotion2_lanthit = $lanthit[2];
        $promotion1_satsan = $satsan[1];
        $promotion2_satsan = $satsan[2];
        $promotion1_eastdagon = $eastdagon[1];
        $promotion2_eastdagon = $eastdagon[2];
        $promotion1_hlaingthaya = $hlaingthaya[1];
        $promotion2_hlaingthaya = $hlaingthaya[2];
        $promotion1_terminal_m = $terminal_m[1];
        $promotion2_terminal_m = $terminal_m[2];
        $promotion1_theikpan = $theikpan[1];
        $promotion2_theikpan = $theikpan[2];
        $promotion1_tampawady = $tampawady[1];
        $promotion2_tampawady = $tampawady[2];
        $promotion1_aye_thayar = $aye_thayar[1];
        $promotion2_aye_thayar = $aye_thayar[2];
        $promotion1_mawlamyine = $mawlamyine[1];
        $promotion2_mawlamyine = $mawlamyine[2];
        $promotion1_southdagon = $southdagon[1];
        $promotion2_southdagon = $southdagon[2];
        $result = [
            $lanthit, $satsan, $eastdagon, $hlaingthaya, $terminal_m, $theikpan, $tampawady, $aye_thayar, $mawlamyine, $southdagon,
        ];

        return view('reports.customer_number_compare.calculate_customer_number_compare', compact('promotions', 'header', 'compare_name', 'result', 'used_promotions', 'compare_name', 'title_name', 'promotion1_name', 'promotion2_name', 'promotion1_lanthit', 'promotion2_lanthit', 'promotion1_satsan', 'promotion2_satsan', 'promotion1_eastdagon', 'promotion2_eastdagon', 'promotion1_hlaingthaya', 'promotion2_hlaingthaya', 'promotion1_terminal_m', 'promotion2_terminal_m', 'promotion1_theikpan', 'promotion2_theikpan', 'promotion1_tampawady', 'promotion2_tampawady', 'promotion1_aye_thayar', 'promotion2_aye_thayar', 'promotion1_mawlamyine', 'promotion2_mawlamyine', 'promotion1_southdagon', 'promotion2_southdagon'));
    }

    public function customer_number_compare_export(Request $request, $compare_name, $promotion_uuid)
    {
        $promotion_uuid = explode(",", $promotion_uuid);
        if (count($promotion_uuid) > 2) {
            return redirect()->back()->withInput()->with('error', 'Only for two Promotion');
        }
        return Excel::download(new CustomerNumberCompareDetailExport($promotion_uuid, $compare_name), 'CustomerNumberCompareDetail-Export.xlsx');
    }

    public function customer_status_number(Request $request)
    {
        return view('reports.customer_status_number.customer_status_number');
    }

    public function customer_status_number_detail(Request $request, $uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();
        $header = ['Branches', 'Member', 'Old Customer', 'New Customer'];
        $lanthit = ['Lanthit'];
        $satsan = ['Satsan'];
        $eastdagon = ['Eastdagon'];
        $hlaingthaya = ['Hlaingthaya'];
        $terminal_m = ['Terminal M'];
        $theikpan = ['Theikpan'];
        $tampawady = ['Tampawady'];
        $aye_thayar = ['Aye Thayar'];
        $mawlamyine = ['Mawlamyine'];
        $southdagon = ['South Dagon'];

        //Find Member Total
        $lanthit_member_total = $this->member_total_by_branch_id($uuid, 1);
        array_push($lanthit, $lanthit_member_total);
        $satsan_member_total = $this->member_total_by_branch_id($uuid, 3);
        array_push($satsan, $satsan_member_total);
        $eastdagon_member_total = $this->member_total_by_branch_id($uuid, 9);
        array_push($eastdagon, $eastdagon_member_total);
        $hlaingthaya_member_total = $this->member_total_by_branch_id($uuid, 19);
        array_push($hlaingthaya, $hlaingthaya_member_total);
        $terminal_m_member_total = $this->member_total_by_branch_id($uuid, 27);
        array_push($terminal_m, $terminal_m_member_total);
        $theikpan_member_total = $this->member_total_by_branch_id($uuid, 2);
        array_push($theikpan, $theikpan_member_total);
        $tampawady_member_total = $this->member_total_by_branch_id($uuid, 11);
        array_push($tampawady, $tampawady_member_total);
        $aye_thayar_member_total = $this->member_total_by_branch_id($uuid, 21);
        array_push($aye_thayar, $aye_thayar_member_total);
        $mawlamyine_member_total = $this->member_total_by_branch_id($uuid, 10);
        array_push($mawlamyine, $mawlamyine_member_total);
        $southdagon_member_total = $this->member_total_by_branch_id($uuid, 28);
        array_push($southdagon, $southdagon_member_total);

        //Find Old Customer Total
        $lanthit_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 1);
        array_push($lanthit, $lanthit_old_customer_total);
        $satsan_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 3);
        array_push($satsan, $satsan_old_customer_total);
        $eastdagon_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 9);
        array_push($eastdagon, $eastdagon_old_customer_total);
        $hlaingthaya_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 19);
        array_push($hlaingthaya, $hlaingthaya_old_customer_total);
        $terminal_m_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 27);
        array_push($terminal_m, $terminal_m_old_customer_total);
        $theikpan_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 2);
        array_push($theikpan, $theikpan_old_customer_total);
        $tampawady_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 11);
        array_push($tampawady, $tampawady_old_customer_total);
        $aye_thayar_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 21);
        array_push($aye_thayar, $aye_thayar_old_customer_total);
        $mawlamyine_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 10);
        array_push($mawlamyine, $mawlamyine_old_customer_total);
        $southdagon_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 28);
        array_push($southdagon, $southdagon_old_customer_total);

        //Find New Customer Total
        $lanthit_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 1);
        array_push($lanthit, $lanthit_new_customer_total);
        $satsan_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 3);
        array_push($satsan, $satsan_new_customer_total);
        $eastdagon_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 9);
        array_push($eastdagon, $eastdagon_new_customer_total);
        $hlaingthaya_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 19);
        array_push($hlaingthaya, $hlaingthaya_new_customer_total);
        $terminal_m_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 27);
        array_push($terminal_m, $terminal_m_new_customer_total);
        $theikpan_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 2);
        array_push($theikpan, $theikpan_new_customer_total);
        $tampawady_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 11);
        array_push($tampawady, $tampawady_new_customer_total);
        $aye_thayar_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 21);
        array_push($aye_thayar, $aye_thayar_new_customer_total);
        $mawlamyine_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 10);
        array_push($mawlamyine, $mawlamyine_new_customer_total);
        $southdagon_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 28);
        array_push($southdagon, $southdagon_new_customer_total);

        $total = ['Total'];

        $total_member = $lanthit_member_total + $satsan_member_total + $eastdagon_member_total + $hlaingthaya_member_total + $terminal_m_member_total + $theikpan_member_total +
            $tampawady_member_total + $aye_thayar_member_total + $mawlamyine_member_total + $southdagon_member_total;
        array_push($total, $total_member);

        $total_old_customer = $lanthit_old_customer_total + $satsan_old_customer_total + $eastdagon_old_customer_total + $hlaingthaya_old_customer_total + $terminal_m_old_customer_total + $theikpan_old_customer_total +
            $tampawady_old_customer_total + $aye_thayar_old_customer_total + $mawlamyine_old_customer_total + $southdagon_old_customer_total;
        array_push($total, $total_old_customer);

        $total_new_customer = $lanthit_new_customer_total + $satsan_new_customer_total + $eastdagon_new_customer_total + $hlaingthaya_new_customer_total + $terminal_m_new_customer_total + $theikpan_new_customer_total +
            $tampawady_new_customer_total + $aye_thayar_new_customer_total + $mawlamyine_new_customer_total + $southdagon_new_customer_total;
        array_push($total, $total_new_customer);

        $result = [
            $lanthit, $satsan, $eastdagon, $hlaingthaya, $terminal_m, $theikpan, $tampawady, $aye_thayar, $mawlamyine, $southdagon, $total,
        ];
        return view('reports.customer_status_number.customer_status_number_detail', compact('promotion', 'header', 'result'));
    }

    public function member_total_by_branch_id($promotion_uuid, $branch_id)
    {
        return $this->ticket_header_connection()
            ->where('promotion_uuid', $promotion_uuid)
            ->select('customer_uuid', DB::raw('count(*) as total'))
            ->whereHas('customers', function ($q) {
                $q->where('customer_type', '=', 'Member');
            })
            ->where('branch_id', $branch_id)
            ->groupBy('customer_uuid')
            ->get()->count();
    }

    public function old_customer_total_by_branch_id($promotion_uuid, $branch_id)
    {
        return $this->ticket_header_connection()
            ->where('promotion_uuid', $promotion_uuid)
            ->select('customer_uuid', DB::raw('count(*) as total'))
            ->whereHas('customers', function ($q) {
                $q->where('customer_type', '=', 'Old');
            })
            ->where('branch_id', $branch_id)
            ->groupBy('customer_uuid')
            ->get()->count();
    }

    public function new_customer_total_by_branch_id($promotion_uuid, $branch_id)
    {
        return $this->ticket_header_connection()
            ->where('promotion_uuid', $promotion_uuid)
            ->select('customer_uuid', DB::raw('count(*) as total'))
            ->whereHas('customers', function ($q) {
                $q->where('customer_type', '=', 'New');
            })
            ->where('branch_id', $branch_id)
            ->groupBy('customer_uuid')
            ->get()->count();
    }

    public function customer_status_number_detail_graph($uuid)
    {
        $promotion = $this->promotion_connection()->where('uuid', $uuid)->first();
        //Find Member Total
        $lanthit_member_total = $this->member_total_by_branch_id($uuid, 1);
        $satsan_member_total = $this->member_total_by_branch_id($uuid, 3);
        $eastdagon_member_total = $this->member_total_by_branch_id($uuid, 9);
        $hlaingthaya_member_total = $this->member_total_by_branch_id($uuid, 19);
        $terminal_m_member_total = $this->member_total_by_branch_id($uuid, 27);
        $theikpan_member_total = $this->member_total_by_branch_id($uuid, 2);
        $tampawady_member_total = $this->member_total_by_branch_id($uuid, 11);
        $aye_thayar_member_total = $this->member_total_by_branch_id($uuid, 21);
        $mawlamyine_member_total = $this->member_total_by_branch_id($uuid, 10);
        $southdagon_member_total = $this->member_total_by_branch_id($uuid, 28);

        //Find Old Customer Total
        $lanthit_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 1);
        $satsan_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 3);
        $eastdagon_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 9);
        $hlaingthaya_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 19);
        $terminal_m_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 27);
        $theikpan_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 2);
        $tampawady_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 11);
        $aye_thayar_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 21);
        $mawlamyine_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 10);
        $southdagon_old_customer_total = $this->old_customer_total_by_branch_id($uuid, 28);

        //Find New Customer Total
        $lanthit_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 1);
        $satsan_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 3);
        $eastdagon_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 9);
        $hlaingthaya_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 19);
        $terminal_m_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 27);
        $theikpan_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 2);
        $tampawady_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 11);
        $aye_thayar_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 21);
        $mawlamyine_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 10);
        $southdagon_new_customer_total = $this->new_customer_total_by_branch_id($uuid, 28);

        $total_member = $lanthit_member_total + $satsan_member_total + $eastdagon_member_total + $hlaingthaya_member_total + $terminal_m_member_total + $theikpan_member_total +
            $tampawady_member_total + $aye_thayar_member_total + $mawlamyine_member_total + $southdagon_member_total;

        $total_old_customer = $lanthit_old_customer_total + $satsan_old_customer_total + $eastdagon_old_customer_total + $hlaingthaya_old_customer_total + $terminal_m_old_customer_total + $theikpan_old_customer_total +
            $tampawady_old_customer_total + $aye_thayar_old_customer_total + $mawlamyine_old_customer_total + $southdagon_old_customer_total;

        $total_new_customer = $lanthit_new_customer_total + $satsan_new_customer_total + $eastdagon_new_customer_total + $hlaingthaya_new_customer_total + $terminal_m_new_customer_total + $theikpan_new_customer_total +
            $tampawady_new_customer_total + $aye_thayar_new_customer_total + $mawlamyine_new_customer_total + $southdagon_new_customer_total;

        return view('reports.customer_status_number.customer_status_number_detail_graph', compact(
            'promotion',
            'total_member', 'total_old_customer', 'total_new_customer',
            'lanthit_member_total', 'lanthit_old_customer_total', 'lanthit_new_customer_total',
            'satsan_member_total', 'satsan_old_customer_total', 'satsan_new_customer_total',
            'eastdagon_member_total', 'eastdagon_old_customer_total', 'eastdagon_new_customer_total',
            'hlaingthaya_member_total', 'hlaingthaya_old_customer_total', 'hlaingthaya_new_customer_total',
            'terminal_m_member_total', 'terminal_m_old_customer_total', 'terminal_m_new_customer_total',
            'theikpan_member_total', 'theikpan_old_customer_total', 'theikpan_new_customer_total',
            'tampawady_member_total', 'tampawady_old_customer_total', 'tampawady_new_customer_total',
            'aye_thayar_member_total', 'aye_thayar_old_customer_total', 'aye_thayar_new_customer_total',
            'mawlamyine_member_total', 'mawlamyine_old_customer_total', 'mawlamyine_new_customer_total',
            'southdagon_member_total', 'southdagon_old_customer_total', 'southdagon_new_customer_total',
        ));
    }

    public function customer_status_number_export($promotion_uuid)
    {
        return Excel::download(new CustomerStatusNumberDetailExport($promotion_uuid), 'CustomerStatusNumberDetail-Export.xlsx');
    }

    public function customer_tickets_by_area()
    {
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        $promotions = [];
        $branch_id = 0;
        $promotion_uuid = 0;
        $amphurs = null;
        $provinces = null;
        return view('reports.customer_tickets_by_area', compact('branches','promotions','branch_id','promotion_uuid','amphurs','provinces'));
    }

    public function search_customer_tickets_by_area(Request $request,$branch_id,$promotion_uuid)
    {
        $amphurs = $this->ticket_header_connection()
        ->select('customers.amphur_id', DB::raw('count(*) as total'))
        ->groupBy('customers.amphur_id')
        ->join('customers','customers.uuid','ticket_headers.customer_uuid')
        ->where('promotion_uuid',$promotion_uuid)->where('branch_id',$branch_id)
        ->get();

        foreach($amphurs as $a){
            $amphur_name = $this->get_amphur_name_by_amphur_id($a->amphur_id);
            $a->amphur_id = $amphur_name;
        }

        $provinces = $this->ticket_header_connection()
        ->select('customers.province_id', DB::raw('count(*) as total'))
        ->groupBy('customers.province_id')
        ->leftjoin('customers','customers.uuid','ticket_headers.customer_uuid')
        ->where('promotion_uuid',$promotion_uuid)->where('branch_id',$branch_id)
        ->get();
        foreach($provinces as $p){
            $province_name = $this->get_province_name_by_province_id($p->province_id);
            $p->province_id = $province_name;
        }
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        $promotions = LuckyDrawBranch::where('branch_id', $branch_id)->with('promotions')->get();
        return view('reports.customer_tickets_by_area', compact('branches','amphurs','provinces','branch_id','promotions','promotion_uuid'));
    }

    public function get_amphur_name_by_amphur_id($amphur_id)
    {
        $amphur = Amphur::where('amphur_id',$amphur_id)->first();
        if(isset($amphur->amphur_name)){
            return $amphur->amphur_name;
        }else{
            return 'unknow amphur';
        };
    }

    public function get_province_name_by_province_id($province_id)
    {
        $province = Province::where('province_id',$province_id)->first();
        if(isset($province->province_name)){
            return $province->province_name;
        }else{
            return 'unknow province';
        };
        return Province::where('province_id',$province_id)->first()->province_name;
    }
    public function cash_coupon_issued_report()
    {
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        $promotions = LuckyDraw::get();
        $branch_id = 0;
        $promotion_uuid = 0;
        $amphurs = null;
        $provinces = null;
        return view('reports.cash_coupon_issued_report', compact('branches','promotions','branch_id','promotion_uuid','amphurs','provinces','promotions'));
    }
    public function cash_coupon_customer_used_report()
    {
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        $promotions = LuckyDraw::get();
        $branch_id = 0;
        $promotion_uuid = 0;
        $amphurs = null;
        $provinces = null;
        return view('reports.cash_coupon_customer_used_report', compact('branches','promotions','branch_id','promotion_uuid','amphurs','provinces','promotions'));
    }
    public function luckydraw_issued_report()
    {
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        $promotions = LuckyDraw::get();
        $branch_id = 0;
        $promotion_uuid = 0;
        $amphurs = null;
        $provinces = null;
        return view('reports.luckydraw_issued_report', compact('branches','promotions','branch_id','promotion_uuid','amphurs','provinces','promotions'));
    }
    public function gold_ring_and_gold_coin_issued_report()
    {
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        $promotions = LuckyDraw::get();
        $branch_id = 0;
        $promotion_uuid = 0;
        $amphurs = null;
        $provinces = null;
        return view('reports.gold_ring_and_gold_coin_issued_report', compact('branches','promotions','branch_id','promotion_uuid','amphurs','provinces','promotions'));
    }
    public function issued_result(Request $request)
    {
        $lucky_draw_name = (!empty($_GET["lucky_draw_name"])) ? ($_GET["lucky_draw_name"]) : ('');
        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');

        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
        $result = $this->issued_prize_connection()->with('promotions');
        if ($lucky_draw_name != "") {
            $result = $result->whereHas('promotions', function ($q) use ($lucky_draw_name) {
                $q->where('uuid', 'ilike', '%' . $lucky_draw_name . '%');
            });
        }
        if ($start_date != "") {
            $dateStr = str_replace("/", "-", $start_date);
            $start_date = date('Y/m/d H:i:s', strtotime($dateStr));
            $result = $result->whereDate('prize_date', '>=' ,$start_date);
        }
        if ($end_date != "") {
            $dateStr = str_replace("/", "-", $end_date);
            $end_date = date('Y/m/d H:i:s', strtotime($dateStr));
            $result = $result->whereDate('prize_date', '<=' , $start_date);

        }
            return DataTables::of($result)
              ->editColumn('branch_id', function ($data) {
                if (isset($data->branch_id)) {
                    return $data->branches->branch_name_eng;
                }
                return '';
                })
                ->editColumn('customer_uuid', function ($data) {
                    if (isset($data->customer_uuid)) {
                        return $data->customers->firstname;
                    }
                return '';
                })
                ->addColumn('customer_no', function ($data) {
                    if (isset($data->customer_uuid)) {
                        return $data->customers->customer_no;
                    }
                    return '';
                })
                ->editColumn('customer_name', function ($data) {
                    if (isset($data->customer_uuid)) {
                        return $data->customers->firstname;
                    }
                return '';
                })
                ->editColumn('total_amount', function ($data) {
                    if (isset($data->prize_amount)) {
                        return $data->prize_amount;
                    }
                    return '';
                })
                ->addColumn('invoice_no', function ($data) {
                    if (isset($data->ticket_header_uuid)) {
                        return $data->ticket_header_invoice->invoice_no;
                    }
                    return '';
                })
                ->editColumn('promotion_uuid', function ($data) {
                    if (isset($data->promotion_uuid)) {
                        return $data->promotions->name;
                    }
                    return '';
                })
            ->addIndexColumn()
            ->make(true);
        }

    public function cash_coupon_issued_export(Request $request,$uuid,$start_date,$end_date)
    {
        if($uuid){
            return Excel::download(new CashCouponIssuedExport($uuid,$start_date,$end_date), 'cash_coupon_export.xlsx');
        }else{
             return 'Error';
        }
    }
}
