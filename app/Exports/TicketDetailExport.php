<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\LuckyDraw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TicketDetailExport implements FromView
{
    public function __construct($uuid)
    {
        $this->promotion_uuid = $uuid;
    }
    
    protected function promotion_connection()
    {
        return new LuckyDraw();
    }

    public function view(): View
    {
        $promotion_uuid = $this->promotion_uuid;
        $promotion = $this->promotion_connection()->where('uuid',$promotion_uuid)->first();
        $result = Ticket::with('ticket_headers')
        ->whereHas('ticket_headers', function($q) use($promotion_uuid){
            $q->where('promotion_uuid', '=', $promotion_uuid)
            ->where('status', 1);
        })
        ->get();
        // dd($result);
        return view('reports.ticket_history.ticket_detail_export', compact('promotion','result'));
    }
}
