<?php

namespace App\Http\Controllers\NewCreateTicket;

use App\Models\Customer;
use App\Models\LuckyDraw;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\LuckyDrawType;
use App\Models\LuckyDrawBrand;
use App\Models\LuckyDrawBranch;
use App\Models\LuckyDrawCategory;
use App\Models\POS101\{Pos101Amphur,Pos101GbhCustomer};
use App\Models\POS102\Pos102Amphur;
use App\Models\POS103\Pos103Amphur;
use App\Models\POS104\Pos104Amphur;
use App\Models\POS105\Pos105Amphur;
use App\Models\POS106\Pos106Amphur;
use App\Models\POS107\Pos107Amphur;
use App\Models\POS108\Pos108Amphur;
use App\Models\POS112\Pos112Amphur;
use App\Models\POS110\Pos110Amphur;
use App\Models\POS113\Pos113Amphur;
use App\Models\POS114\Pos114Amphur;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\POS101\Pos101Province;
use App\Models\POS102\Pos102Province;
use App\Models\POS103\Pos103Province;
use App\Models\POS104\Pos104Province;
use App\Models\POS105\Pos105Province;
use App\Models\POS106\Pos106Province;
use App\Models\POS107\Pos107Province;
use App\Models\POS108\Pos108Province;
use App\Models\POS112\Pos112Province;
use App\Models\POS113\Pos113Province;
use App\Models\POS114\Pos114Province;
use App\Models\POS110\Pos110Province;
use App\Models\{Province,Amphur, NRC};

class NewDesignController extends Controller
{
    public function all_layout()
    {
        return view('new_create_tickets.old_all_layout');
    }

    public function create_ticket()
    {
        $provinces      = Province::get();
        $amphurs        = Amphur::get();
        $data           = NRC::get();
        $customer       = new Customer();
        $gbh_customer   = new Pos101GbhCustomer();
        return view('new_create_tickets.layout', compact('provinces','amphurs','data','customer','gbh_customer'));
    }

    public function new_invoices()
    {
        return view('new_create_tickets.new_invoices');
    }

    public function promotion_info($lucky_draw_uuid)
    {
        $lucky_draw = LuckyDraw::where('uuid',$lucky_draw_uuid)->first();
        $lucky_draw_branches = LuckyDrawBranch::where('promotion_uuid',$lucky_draw_uuid)->first();
        if(!$lucky_draw_branches){
            $lucky_draw_branches = 'All Branch';
        }else{
            $lucky_draw_branches = LuckyDrawBranch::where('promotion_uuid',$lucky_draw_uuid)->with('branches')->get()->pluck('branches')->toarray();
            $branch_array = [];
            foreach ($lucky_draw_branches as $branch) {
                $branch_array[] = $branch['branch_name_eng'];
            }
            $lucky_draw_branches = implode(" ",$branch_array);
        }

        $lucky_draw_categories = LuckyDrawCategory::where('promotion_uuid',$lucky_draw_uuid)->first();
        if(!$lucky_draw_categories){
            $lucky_draw_categories = 'All Category';
        }else{
            $lucky_draw_categories = LuckyDrawCategory::where('promotion_uuid',$lucky_draw_uuid)->with('categories')->get()->pluck('categories')->toarray();
            $category_array = [];
            foreach ($lucky_draw_categories as $category) {
                $category_array[] = $category['name'];
            }

            $lucky_draw_categories = implode(" ",$category_array);
        }
        $lucky_draw_brands = LuckyDrawBrand::where('promotion_uuid',$lucky_draw_uuid)->first();
        if(!$lucky_draw_brands){
            $lucky_draw_brands = 'All Brand';
        }else{
            $lucky_draw_brands = LuckyDrawBrand::where('promotion_uuid',$lucky_draw_uuid)->with('brands')->get()->pluck('brands')->toarray();
            $brand_array = [];
            foreach ($lucky_draw_brands as $brand) {
                $brand_array[] = $brand['product_brand_name'];
            }
            $lucky_draw_brands = implode(" ",$brand_array);
        }
        $lucky_draw_type_name = LuckyDrawType::where('uuid',$lucky_draw->lucky_draw_type_uuid)->first();
        if($lucky_draw_type_name){
            $lucky_draw_type_name = $lucky_draw_type_name->name;
        }else{
            $lucky_draw_type_name = 'Normal Lucky Draw Type';
        }
        return  response()->json([
            'lucky_draw' => $lucky_draw->name,
            'lucky_draw_type' => $lucky_draw_type_name,
            'lucky_draw_branches' => $lucky_draw_branches,
            'lucky_draw_categories' => $lucky_draw_categories,
            'lucky_draw_brands' => $lucky_draw_brands,
            'lucky_draw_discon' => $lucky_draw->discon_status == 1 ? 'Include' : 'Exclude' ,
            'lucky_draw_start_date' => $lucky_draw->start_date ,
            'lucky_draw_end_date' => $lucky_draw->end_date,
        ], 200);
    }

}
