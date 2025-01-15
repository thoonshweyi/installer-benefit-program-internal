<?php

namespace App\Http\Controllers;

use App\Models\LuckyDrawBranch;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketReportController extends Controller
{
    public function today_ticket_detail($promotion_uuid,$branch_id)
    {
        $promotion= LuckyDrawBranch::where(['promotion_uuid'=>$promotion_uuid,'branch_id'=>$branch_id])->first();
        // dd($promotion->uuid);
        $tickets = Ticket::where('promotion_uuid',$promotion->promotion_uuid)->latest()->paginate(10);
        // dd($tickets);
        return view('ticket_reports.today',compact('tickets'));
    }
}

