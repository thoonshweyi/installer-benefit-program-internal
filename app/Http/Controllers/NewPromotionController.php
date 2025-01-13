<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\LuckyDraw;
use Illuminate\Support\Str;
use App\Models\ProductCheck;
use App\Models\SubPromotion;
use Illuminate\Http\Request;
use App\Models\LuckyDrawType;
use App\Models\Bago\BagoBrand;
use App\Models\LuckyDrawBrand;
use App\Models\LuckyDrawBranch;
use App\Models\LuckyDrawCategory;
use App\Models\PromotionChangeLog;
use App\Models\Satsan\SatsanBrand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Lanthit\LanthitBrand;
use App\Models\PromotionSubPromotion;
use App\Models\Satsan\SatsanLuckyDraw;
use App\Models\TheikPan\TheikPanBrand;
use App\Models\EastDagon\EastDagonBrand;
use App\Models\Lanthit\LanthitLuckyDraw;
use App\Models\Tampawady\TampawadyBrand;
use App\Models\TerminalM\TerminalMBrand;
use App\Models\Satsan\SatsanTicketHeader;
use App\Models\AyeTharyar\AyeTharyarBrand;
use App\Models\Mawlamyine\MawlamyineBrand;
use App\Models\SouthDagon\SouthDagonBrand;
use App\Models\TheikPan\TheikPanLuckyDraw;
use App\Models\Lanthit\LanthitTicketHeader;
use App\Models\EastDagon\EastDagonLuckyDraw;
use App\Models\ShwePyiThar\ShwePyiTharBrand;
use App\Models\Tampawady\TampawadyLuckyDraw;
use App\Models\TerminalM\TerminalMLuckyDraw;
use App\Models\TheikPan\TheikPanTicketHeader;
use App\Models\AyeTharyar\AyeTharyarLuckyDraw;
use App\Models\Mawlamyine\MawlamyineLuckyDraw;
use App\Models\SouthDagon\SouthDagonLuckyDraw;
use App\Models\EastDagon\EastDagonTicketHeader;
use App\Models\Tampawady\TampawadyTicketHeader;
use App\Models\TerminalM\TerminalMTicketHeader;
use App\Models\HlaingTharyar\HlaingTharyarBrand;
use App\Models\AyeTharyar\AyeTharyarTicketHeader;
use App\Models\Mawlamyine\MawlamyineTicketHeader;
use App\Models\SouthDagon\SouthDagonTicketHeader;
use App\Models\HlaingTharyar\HlaingTharyarLuckyDraw;
use App\Models\HlaingTharyar\HlaingTharyarTicketHeader;

class NewPromotionController extends Controller
{
    public function index()
    {
        return view('promotion.index');
    }

    public function create()
    {
        // $branches = Branch::select('branch_id', 'branch_name_eng')
        //     ->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28,23,30])
        //     ->get();
        $branches = Branch::all();
        $categories = Category::get();
        $currentURL = URL::current();

        $brands = LanthitBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();

       
        $lucky_draw_types = LuckyDrawType::where('status', 1)->get();

        return view('promotion.create', compact('branches', 'categories', 'brands', 'lucky_draw_types'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:50|unique:promotions,name',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'discon_status' => 'required',
            'lucky_draw_type_uuid' => 'required',
        ]);
        if (str_contains($request->name, '  ')) {
            return redirect()->back()->withInput()->with('error', 'Can not use "Double Space" for Promotion Name');
        }

        if (str_contains($request->name, '-')) {
            return redirect()->back()->withInput()->with('error', 'Can not use "-" for Promotion Name');
        }

        $lucky_draw_request['uuid'] = (string) Str::uuid();
        $lucky_draw_request['name'] = $request->name;
        $lucky_draw_request['start_date'] = $request->start_date;
        $lucky_draw_request['end_date'] = $request->end_date;
        $lucky_draw_request['status'] = $request->status;
        $lucky_draw_request['amount_for_one_ticket'] = $request->amount_for_one_ticket;
        $lucky_draw_request['remark'] = $request->remark;
        $lucky_draw_request['discon_status'] = $request->discon_status;
        $lucky_draw_request['lucky_draw_type_uuid'] = $request->lucky_draw_type_uuid;
        $lucky_draw_request['diposit_type_id'] = $request->diposit_type_id;
        DB::beginTransaction();

        $lucky_draw = LuckyDraw::create($lucky_draw_request);

        ////old info,new info,reason,promotion_uuid
        create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion-' . $lucky_draw->name, $lucky_draw->uuid);

        $branch_ids = $request->branch_id;
        if ($branch_ids == null && $request->select_all_branch == null) {
            return redirect()->back()->withInput()->with('error', 'Branch is required');
        }
        $category_ids = $request->category_id;
        if ($category_ids == null && $request->select_all_category == null) {
            return redirect()->back()->withInput()->with('error', 'Category is required');
        }
        $brand_ids = $request->brand_id;
        if ($brand_ids == null && $request->select_all_brand == null) {
            return redirect()->back()->withInput()->with('error', 'Brand is required');
        }

        // if (!$request->select_all_branch) {
        //     foreach ($branch_ids as $branch_id) {
        //         $luckydrawBranch['promotion_uuid'] = $lucky_draw->uuid;
        //         $luckydrawBranch['branch_id'] = $branch_id;
        //         LuckyDrawBranch::create($luckydrawBranch);
        //         if ($branch_id == 1) {
        //             $lanthit_lucky_draw = LanthitLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $lanthit_lucky_draw->uuid;
        //             LanthitLuckyDrawBranch::create($luckydrawBranch);

        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion-' . $lucky_draw->name . 'in Lanthit', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $lanthit_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     LanthitLuckyDrawCategory::create($luckydrawCategory);

        //                 }
        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $lanthit_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     LanthitLuckyDrawBrand::create($luckydrawBrand);

        //                 }
        //             }
        //         }
        //         if ($branch_id == 2) {
        //             $theikpan_lucky_draw = TheikPanLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $theikpan_lucky_draw->uuid;
        //             TheikPanLuckyDrawBranch::create($luckydrawBranch);

        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in TheikPan', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $theikpan_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     TheikPanLuckyDrawCategory::create($luckydrawCategory);

        //                 }
        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $theikpan_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     TheikPanLuckyDrawBrand::create($luckydrawBrand);

        //                 }
        //             }
        //         }
        //         if ($branch_id == 3) {
        //             $satsan_lucky_draw = SatsanLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $satsan_lucky_draw->uuid;
        //             SatsanLuckyDrawBranch::create($luckydrawBranch);
        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in Satsan', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $satsan_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     SatsanLuckyDrawCategory::create($luckydrawCategory);

        //                 }
        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $satsan_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     SatsanLuckyDrawBrand::create($luckydrawBrand);

        //                 }
        //             }
        //         }
        //         if ($branch_id == 9) {
        //             $eastdagon_lucky_draw = EastDagonLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $eastdagon_lucky_draw->uuid;
        //             EastDagonLuckyDrawBranch::create($luckydrawBranch);

        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in EastDagon', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $eastdagon_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     EastDagonLuckyDrawCategory::create($luckydrawCategory);

        //                 }
        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $eastdagon_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     EastDagonLuckyDrawBrand::create($luckydrawBrand);

        //                 }
        //             }
        //         }
        //         if ($branch_id == 10) {
        //             $mawlamyine_lucky_draw = MawlamyineLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $mawlamyine_lucky_draw->uuid;
        //             MawlamyineLuckyDrawBranch::create($luckydrawBranch);

        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in Mawlamyine', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $mawlamyine_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     MawlamyineLuckyDrawCategory::create($luckydrawCategory);
        //                 }
        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $mawlamyine_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     MawlamyineLuckyDrawBrand::create($luckydrawBrand);
        //                 }
        //             }
        //         }
        //         if ($branch_id == 11) {
        //             $tampawady_lucky_draw = TampawadyLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $tampawady_lucky_draw->uuid;
        //             TampawadyLuckyDrawBranch::create($luckydrawBranch);

        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in Tampawady', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $tampawady_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     TampawadyLuckyDrawCategory::create($luckydrawCategory);
        //                 }

        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $tampawady_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     TampawadyLuckyDrawBrand::create($luckydrawBrand);
        //                 }

        //             }
        //         }
        //         if ($branch_id == 19) {
        //             $hlaingtharyar_lucky_draw = HlaingTharyarLuckyDraw::create($lucky_draw_request);
        //             $luckydrawBranch['promotion_uuid'] = $hlaingtharyar_lucky_draw->uuid;
        //             HlaingTharyarLuckyDrawBranch::create($luckydrawBranch);

        //             ////old info,new info,reason,promotion_uuid
        //             create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in HlaingTharyar', $lanthit_lucky_draw->uuid);

        //             if (!$request->select_all_category) {
        //                 foreach ($category_ids as $category_id) {
        //                     $luckydrawCategory['promotion_uuid'] = $hlaingtharyar_lucky_draw->uuid;
        //                     $luckydrawCategory['category_id'] = $category_id;
        //                     HlaingTharyarLuckyDrawCategory::create($luckydrawCategory);
        //                 }

        //             }

        //             if (!$request->select_all_brand) {
        //                 foreach ($brand_ids as $brand_id) {
        //                     $luckydrawBrand['promotion_uuid'] = $hlaingtharyar_lucky_draw->uuid;
        //                     $luckydrawBrand['brand_id'] = $brand_id;
        //                     HlaingTharyarLuckyDrawBrand::create($luckydrawBrand);

        //                 }
        //             }
        //             if ($branch_id == 21) {
        //                 $ayetharyar_lucky_draw = AyeTharyarLuckyDraw::create($lucky_draw_request);
        //                 $luckydrawBranch['promotion_uuid'] = $ayetharyar_lucky_draw->uuid;
        //                 AyeTharyarLuckyDrawBranch::create($luckydrawBranch);

        //                 /////old info,new info,reason,promotion_uuid
        //                 create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in AyeTharyar', $lanthit_lucky_draw->uuid);

        //                 if (!$request->select_all_category) {
        //                     foreach ($category_ids as $category_id) {
        //                         $luckydrawCategory['promotion_uuid'] = $ayetharyar_lucky_draw->uuid;
        //                         $luckydrawCategory['category_id'] = $category_id;
        //                         AyeTharyarLuckyDrawCategory::create($luckydrawCategory);

        //                     }
        //                 }

        //                 if (!$request->select_all_brand) {
        //                     foreach ($brand_ids as $brand_id) {
        //                         $luckydrawBrand['promotion_uuid'] = $ayetharyar_lucky_draw->uuid;
        //                         $luckydrawBrand['brand_id'] = $brand_id;
        //                         AyeTharyarLuckyDrawBrand::create($luckydrawBrand);

        //                     }
        //                 }
        //             }
        //             if ($branch_id == 27) {
        //                 $terminalm_lucky_draw = TerminalMLuckyDraw::create($lucky_draw_request);
        //                 $luckydrawBranch['promotion_uuid'] = $terminalm_lucky_draw->uuid;
        //                 TerminalMLuckyDrawBranch::create($luckydrawBranch);

        //                 ////old info,new info,reason,promotion_uuid
        //                 create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in TerminalM', $lanthit_lucky_draw->uuid);

        //                 if (!$request->select_all_category) {
        //                     foreach ($category_ids as $category_id) {
        //                         $luckydrawCategory['promotion_uuid'] = $terminalm_lucky_draw->uuid;
        //                         $luckydrawCategory['category_id'] = $category_id;
        //                         TerminalMLuckyDrawCategory::create($luckydrawCategory);

        //                     }
        //                 }

        //                 if (!$request->select_all_brand) {
        //                     foreach ($brand_ids as $brand_id) {
        //                         $luckydrawBrand['promotion_uuid'] = $terminalm_lucky_draw->uuid;
        //                         $luckydrawBrand['brand_id'] = $brand_id;
        //                         TerminalMLuckyDrawBrand::create($luckydrawBrand);

        //                     }
        //                 }
        //             }

        //             if ($branch_id == 28) {
        //                 $terminalm_lucky_draw = SouthDagonLuckyDraw::create($lucky_draw_request);
        //                 $luckydrawBranch['promotion_uuid'] = $terminalm_lucky_draw->uuid;
        //                 SouthDagonLuckyDrawBranch::create($luckydrawBranch);

        //                 ////old info,new info,reason,promotion_uuid
        //                 create_promotion_log($lucky_draw->name, $lucky_draw->name, 'Create Promotion' . $lucky_draw->name . 'in SouthDagon', $lanthit_lucky_draw->uuid);

        //                 if (!$request->select_all_category) {
        //                     foreach ($category_ids as $category_id) {
        //                         $luckydrawCategory['promotion_uuid'] = $terminalm_lucky_draw->uuid;
        //                         $luckydrawCategory['category_id'] = $category_id;
        //                         SouthDagonLuckyDrawCategory::create($luckydrawCategory);

        //                     }
        //                 }

        //                 if (!$request->select_all_brand) {
        //                     foreach ($brand_ids as $brand_id) {
        //                         $luckydrawBrand['promotion_uuid'] = $terminalm_lucky_draw->uuid;
        //                         $luckydrawBrand['brand_id'] = $brand_id;
        //                         SouthDagonLuckyDrawBrand::create($luckydrawBrand);

        //                     }
        //                 }
        //             }
        //         }
        //     }
        // } else {
        //     $branch_ids = Branch::select('branch_id')->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27])->get()->toarray();

        //     foreach ($branch_ids as $branch_id) {
        //         if ($branch_id['branch_id'] == 1) {
        //             $lanthit_lucky_draw = LanthitLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 2) {
        //             $lanthit_lucky_draw = TheikPanLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 3) {
        //             $lanthit_lucky_draw = SatsanLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 9) {
        //             $lanthit_lucky_draw = EastDagonLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 10) {
        //             $lanthit_lucky_draw = MawlamyineLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 11) {
        //             $lanthit_lucky_draw = TampawadyLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 19) {
        //             $lanthit_lucky_draw = HlaingTharyarLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 21) {
        //             $lanthit_lucky_draw = AyeTharyarLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 27) {
        //             $lanthit_lucky_draw = TerminalMLuckyDraw::create($lucky_draw_request);
        //         }
        //         if ($branch_id['branch_id'] == 28) {
        //             $lanthit_lucky_draw = SouthDagonLuckyDraw::create($lucky_draw_request);
        //         }
        //     }
        // }

        $branch_id = get_current_branch_id();
        $luckydrawBranch['promotion_uuid'] = $lucky_draw->uuid;
        $luckydrawBranch['branch_id'] = $branch_id;
        LuckyDrawBranch::create($luckydrawBranch);

        if (!$request->select_all_category) {
            foreach ($category_ids as $category_id) {
                $luckydrawCategory['promotion_uuid'] = $lucky_draw->uuid;
                $luckydrawCategory['category_id'] = $category_id;
                LuckyDrawCategory::create($luckydrawCategory);
            }
        }

        if (!$request->select_all_brand) {
            foreach ($brand_ids as $brand_id) {
                $luckydrawBrand['promotion_uuid'] = $lucky_draw->uuid;
                $luckydrawBrand['brand_id'] = $brand_id;
                LuckyDrawBrand::create($luckydrawBrand);
            }
        }

        DB::commit();
        return redirect()->route('new_promotion.edit', $lucky_draw->uuid);
    }

    public function edit($lucky_draw_uuid)
    {
        $lucky_draw = LuckyDraw::where('uuid', $lucky_draw_uuid)->first();
        $promotion_sub_promotions = PromotionSubPromotion::where('promotion_uuid', $lucky_draw->uuid)->get();
        $branches = Branch::select('branch_id', 'branch_name_eng')->wherein('branch_id', [1, 2, 3, 9, 10, 11, 19, 20, 21,23, 25, 26, 27, 28,30])->get();

        $luckydraw_branches = LuckyDrawBranch::where('promotion_uuid', $lucky_draw->uuid)->get()->pluck('branch_id')->toarray();
        $categories = Category::get();

        $product_checks = ProductCheck::get();
        $luckydraw_categories = LuckyDrawCategory::where('promotion_uuid', $lucky_draw->uuid)->get()->pluck('category_id')->toarray();
        $currentURL = URL::current();
        $brands = TheikPanBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        $luckydraw_brands = LuckyDrawBrand::select('brand_id')->where('promotion_uuid', $lucky_draw->uuid)->get()->pluck('brand_id')->toarray();

        $lucky_draw_types = LuckyDrawType::where('status', 1)->get();

        $sub_promotions = SubPromotion::get();
        return view('promotion.edit', compact('lucky_draw', 'lucky_draw_types', 'sub_promotions', 'branches', 'luckydraw_branches', 'categories',
            'luckydraw_categories', 'brands', 'luckydraw_brands', 'promotion_sub_promotions'));
    }

    public function update(Request $request, $lucky_draw_uuid)
    {
        request()->validate([
            // 'name' => 'required|unique:promotions,name,' . $LuckyDraw->id,
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'discon_status' => 'required',
            'lucky_draw_type_uuid' => 'required',
        ]);

        $lucky_draw_request['uuid'] = $lucky_draw_uuid;
        $lucky_draw_request['name'] = $request->name;
        $lucky_draw_request['start_date'] = $request->start_date;
        $lucky_draw_request['end_date'] = $request->end_date;
        $lucky_draw_request['main_color'] = $request->main_color;
        $lucky_draw_request['status'] = $request->status;
        $lucky_draw_request['remark'] = $request->remark;
        $lucky_draw_request['discon_status'] = $request->discon_status;
        $lucky_draw_request['diposit_type_id'] = $request->diposit_type_id;
        $lucky_draw_request['lucky_draw_type_uuid'] = $request->lucky_draw_type_uuid;

        $currentURL = URL::current();
        // if (str_contains($currentURL, '192.168.2.221') || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.23') ||str_contains($currentURL, '192.168.3.242') || str_contains($currentURL, '192.168.11.242') || str_contains($currentURL, '192.168.21.242')|| str_contains($currentURL, '192.168.16.242') || str_contains($currentURL, '192.168.31.242') || str_contains($currentURL, '192.168.25.242') || str_contains($currentURL, '192.168..242')) {
        // } else {
        //     return redirect()->route('lucky_draws.edit', $lucky_draw_uuid)->with('success', 'Pormotion is successfully Updated');
        // }
        $Luckydraw = LuckyDraw::where('uuid', $lucky_draw_uuid)->first();
        $old_lucky_draw = $Luckydraw;
        $old_luckyDraw_category = LuckyDrawCategory::where('promotion_uuid', $lucky_draw_uuid)->pluck('category_id')->toArray();
        $old_luckyDraw_brand = LuckyDrawBrand::where('promotion_uuid', $lucky_draw_uuid)->pluck('brand_id')->toArray();
        $lucky_draw = $Luckydraw->update($lucky_draw_request);
        $luckydraw_uuid = $Luckydraw->uuid;

        //Remove old Branch Data from Branch DB
        // $branch_ids = $request->branch_id ?? [];
        // $old_ld_branches = LuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->get()->pluck('branch_id')->toarray();
        // dd($old_ld_branches);
        // foreach ($old_ld_branches as $old_ld_branch) {
        //     if ($old_ld_branch == 1) {
        //         //check ticket
        //         $ticket = LanthitTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         LanthitLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
        //     }

        //     if ($old_ld_branch == 2) {
        //         //check ticket
        //         $ticket = TheikPanTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         TheikPanLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
        //     }

        //     if ($old_ld_branch == 3) {
        //         //check ticket
        //         $ticket = SatsanTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         SatsanLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
        //     }

        //     if ($old_ld_branch == 9) {
        //         //check ticket
        //         $ticket = EastDagonTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         EastDagonLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
        //     }

        //     if ($old_ld_branch == 10) {
        //         //check ticket
        //         $ticket = MawlamyineTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         MawlamyineLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

        //     }
        //     if ($old_ld_branch == 11) {
        //         //check ticket
        //         $ticket = TampawadyTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         TampawadyLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

        //     }
        //     if ($old_ld_branch == 19) {
        //         //check ticket
        //         $ticket = HlaingTharyarTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         HlaingTharyarLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

        //     }
        //     if ($old_ld_branch == 21) {
        //         //check ticket
        //         $ticket = AyeTharyarTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         AyeTharyarLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

        //     }
        //     if ($old_ld_branch == 27) {
        //         //check ticket
        //         $ticket = TerminalMTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         TerminalMLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

        //     }
        //     if ($old_ld_branch == 28) {
        //         //check ticket
        //         $ticket = SouthDagonTicket::where('promotion_uuid', $luckydraw_uuid)->first();
        //         if ($ticket) {
        //             return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
        //         }
        //         SouthDagonLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
        //     }
        // }

        LuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        $branch_id = get_current_branch_id();
        $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        $luckydrawBranch['branch_id'] = $branch_id;
        LuckyDrawBranch::create($luckydrawBranch);

        LuckyDrawCategory::where('promotion_uuid', $luckydraw_uuid)->delete();
        LuckyDrawBrand::where('promotion_uuid', $luckydraw_uuid)->delete();
        // $branch_ids = $request->branch_id ?? [];
        // if ($branch_ids == null && $request->select_all_branch == null) {
        //     return redirect()->back()->withInput()->with('error', 'Branch is required');
        // }
        // if (count($branch_ids) == 0) {
        //     $branch_ids = [2, 11, 1, 3, 9, 19, 10, 21, 27];
        // }
        // foreach ($branch_ids as $branch_id) {
        // $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        // $luckydrawBranch['branch_id'] = $branch_id;
        // LuckyDrawBranch::create($luckydrawBranch);
        // if ($branch_id == 1) {
        //     LanthitLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     LanthitLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     LanthitLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if ($request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //           new_promotion  LanthitLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if ($request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             LanthitLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 2) {
        //     TheikPanLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     TheikPanLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     TheikPanLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             TheikPanLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             TheikPanLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 3) {
        //     SatsanLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     SatsanLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     SatsanLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             SatsanLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             SatsanLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 9) {
        //     EastDagonLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     EastDagonLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     EastDagonLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             EastDagonLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             EastDagonLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 10) {
        //     MawlamyineLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     MawlamyineLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     MawlamyineLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             MawlamyineLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             MawlamyineLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 11) {
        //     TampawadyLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     TampawadyLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     TampawadyLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             TampawadyLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             TampawadyLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 19) {
        //     HlaingTharyarLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     HlaingTharyarLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     HlaingTharyarLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             HlaingTharyarLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             HlaingTharyarLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 21) {
        //     AyeTharyarLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     AyeTharyarLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     AyeTharyarLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             AyeTharyarLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             AyeTharyarLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 27) {
        //     TerminalMLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     TerminalMLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     TerminalMLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             TerminalMLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             TerminalMLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // if ($branch_id == 28) {
        //     SouthDagonLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        //     SouthDagonLuckyDraw::create($lucky_draw_request);
        //     $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
        //     SouthDagonLuckyDrawBranch::create($luckydrawBranch);
        //     $category_ids = $request->category_id ?? [];
        //     if ($category_ids == null && $request->select_all_category == null) {
        //         return redirect()->back()->withInput()->with('error', 'Category is required');
        //     }
        //     if (!$request->select_all_category) {
        //         foreach ($category_ids as $category_id) {
        //             $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawCategory['category_id'] = $category_id;
        //             SouthDagonLuckyDrawCategory::create($luckydrawCategory);
        //         }
        //     }
        //     $brand_ids = $request->brand_id ?? [];
        //     if ($brand_ids == null && $request->select_all_brand == null) {
        //         return redirect()->back()->withInput()->with('error', 'Brand is required');
        //     }
        //     if (!$request->select_all_brand) {
        //         foreach ($brand_ids as $brand_id) {
        //             $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
        //             $luckydrawBrand['brand_id'] = $brand_id;
        //             SouthDagonLuckyDrawBrand::create($luckydrawBrand);
        //         }
        //     }
        // }
        // }
        $category_ids = $request->category_id ?? [];

        if ($category_ids == null && $request->select_all_category == null) {
            return redirect()->back()->withInput()->with('error', 'Category is required');
        }

        if (count($category_ids) > 0) {
            foreach ($category_ids as $category_id) {
                $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                $luckydrawCategory['category_id'] = $category_id;
                LuckyDrawCategory::create($luckydrawCategory);
            }
        };
        $brand_ids = $request->brand_id ?? [];
        if ($brand_ids == null && $request->select_all_brand == null) {
            return redirect()->back()->withInput()->with('error', 'Brand is required');
        }
        if (count($brand_ids) > 0) {
            foreach ($brand_ids as $brand_id) {
                $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                $luckydrawBrand['brand_id'] = $brand_id;
                LuckyDrawBrand::create($luckydrawBrand);
            }
        };

        /////Store Change Log//////
        ////branch////
        $new_branch = $request->branch_id;
        if ($new_branch) {
            $luckyDraw_branch = LuckyDrawBranch::where('promotion_uuid', $lucky_draw_uuid)->pluck('branch_id')->toArray();
            ////old info,new infopromotion_uuid
            branch_array_diff($luckyDraw_branch, $new_branch, $lucky_draw_uuid);
        }

        //category///
        $new_category = $request->category_id;
        if ($new_category) {

            // $luckyDraw_category = LuckyDrawCategory::where('promotion_uuid', $lucky_draw_uuid)->pluck('category_id')->toArray();
            ////old info,new infopromotion_uuid
            category_array_diff($old_luckyDraw_category, $new_category, $lucky_draw_uuid);
        }

        ////brand///
        $new_brand = $request->brand_id;
        if ($new_brand) {

            ////old info,new infopromotion_uuid
            brand_array_diff($old_luckyDraw_brand, $new_brand, $lucky_draw_uuid);
        }
        unset($request['_token']);
        unset($request['_method']);
        unset($request['select_all_category']);
        unset($request['category_id']);
        unset($request['select_all_brand']);
        unset($request['brand_id']);
        unset($request['branch_id']);

        $new_luckdraw_data = $request->toArray();
        $old_luckdraw_data = LuckyDraw::where('uuid', $lucky_draw_uuid)->first()->toArray();
        unset($old_luckdraw_data['id']);
        unset($old_luckdraw_data['uuid']);
        unset($old_luckdraw_data['amount_for_one_ticket']);
        unset($old_luckdraw_data['created_at']);
        unset($old_luckdraw_data['updated_at']);
        unset($old_luckdraw_data['remark']);

        associated_array_diff($old_luckdraw_data, $new_luckdraw_data, $lucky_draw_uuid);

        return redirect()->route('new_promotion.edit', $luckydraw_uuid)->with('success', 'Pormotion is successfully Updated');
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }

    public function destroy($luckydraw_uuid)
    {
        // try {
        $old_luckydraw_uuid = $luckydraw_uuid;
        $LuckyDraw = LuckyDraw::where('uuid', $luckydraw_uuid)->first();
        //Check Ticket
        $lbranches = LuckyDrawBranch::where('promotion_uuid', $LuckyDraw->uuid)->get()->toarray();

        if ($lbranches == []) {
            $lbranches = Branch::all()->toArray();
        }
        foreach ($lbranches as $lbranch) {
            if ($lbranch['branch_id'] == 1) {
                $old_luckydraw_uuid = $LuckyDraw->uuid;
                $ticket = LanthitTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $lanthit_lucky_draw = LanthitLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($lanthit_lucky_draw) {
                        $lanthit_lucky_draw->delete();

                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }

            if ($lbranch['branch_id'] == 2) {
                $ticket = TheikPanTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {

                    $LuckyDraw->delete();
                    $theikpan_lucky_draw = TheikPanLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($theikpan_lucky_draw) {
                        $theikpan_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 3) {
                $ticket = SatsanTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $satsan_lucky_draw = SatsanLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($satsan_lucky_draw) {
                        $satsan_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);
                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 9) {
                $ticket = EastDagonTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $east_dagon_lucky_draw = EastDagonLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($east_dagon_lucky_draw) {
                        $east_dagon_lucky_draw->delete();
                    }
                    ////store change log////
                    $promotion_change_log['uuid'] = (string) Str::uuid();
                    $promotion_change_log['date'] = date('Y-m-d H:i:s');
                    $promotion_change_log['user_uuid'] = auth()->user()->uuid;
                    $promotion_change_log['old_info'] = $LuckyDraw->name;
                    $promotion_change_log['new_info'] = $LuckyDraw->name;
                    $promotion_change_log['reason'] = 'Delete ' . $LuckyDraw->name;
                    $promotion_change_log['promotion_uuid'] = $old_luckydraw_uuid;
                    PromotionChangeLog::create($promotion_change_log);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 10) {
                $ticket = MawlamyineTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $mawlamyine_lucky_draw = MawlamyineLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($mawlamyine_lucky_draw) {
                        $mawlamyine_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);
                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 11) {
                $ticket = TampawadyTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $tampawady_lucky_draw = TampawadyLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($tampawady_lucky_draw) {
                        $tampawady_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 19) {
                $ticket = HlaingTharyarTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $hlaingtharyar_lucky_draw = HlaingTharyarLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($hlaingtharyar_lucky_draw) {
                        $hlaingtharyar_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 21) {
                $ticket = AyeTharyarTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $ayetharyar_lucky_draw = AyeTharyarLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();

                    if ($ayetharyar_lucky_draw) {
                        $ayetharyar_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 27) {
                $ticket = TerminalMTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $terminalm_lucky_draw = TerminalMLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($terminalm_lucky_draw) {
                        $terminalm_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);

                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 28) {
                $ticket = SouthDagonTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $terminalm_lucky_draw = SouthDagonLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($terminalm_lucky_draw) {
                        $terminalm_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in Lanthit.', $old_luckydraw_uuid);
                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
        }
        ////old info,new info,reason,promotion_uuid
        create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Promotion-' . $LuckyDraw->name . 'in HO.', $old_luckydraw_uuid);

        return response()->json([
            'success' => 'Promotion is deleted successfully',
        ]);
        return redirect()->back()->withInput()->with('error', 'This Promotion is have ticket');

        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draw_types.index"))
        //         ->with('error', 'Fail to delete Lucky Draw!');
        // }
    }

}
