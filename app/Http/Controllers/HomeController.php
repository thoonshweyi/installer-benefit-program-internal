<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Ticket;
use App\Models\Document;
use App\Models\Supplier;
use App\Models\LuckyDraw;
use App\Models\BranchUser;
use App\Models\ClaimHistory;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\DocumentStatus;
use App\Models\LuckyDrawBranch;
use App\Models\Bago\BagoCustomer;
use App\Models\CreditPointAdjust;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\CardNumberGenerator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ShwePyiThar\ShwePyiTharCustomer;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view-dashboard', ['only' => ['index']]);
    }

    protected function connection()
    {
        return new Document();
    }

    public function check_logedin(Request $request)
    {
        if($this->middleware('auth')){
            return redirect()->route('home');
        }else{
            return view('auth.login');
        }
    }

    public function index()
    {
        $branch_id = getCurrentBranch();

        $pending_installer_cards_count =  InstallerCard::
                                            where('branch_id',$branch_id)
                                            ->where('stage','pending')
                                            ->orderBy('id','desc')->count();


        $pending_card_number_generator_count =  CardNumberGenerator::
                                            where('branch_id',$branch_id)
                                            ->where('status','pending')
                                            ->orderBy('id','desc')->count();
        // dd($pending_card_number_generator_count);
        $pending_credit_point_adjust =  CreditPointAdjust::
                                                where('branch_id',$branch_id)
                                                ->where('status','pending')
                                                ->orderBy('id','desc')->count();

        return view('home', compact(
            'pending_installer_cards_count',
            'pending_card_number_generator_count',
            'pending_credit_point_adjust'
        ));

    }

    public function test()
    {
        $ayetharyarCustomer = AyeTharyarCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $eastdagonCustomer = EastDagonCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $hlaingtharyarCustomer = HlaingTharyarCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $lanthitCustomer = LanthitCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $mawlamyineCustomer = MawlamyineCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $satsanCustomer = SatsanCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $tampawadyCustomer = TampawadyCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $terminalCustomer = TerminalMCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $theikpanCustomer = TheikPanCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $southdagonCustomer = SouthDagonCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $bagoCustomer = BagoCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $branchCustomers = array_merge($ayetharyarCustomer, $eastdagonCustomer, $hlaingtharyarCustomer, $lanthitCustomer,
         $mawlamyineCustomer, $satsanCustomer, $tampawadyCustomer, $terminalCustomer, $theikpanCustomer,$southdagonCustomer,$bagoCustomer);


        # code...
    }

    public function getActivePromotions()
    {
        // try {
            $promotionBranchs = LuckyDrawBranch::
                select('promotion_branches.id as promotion_branches_id','promotions.uuid as promotion_uuid','promotions.name as promotion_name',
                'branches.branch_id','branches.branch_name_eng')
                ->leftjoin('promotions','promotions.uuid','promotion_branches.promotion_uuid')
                ->leftjoin('branches','branches.branch_id','promotion_branches.branch_id')
                ->where('promotions.status', 1)->orderby('branches.id','DESC')->get();
            return $promotionBranchs;

        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to get Total Return Document!');
        // }
    }
    protected function ticket_header_connection()
    {
        return new TicketHeader();
    }

    protected function claim_history_connection()
    {
        return new ClaimHistory();
    }

    public function findTodayTicket($promotion_uuid,$branch_id)
    {
        // try {

            return $this->claim_history_connection()
            ->where('claim_histories.promotion_uuid', $promotion_uuid)
            ->join('ticket_headers', 'ticket_headers.uuid', 'claim_histories.ticket_header_uuid')
            ->leftJoin('sub_promotions', 'sub_promotions.uuid', 'claim_histories.sub_promotion_uuid')
            ->join('tickets', 'tickets.ticket_header_uuid', 'ticket_headers.uuid')
            ->select('tickets.ticket_no', DB::raw('count(*) as total'))
            ->where('ticket_headers.branch_id', $branch_id)
            ->where('ticket_headers.status', 1)
            ->whereDate('ticket_headers.created_at', '=', date('Y-m-d'))
            ->groupBy('tickets.ticket_no')
            ->get()->count();
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to get Complete Return Document!');
        // }
    }

    public function findTotalTicket($promotion_uuid,$branch_id)
    {
        try {
            return $this->claim_history_connection()
            ->where('claim_histories.promotion_uuid', $promotion_uuid)
            ->join('ticket_headers', 'ticket_headers.uuid', 'claim_histories.ticket_header_uuid')
            ->join('tickets', 'tickets.ticket_header_uuid', 'ticket_headers.uuid')
            ->select('tickets.ticket_no', DB::raw('count(*) as total'))
            ->where('ticket_headers.branch_id', $branch_id)
            ->where('ticket_headers.status', 1)
            ->groupBy('tickets.ticket_no')
            ->get()->count();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to get Complete Return Document!');
        }
    }

    public function make_as_read($notification_id,$document_id)
    {
        $notification = auth()->user()->notifications()->find($notification_id);
        if($notification) {
            $notification->markAsRead();
        }
        return redirect()
                ->intended(route("documents.edit",$document_id));

    }

    public function notifications()
    {
        return number_convert(auth()->user()->unreadNotifications->count());
    }

    public function download_manual()
    {
        $file_path = public_path('images/user_manual/LD_User Manual _V1.1_08082022.pdf');
        return response()->download($file_path);
    }
}
