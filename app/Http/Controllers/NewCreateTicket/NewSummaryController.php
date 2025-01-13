<?php

namespace App\Http\Controllers\NewCreateTicket;

use App\Models\Customer;
use File;
use App\Models\ClaimHistory;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\LuckyDrawType;
use App\Http\Controllers\Controller;

class NewSummaryController extends Controller
{
    public function new_remove_ticket_file(Request $request)
    {
        $uuid = $request->claim_history_uuid;
        $claim_history = ClaimHistory::where('uuid', $uuid)->first();
        $claim_history->update([
            'printed_at' => date('Y-m-d H:i:s'),
            'print_status' => 2,
        ]);
        $filename = $uuid . '.pdf';
        // File::delete(public_path('tickets/' . $filename));
        $ticket_header_uuid = $claim_history->ticket_header_uuid;

        $promotion_types = LuckyDrawType::where('status', 1)
            ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
                $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
                    $query2->where('ticket_header_uuid', $ticket_header_uuid)
                        ->orderby('created_at', 'ASC');
                }])
                    ->where('status', 1);
            }])
            ->get();
    }


}
