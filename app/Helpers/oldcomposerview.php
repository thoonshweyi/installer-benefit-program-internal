<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\LuckyDrawType;

class PromotionTypeComposer
{
    public function __construct($ticket_header_uuid = null)
    {
        $this->ticket_header_uuid = $ticket_header_uuid;
    }
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $ticket_header_uuid = request()->route() != null ?  request()->route()->ticket_header_uuid : null;

        // $promotion_types = LuckyDrawType::where('status', 1)
        // ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
        //     $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
        //         $query2->where('ticket_header_uuid', $ticket_header_uuid)
        //             ->orderby('created_at', 'ASC');
        //     }])
        //         ->where('status', 1);
        // }])
        // ->orderby('created_at', 'ASC')
        // ->get();
        $promotion_types = LuckyDrawType::where('status', 1)
        ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
            $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
                $query2->where('ticket_header_uuid', $ticket_header_uuid)
                    ->orderby('created_at', 'ASC');
            }])
                ->where('status', 1);
        }])
        ->orderby('created_at', 'ASC')
        ->get();
        // dd($promotion_types);
        if($promotion_types->count()==0)
        {
            return redirect()->route('login');
        }
        $view->with('promotion_types', $promotion_types);

    }

}
