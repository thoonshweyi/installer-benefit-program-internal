<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\TicketHeader;
use App\Models\Lanthit\LanthitBrand;

class PromotionController extends Controller
{
    public function main_promotion()
    {
        $branches = Branch::select('branch_id', 'branch_name_eng')
            ->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28])
            ->get();
        $categories = Category::get();
        $brands = LanthitBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();

        return view('promotion.main-promotion', compact('branches', 'categories', 'brands'));
    }

   public function create_main_promotion()
   {
      return view('create_tickets.create-collect-invoice');
   }
    public function check_customer_info()
    {

        return view('create_tickets.check-customer-info');
    }
    public function check_choose_promotion($ticket_header_uuid)
    {
        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        return view('create_tickets.choose-promotions',compact('ticket_header'));
    }
    public function choose_promotions()
    {

        return view('create_tickets.choose-promotions');
    }
    public function claim_prizes()
    {
        return view('create_tickets.claim-prizes');
    }
    public function summary()
    {
        return view('create_tickets.summary');
    }

}
