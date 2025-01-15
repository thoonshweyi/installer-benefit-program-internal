<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Ticket;
use App\Models\Document;
use App\Models\Supplier;
use App\Models\LuckyDraw;
use App\Models\BranchUser;
use App\Models\TicketHeader;
use App\Models\DocumentStatus;
use App\Models\LuckyDrawBranch;
use App\Models\Bago\BagoCustomer;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Satsan\SatsanCustomer;
use App\Models\Lanthit\LanthitCustomer;
use App\Models\TheikPan\TheikPanCustomer;
use App\Models\EastDagon\EastDagonCustomer;
use App\Models\Tampawady\TampawadyCustomer;
use App\Models\TerminalM\TerminalMCustomer;
use App\Http\Controllers\DocumentController;
use App\Models\AyeTharyar\AyeTharyarCustomer;
use App\Models\Mawlamyine\MawlamyineCustomer;
use App\Models\SouthDagon\SouthDagonCustomer;
use App\Models\HlaingTharyar\HlaingTharyarCustomer;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-dashboard', ['only' => ['index']]);
    }

    protected function connection()
    {
        return new Document();
    }

    public function index()
    {
        // try {
            $totalUser = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'Admin');
            })->count();

            $promotionBranchs = $this->getActivePromotions();
            // $totalExchangeDoc = $this->getTotalExchangeDocument();

            // $completeReturnDoc = $this->getCompleteReturnDocument();
            // $completeExchangeDoc = $this->getCompleteExchangeDocument();
            // $overdueExchangeDoc = $this->getOverdueExchangeDocument();
             // if(count($document_checks) != 0){
            //     foreach ($document_checks as $document_check) {
            //         $document = new DocumentController;
            //         $return_document_doc_no = $document::generate_doc_no(1,date('Y-m-d H:i:s'));
            //         Document::where('id', $document_check->id)->update([
            //             'document_no' => $return_document_doc_no,
            //             'document_type' =>  1,
            //             'exchange_to_return' =>  date('Y-m-d H:i:s'),
            //             ]
            //         );
            //     }
            // }
            // $totalRole = Role::count();
            return view('home', compact('promotionBranchs'));
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("login"))
        //         ->with('error', 'Fail to View Home!');
        // }
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

        $branchCustomers = array_merge_recursive($ayetharyarCustomer, $eastdagonCustomer, $hlaingtharyarCustomer,
         $lanthitCustomer, $mawlamyineCustomer, $satsanCustomer, $tampawadyCustomer, $terminalCustomer, $theikpanCustomer,
         $southdagonCustomer,$bagoCustomer);

        dd($branchCustomers);
        foreach($branchCustomers as $bCustomer){
            dd($bCustomer);
            //Add or Update
            $customer = Customer::where('uuid',$bCustomer['uuid'])->first();
            if($customer){
                $customer->update($bCustomer);
            }else{
                Customer::create($bCustomer);
            }
        }
        # code...
    }

    public function getActivePromotions()
    {
        // try {
            $promotionBranchs = LuckyDrawBranch::
            select('promotion_branches.id as promotion_branches_id','promotions.uuid as promotion_uuid','promotions.name as promotion_name','branches.branch_id','branches.branch_name_eng')
            ->leftjoin('promotions','promotions.uuid','promotion_branches.promotion_uuid')
            ->leftjoin('branches','branches.branch_id','promotion_branches.branch_id')->where('promotions.status', 1)->orderby('promotion_branches.id','DESC')->get();
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

    public function findTodayTicket($promotion_uuid,$branch_id)
    {
        try {
            return $this->ticket_header_connection()
            ->where('ticket_headers.promotion_uuid', $promotion_uuid)
            ->join('tickets', 'tickets.ticket_header_uuid', 'ticket_headers.uuid')
            ->select('tickets.ticket_no', DB::raw('count(*) as total'))
            ->where('ticket_headers.branch_id', $branch_id)
            ->where('ticket_headers.status', 1)
            ->whereDate('ticket_headers.created_at', '=', date('Y-m-d'))
            ->groupBy('tickets.ticket_no')
            ->get()->count();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to get Complete Return Document!');
        }
    }

    public function findTotalTicket($promotion_uuid,$branch_id)
    {
        try {
            return $this->ticket_header_connection()
            ->where('ticket_headers.promotion_uuid', $promotion_uuid)
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

    public function getCompleteReturnDocument()
    {
        try {
            $user_branches = BranchUser::where('user_id', auth()->user()->id)->pluck('branch_id')->toArray();
            $document = $this->connection()->whereIn('branch_id', $user_branches);
            return $document->where('document_type', '1')->where('document_status', 9)->count();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to get Complete Return Document!');
        }
    }

    public function getCompleteExchangeDocument()
    {
        try {
            $user_branches = BranchUser::where('user_id', auth()->user()->id)->pluck('branch_id')->toArray();
            $document = $this->connection()->whereIn('branch_id', $user_branches);
            return $document->where('document_type', '2')->where('document_status', 11)->count();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to get Complete Exchange Document!');
        }
    }

    public function getOverdueExchangeDocument()
    {
        try {
            $user_branches = BranchUser::where('user_id', auth()->user()->id)->pluck('branch_id')->toArray();
            $document = $this->connection()->whereIn('branch_id', $user_branches);
            $inetrval = date('Y-m-d', strtotime(now() . ' - 14 days'));
            return $document->where('document_type', '=', '2')->where('deleted_at', null)
            ->where('operation_rg_in_updated_datetime', null)->where('operation_rg_out_updated_datetime', '<', $inetrval)->count();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to get Complete Exchange Document!');
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
}
