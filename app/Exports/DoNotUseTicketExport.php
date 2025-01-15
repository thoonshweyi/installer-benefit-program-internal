<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\LuckyDraw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DoNotUseTicketExport implements FromView
{
    public function __construct($promotion_uuid)
    {
        $this->promotion_uuid = $promotion_uuid;
    }
    
    protected function promotion_connection()
    {
        return new LuckyDraw();
    }

    public function view(): View
    {
        $promotion = LuckyDraw::where('uuid',$this->promotion_uuid)->first();
        $result = Ticket::with('ticket_headers')
        ->where('promotion_uuid',$this->promotion_uuid)
        ->whereHas('ticket_headers', function($q) {
            $q->where('status', '=', 2);
        })
        ->get();
        return view('tickets.do_not_use_tickets', compact('promotion','result'));
    }
}
