<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\LuckyDraw;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LuckyDrawType;
use App\Models\Bago\BagoBrand;
use App\Models\LuckyDrawBrand;
use App\Models\Bago\BagoTicket;
use App\Models\LuckyDrawBranch;
use Yajra\DataTables\DataTables;
use App\Models\LuckyDrawCategory;
use App\Models\Bago\BagoLuckyDraw;
use App\Models\PromotionChangeLog;
use App\Models\Satsan\SatsanBrand;
use Illuminate\Support\Facades\DB;
use App\Models\Satsan\SatsanTicket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Lanthit\LanthitBrand;
use Illuminate\Support\Facades\File;
use App\Models\Lanthit\LanthitTicket;
use App\Models\Satsan\SatsanLuckyDraw;
use App\Models\TheikPan\TheikPanBrand;
use App\Models\Bago\BagoLuckyDrawBrand;
use App\Models\TheikPan\TheikPanTicket;
use App\Models\Bago\BagoLuckyDrawBranch;
use App\Models\EastDagon\EastDagonBrand;
use App\Models\Lanthit\LanthitLuckyDraw;
use App\Models\Tampawady\TampawadyBrand;
use App\Models\TerminalM\TerminalMBrand;
use App\Models\EastDagon\EastDagonTicket;
use App\Models\Satsan\SatsanTicketHeader;
use App\Models\Tampawady\TampawadyTicket;
use App\Models\TerminalM\TerminalMTicket;
use App\Models\AyeTharyar\AyeTharyarBrand;
use App\Models\Bago\BagoLuckyDrawCategory;
use App\Models\Mawlamyine\MawlamyineBrand;
use App\Models\SouthDagon\SouthDagonBrand;
use App\Models\TheikPan\TheikPanLuckyDraw;
use App\Models\AyeTharyar\AyeTharyarTicket;
use App\Models\Lanthit\LanthitTicketHeader;
use App\Models\Mawlamyine\MawlamyineTicket;
use App\Models\Satsan\SatsanLuckyDrawBrand;
use App\Models\EastDagon\EastDagonLuckyDraw;
use App\Models\Satsan\SatsanLuckyDrawBranch;
use App\Models\Tampawady\TampawadyLuckyDraw;
use App\Models\TerminalM\TerminalMLuckyDraw;
use App\Models\Lanthit\LanthitLuckyDrawBrand;
use App\Models\TheikPan\TheikPanTicketHeader;
use App\Models\AyeTharyar\AyeTharyarLuckyDraw;
use App\Models\Lanthit\LanthitLuckyDrawBranch;
use App\Models\Mawlamyine\MawlamyineLuckyDraw;
use App\Models\Satsan\SatsanLuckyDrawCategory;
use App\Models\SouthDagon\SouthDagonLuckyDraw;
use App\Models\EastDagon\EastDagonTicketHeader;
use App\Models\Tampawady\TampawadyTicketHeader;
use App\Models\TerminalM\TerminalMTicketHeader;
use App\Models\TheikPan\TheikPanLuckyDrawBrand;
use App\Models\HlaingTharyar\HlaingTharyarBrand;
use App\Models\Lanthit\LanthitLuckyDrawCategory;
use App\Models\TheikPan\TheikPanLuckyDrawBranch;
use App\Models\AyeTharyar\AyeTharyarTicketHeader;
use App\Models\EastDagon\EastDagonLuckyDrawBrand;
use App\Models\HlaingTharyar\HlaingTharyarTicket;
use App\Models\Mawlamyine\MawlamyineTicketHeader;
use App\Models\Tampawady\TampawadyLuckyDrawBrand;
use App\Models\TerminalM\TerminalMLuckyDrawBrand;
use App\Models\EastDagon\EastDagonLuckyDrawBranch;
use App\Models\Tampawady\TampawadyLuckyDrawBranch;
use App\Models\TerminalM\TerminalMLuckyDrawBranch;
use App\Models\TheikPan\TheikPanLuckyDrawCategory;
use App\Models\AyeTharyar\AyeTharyarLuckyDrawBrand;
use App\Models\Mawlamyine\MawlamyineLuckyDrawBrand;
use App\Models\SouthDagon\SouthDagonLuckyDrawBrand;
use App\Models\AyeTharyar\AyeTharyarLuckyDrawBranch;
use App\Models\EastDagon\EastDagonLuckyDrawCategory;
use App\Models\HlaingTharyar\HlaingTharyarLuckyDraw;
use App\Models\Mawlamyine\MawlamyineLuckyDrawBranch;
use App\Models\SouthDagon\SouthDagonLuckyDrawBranch;
use App\Models\Tampawady\TampawadyLuckyDrawCategory;
use App\Models\TerminalM\TerminalMLuckyDrawCategory;
use App\Models\AyeTharyar\AyeTharyarLuckyDrawCategory;
use App\Models\Mawlamyine\MawlamyineLuckyDrawCategory;
use App\Models\SouthDagon\SouthDagonLuckyDrawCategory;
use App\Models\HlaingTharyar\HlaingTharyarTicketHeader;
use App\Models\HlaingTharyar\HlaingTharyarLuckyDrawBrand;
use App\Models\HlaingTharyar\HlaingTharyarLuckyDrawBranch;
use App\Models\HlaingTharyar\HlaingTharyarLuckyDrawCategory;

class LuckyDrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:view-promotion', ['only' => ['index','search_result']]);
        // $this->middleware('permission:create-promotion', ['only' => ['create','store']]);
        // $this->middleware('permission:edit-promotion', ['only' => ['edit','update']]);
        // $this->middleware('permission:delete-promotion', ['only' => ['destroy']]);
        // $this->middleware('permission:approve-promotion', ['only' => ['approve']]);
        // $this->middleware('permission:reject-promotion', ['only' => ['reject']]);
    }

    protected function connection()
    {
        return new LuckyDraw();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('lucky_draws.index');
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }
    public function search_result(Request $request)
    {
        try {
            $lucky_draw_name = (!empty($_GET["lucky_draw_name"])) ? ($_GET["lucky_draw_name"]) : ('');
            $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
            $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
            $lucky_draw_status = (!empty($_GET["lucky_draw_status"])) ? ($_GET["lucky_draw_status"]) : 1;
            $result = $this->connection();
            if ($lucky_draw_name != "") {
                $result = $result->where('name', 'like', '%' . $lucky_draw_name . '%');
            }
            if ($start_date != "") {
                $dateStr = str_replace("/", "-", $start_date);
                $start_date = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('start_date', '>=', $start_date);
            }
            if ($end_date != "") {
                $dateStr = str_replace("/", "-", $end_date);
                $end_date = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('end_date', '>=', $end_date);
            }
            if ($lucky_draw_status != "") {
                $result = $result->where('status', $lucky_draw_status);
            }
            $result = $result->with('promotion_type')->get();
            return DataTables::of($result)
                ->addColumn('promotion_type', function ($data) {
                    if (isset($data->promotion_type)) {
                        return $data->promotion_type->name;
                    }
                    return '';
                })
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("lucky_draws.index"))
                ->with('error', 'Fail to Search Lucky Draw!');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $branches = Branch::select('branch_id', 'branch_name_eng')
                ->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28])
                ->get();
            $categories = Category::get();
            $currentURL = URL::current();
            if (str_contains($currentURL, '192.168.21.242') || str_contains($currentURL,'tpluckydraw')) {
                $brands = TheikPanBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.25.242')|| str_contains($currentURL,'tpwluckydraw')) {
                $brands = TampawadyBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.3.242')
            // ||str_contains($currentURL, '192.168.2.23')
             || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221') || str_contains($currentURL,'ltluckydraw') ) {
                $brands = LanthitBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.11.242') || str_contains($currentURL,'ssluckydraw')
            //  ||str_contains($currentURL, '192.168.2.23')
             ) {
                $brands = SatsanBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.16.242')|| str_contains($currentURL,'edluckydraw')
            ||str_contains($currentURL, '192.168.2.23')) {
                $brands = EastDagonBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.36.242') || str_contains($currentURL,'htyluckydraw')) {
                $brands = HlaingTharyarBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.31.242') || str_contains($currentURL,'mlmluckydraw')) {
                $brands = MawlamyineBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.41.242') || str_contains($currentURL,'atyluckydraw')) {
                $brands = AyeTharyarBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.46.242') || str_contains($currentURL,'tmluckydraw')) {
                $brands = TerminalMBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.51.242') || str_contains($currentURL,'sdgluckydraw')) {
                $brands = SouthDagonBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.56.242') || str_contains($currentURL,'dngluckydraw')) {
                $brands = SouthDagonBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            if (str_contains($currentURL, '192.168.61.242') || str_contains($currentURL,'bagoluckydraw')) {
                $brands = BagoBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
            }
            $lucky_draw_types = LuckyDrawType::get();
            return view('lucky_draws.create', compact('branches', 'categories', 'brands', 'lucky_draw_types'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("lucky_draws.index"))
                ->with('error', 'Fail to Search Lucky Draw!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        request()->validate([
            'name' => 'required|string|max:50|unique:promotions,name',
            "promotion_image" => 'image | required | mimes:png,PNG | max:2048 |dimensions:width=560,height=140',
            'start_date' => 'required',
            'end_date' => 'required',
            'amount_for_one_ticket' => 'required',
            'status' => 'required',
            'discon_status' => 'required',
            // 'lucky_draw_type' => 'required'
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
        $lucky_draw_request['amount_for_one_ticket'] = str_replace(',', '', $request->amount_for_one_ticket);
        $lucky_draw_request['status'] = $request->status;
        $lucky_draw_request['remark'] = $request->remark;
        $lucky_draw_request['discon_status'] = $request->discon_status;
        $lucky_draw_request['lucky_draw_type_uuid'] = $request->lucky_draw_type;
        DB::beginTransaction();
        $LuckyDraw = LuckyDraw::create($lucky_draw_request);

        if ($request->promotion_image) {
            File::delete(public_path('images/promotion_image/' . $lucky_draw->uuid . '.png'));
            $filename = $lucky_draw->uuid . '.png';
            $request->promotion_image->move(public_path('images/promotion_image'), $filename);
        }

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
        if (!$request->select_all_branch) {
            foreach ($branch_ids as $branch_id) {
                $luckydrawBranch['promotion_uuid'] = $lucky_draw->uuid;
                $luckydrawBranch['branch_id'] = $branch_id;
                LuckyDrawBranch::create($luckydrawBranch);

                if ($branch_id == 1) {
                    $lanthit_lucky_draw = LanthitLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $lanthit_lucky_draw->uuid;
                    LanthitLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $lanthit_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            LanthitLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $lanthit_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            LanthitLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 2) {
                    $theikpan_lucky_draw = TheikPanLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $theikpan_lucky_draw->uuid;
                    TheikPanLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $theikpan_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            TheikPanLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $theikpan_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            TheikPanLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 3) {
                    $satsan_lucky_draw = SatsanLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $satsan_lucky_draw->uuid;
                    SatsanLuckyDrawBranch::create($luckydrawBranch);
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $satsan_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            SatsanLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $satsan_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            SatsanLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 9) {
                    $eastdagon_lucky_draw = EastDagonLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $eastdagon_lucky_draw->uuid;
                    EastDagonLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $eastdagon_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            EastDagonLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $eastdagon_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            EastDagonLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 10) {
                    $mawlamyine_lucky_draw = MawlamyineLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $mawlamyine_lucky_draw->uuid;
                    MawlamyineLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $mawlamyine_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            MawlamyineLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $mawlamyine_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            MawlamyineLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 11) {
                    $tampawady_lucky_draw = TampawadyLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $tampawady_lucky_draw->uuid;
                    TampawadyLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $tampawady_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            TampawadyLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $tampawady_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            TampawadyLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 19) {
                    $hlaingtharyar_lucky_draw = HlaingTharyarLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $hlaingtharyar_lucky_draw->uuid;
                    HlaingTharyarLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $hlaingtharyar_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            HlaingTharyarLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $hlaingtharyar_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            HlaingTharyarLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 21) {
                    $ayetharyar_lucky_draw = AyeTharyarLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $ayetharyar_lucky_draw->uuid;
                    AyeTharyarLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $ayetharyar_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            AyeTharyarLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $ayetharyar_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            AyeTharyarLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 27) {
                    $terminalm_lucky_draw = TerminalMLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                    TerminalMLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            TerminalMLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            TerminalMLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }

                if ($branch_id == 28) {
                    $terminalm_lucky_draw = SouthDagonLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                    SouthDagonLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            SouthDagonLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            SouthDagonLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
                if ($branch_id == 23) {
                    $terminalm_lucky_draw = BagoLuckyDraw::create($lucky_draw_request);
                    $luckydrawBranch['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                    BagoLuckyDrawBranch::create($luckydrawBranch);

                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

                    if (!$request->select_all_category) {
                        foreach ($category_ids as $category_id) {
                            $luckydrawCategory['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                            $luckydrawCategory['category_id'] = $category_id;
                            BagoLuckyDrawCategory::create($luckydrawCategory);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }

                    if (!$request->select_all_brand) {
                        foreach ($brand_ids as $brand_id) {
                            $luckydrawBrand['promotion_uuid'] = $terminalm_lucky_draw->uuid;
                            $luckydrawBrand['brand_id'] = $brand_id;
                            BagoLuckyDrawBrand::create($luckydrawBrand);
                            ////old info,new info,reason,promotion_uuid
                            create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Create Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                        }
                    }
                }
            }
        } else {
            $branch_ids = Branch::select('branch_id')->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27])->get()->toarray();

            foreach ($branch_ids as $branch_id) {
                if ($branch_id['branch_id'] == 1) {
                    $lanthit_lucky_draw = LanthitLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 2) {
                    $lanthit_lucky_draw = TheikPanLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 3) {
                    $lanthit_lucky_draw = SatsanLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 9) {
                    $lanthit_lucky_draw = EastDagonLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 10) {
                    $lanthit_lucky_draw = MawlamyineLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 11) {
                    $lanthit_lucky_draw = TampawadyLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 19) {
                    $lanthit_lucky_draw = HlaingTharyarLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 21) {
                    $lanthit_lucky_draw = AyeTharyarLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 27) {
                    $lanthit_lucky_draw = TerminalMLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 28) {
                    $lanthit_lucky_draw = SouthDagonLuckyDraw::create($lucky_draw_request);
                }
                if ($branch_id['branch_id'] == 23) {
                    $lanthit_lucky_draw = BagoLuckyDraw::create($lucky_draw_request);
                }
            }
        }

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
        return redirect()->route('lucky_draws.index')->with('success', 'Pormotion is successfully Created');
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws.index"))
        //         ->with('error', 'Fail to Create Promotion!');
        //     DB::rollBack();
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LuckyDraw  $luckyDraw
     * @return \Illuminate\Http\Response
     */
    public function show($lucky_draw_uuid)
    {
        $lucky_draw = LuckyDraw::where('uuid', $lucky_draw_uuid)->first();
        $lucky_draw_branches = LuckyDrawBranch::where('promotion_uuid', $lucky_draw_uuid)->first();
        if (!$lucky_draw_branches) {
            $lucky_draw_branches = 'All Branch';
        } else {
            $lucky_draw_branches = LuckyDrawBranch::where('promotion_uuid', $lucky_draw_uuid)->with('branches')->get()->pluck('branches')->toarray();
            $branch_array = [];
            foreach ($lucky_draw_branches as $branch) {
                $branch_array[] = $branch['branch_name_eng'];
            }
            $lucky_draw_branches = implode(" ", $branch_array);
        }

        $lucky_draw_categories = LuckyDrawCategory::where('promotion_uuid', $lucky_draw_uuid)->first();
        if (!$lucky_draw_categories) {
            $lucky_draw_categories = 'All Category';
        } else {
            $lucky_draw_categories = LuckyDrawCategory::where('promotion_uuid', $lucky_draw_uuid)->with('categories')->get()->pluck('categories')->toarray();
            $category_array = [];
            foreach ($lucky_draw_categories as $category) {
                $category_array[] = $category['name'];
            }
            $lucky_draw_categories = implode(" ", $category_array);
        }
        $lucky_draw_brands = LuckyDrawBrand::where('promotion_uuid', $lucky_draw_uuid)->first();
        if (!$lucky_draw_brands) {
            $lucky_draw_brands = 'All Brand';
        } else {
            $lucky_draw_brands = LuckyDrawBrand::where('promotion_uuid', $lucky_draw_uuid)->with('brands')->get()->pluck('brands')->toarray();
            $brand_array = [];
            foreach ($lucky_draw_brands as $brand) {
                $brand_array[] = $brand['product_brand_name'];
            }
            $lucky_draw_brands = implode(" ", $brand_array);
        }
        $lucky_draw_type_name = LuckyDrawType::where('uuid', $lucky_draw->lucky_draw_type_uuid)->first();
        if ($lucky_draw_type_name) {
            $lucky_draw_type_name = $lucky_draw_type_name->name;
        } else {
            $lucky_draw_type_name = 'Normal Lucky Draw Type';
        }
        return response()->json([
            'lucky_draw' => $lucky_draw->name,
            'lucky_draw_type' => $lucky_draw_type_name,
            'lucky_draw_branches' => $lucky_draw_branches,
            'lucky_draw_categories' => $lucky_draw_categories,
            'lucky_draw_brands' => $lucky_draw_brands,
            'lucky_draw_discon' => $lucky_draw->discon_status == 1 ? 'Include' : 'Exclude',
            'lucky_draw_start_date' => $lucky_draw->start_date,
            'lucky_draw_end_date' => $lucky_draw->end_date,
        ], 200);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LuckyDraw  $LuckyDraw
     * @return \Illuminate\Http\Response
     */
    public function edit($lucky_draw_uuid)
    {
        // try{
        $lucky_draw = LuckyDraw::where('uuid', $lucky_draw_uuid)->first();

        $branches = Branch::select('branch_id', 'branch_name_eng')->wherein('branch_id', [1, 2, 3, 9, 10, 11, 19, 20, 21, 25, 26, 27, 28])->get();
        $luckydraw_branches = LuckyDrawBranch::where('promotion_uuid', $lucky_draw->uuid)->get()->pluck('branch_id')->toarray();
        $categories = Category::get();
        $luckydraw_categories = LuckyDrawCategory::where('promotion_uuid', $lucky_draw->uuid)->get()->pluck('category_id')->toarray();

        $currentURL = URL::current();
        if (str_contains($currentURL, '192.168.21.242') || str_contains($currentURL,'tpluckydraw')) {
            $brands = TheikPanBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.25.242') || str_contains($currentURL,'tpwluckydraw')) {
            $brands = TampawadyBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.3.242') || str_contains($currentURL,'ltluckydraw')
        // ||str_contains($currentURL, '192.168.2.23')
         || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221') ) {
            $brands = LanthitBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.11.242')|| str_contains($currentURL,'ssluckydraw')
        //  ||str_contains($currentURL, '192.168.2.23')
         ) {
            $brands = SatsanBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.16.242') || str_contains($currentURL,'edluckydraw')
        ||str_contains($currentURL, '192.168.2.23')) {
            $brands = EastDagonBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.36.242') || str_contains($currentURL,'htyluckydraw')) {
            $brands = HlaingTharyarBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.31.242') || str_contains($currentURL,'mlmluckydraw')) {
            $brands = MawlamyineBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.41.242') || str_contains($currentURL,'atyluckydraw')) {
            $brands = AyeTharyarBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.46.242') || str_contains($currentURL,'tmluckydraw')) {
            $brands = TerminalMBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.51.242')|| str_contains($currentURL,'sdgluckydraw')) {
            $brands = SouthDagonBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.56.242')|| str_contains($currentURL,'dngluckydraw')) {
            $brands = SouthDagonBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }
        if (str_contains($currentURL, '192.168.61.242') || str_contains($currentURL,'bagoluckydraw')) {
            $brands = BagoBrand::select('good_brand_id as product_brand_id', 'good_brand_code as product_brand_code', 'good_brand_name as product_brand_name')->get();
        }

        $luckydraw_brands = LuckyDrawBrand::select('brand_id')->where('promotion_uuid', $lucky_draw->uuid)->get()->pluck('brand_id')->toarray();

        return view('lucky_draws.edit', compact('lucky_draw', 'branches', 'luckydraw_branches', 'categories', 'luckydraw_categories', 'brands', 'luckydraw_brands'));
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LuckyDraw  $LuckyDraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lucky_draw_uuid)
    {
        // try{
        $LuckyDraw = LuckyDraw::where('uuid', $lucky_draw_uuid)->first();
        request()->validate([
            'name' => 'required|unique:promotions,name,' . $LuckyDraw->id,
            'start_date' => 'required',
            'end_date' => 'required',
            'amount_for_one_ticket' => 'required',
            'status' => 'required',
            'discon_status' => 'required',
        ]);

        $lucky_draw_request['uuid'] = $LuckyDraw->uuid;
        $lucky_draw_request['name'] = $request->name;
        $lucky_draw_request['start_date'] = $request->start_date;
        $lucky_draw_request['end_date'] = $request->end_date;
        $lucky_draw_request['amount_for_one_ticket'] = str_replace(',', '', $request->amount_for_one_ticket);
        $lucky_draw_request['main_color'] = $request->main_color;
        $lucky_draw_request['status'] = $request->status;
        $lucky_draw_request['remark'] = $request->remark;
        $lucky_draw_request['discon_status'] = $request->discon_status;
        if ($request->promotion_image) {
            $request->validate([
                "promotion_image" => 'image | required | mimes:png,PNG | max:2048 |dimensions:width=560,height=140',
            ]);

            File::delete(public_path('images/promotion_image/' . $LuckyDraw->uuid . '.png'));
            $filename = $LuckyDraw->uuid . '.png';
            $request->promotion_image->move(public_path('images/promotion_image'), $filename);
        }
        $currentURL = URL::current();

        if (str_contains($currentURL, '192.168.2.221')) {
        } else {
            return redirect()->route('lucky_draws.edit', $lucky_draw_uuid)->with('success', 'Pormotion is successfully Updated');
        }
        $lucky_draw = $LuckyDraw->update($lucky_draw_request);
        $luckydraw_uuid = $LuckyDraw->uuid;

        //Remove old Branch Data from Branch DB
        $branch_ids = $request->branch_id ?? [];
        $old_ld_branches = LuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->get()->pluck('branch_id')->toarray();

        foreach ($old_ld_branches as $old_ld_branch) {
            if ($old_ld_branch == 1) {
                //check ticket
                $ticket = LanthitTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                LanthitLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
            }

            if ($old_ld_branch == 2) {
                //check ticket
                $ticket = TheikPanTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                TheikPanLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
            }

            if ($old_ld_branch == 3) {
                //check ticket
                $ticket = SatsanTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                SatsanLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
            }

            if ($old_ld_branch == 9) {
                //check ticket
                $ticket = EastDagonTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                EastDagonLuckyDraw::where('uuid', $luckydraw_uuid)->delete();
            }

            if ($old_ld_branch == 10) {
                //check ticket
                $ticket = MawlamyineTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                MawlamyineLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
            if ($old_ld_branch == 11) {
                //check ticket
                $ticket = TampawadyTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                TampawadyLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
            if ($old_ld_branch == 19) {
                //check ticket
                $ticket = HlaingTharyarTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                HlaingTharyarLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
            if ($old_ld_branch == 21) {
                //check ticket
                $ticket = AyeTharyarTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                AyeTharyarLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
            if ($old_ld_branch == 27) {
                //check ticket
                $ticket = TerminalMTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                TerminalMLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
            if ($old_ld_branch == 28) {
                //check ticket
                $ticket = SouthDagonTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                SouthDagonLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
            if ($old_ld_branch == 23) {
                //check ticket
                $ticket = BagoTicket::where('promotion_uuid', $luckydraw_uuid)->first();
                if ($ticket) {
                    return redirect()->back()->withInput()->with('error', 'Cannot Edit Branch This Promotion has Ticket on Branch');
                }
                BagoLuckyDraw::where('uuid', $luckydraw_uuid)->delete();

            }
        }
        LuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
        LuckyDrawCategory::where('promotion_uuid', $luckydraw_uuid)->delete();
        LuckyDrawBrand::where('promotion_uuid', $luckydraw_uuid)->delete();
        $branch_ids = $request->branch_id ?? [];
        if ($branch_ids == null && $request->select_all_branch == null) {
            return redirect()->back()->withInput()->with('error', 'Branch is required');
        }
        if (count($branch_ids) == 0) {
            $branch_ids = [2, 11, 1, 3, 9, 19, 10, 21, 27];
        }
        foreach ($branch_ids as $branch_id) {
            $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
            $luckydrawBranch['branch_id'] = $branch_id;
            LuckyDrawBranch::create($luckydrawBranch);
            if ($branch_id == 1) {
                LanthitLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                LanthitLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                LanthitLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if ($request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        LanthitLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if ($request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        LanthitLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 2) {
                TheikPanLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                TheikPanLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                TheikPanLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        TheikPanLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        TheikPanLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 3) {
                SatsanLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                SatsanLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                SatsanLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        SatsanLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        SatsanLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 9) {
                EastDagonLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                EastDagonLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                EastDagonLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        EastDagonLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        EastDagonLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 10) {
                MawlamyineLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                MawlamyineLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                MawlamyineLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        MawlamyineLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        MawlamyineLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 11) {
                TampawadyLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                TampawadyLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                TampawadyLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        TampawadyLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        TampawadyLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 19) {
                HlaingTharyarLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                HlaingTharyarLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                HlaingTharyarLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        HlaingTharyarLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        HlaingTharyarLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 21) {
                AyeTharyarLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                AyeTharyarLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                AyeTharyarLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        AyeTharyarLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        AyeTharyarLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 27) {
                TerminalMLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                TerminalMLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                TerminalMLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        TerminalMLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        TerminalMLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 28) {
                SouthDagonLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                SouthDagonLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                SouthDagonLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        SouthDagonLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        SouthDagonLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
            if ($branch_id == 23) {
                BagoLuckyDrawBranch::where('promotion_uuid', $luckydraw_uuid)->delete();
                BagoLuckyDraw::create($lucky_draw_request);
                $luckydrawBranch['promotion_uuid'] = $luckydraw_uuid;
                BagoLuckyDrawBranch::create($luckydrawBranch);
                $category_ids = $request->category_id ?? [];
                if ($category_ids == null && $request->select_all_category == null) {
                    return redirect()->back()->withInput()->with('error', 'Category is required');
                }
                if (!$request->select_all_category) {
                    foreach ($category_ids as $category_id) {
                        $luckydrawCategory['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawCategory['category_id'] = $category_id;
                        BagoLuckyDrawCategory::create($luckydrawCategory);
                    }
                }
                $brand_ids = $request->brand_id ?? [];
                if ($brand_ids == null && $request->select_all_brand == null) {
                    return redirect()->back()->withInput()->with('error', 'Brand is required');
                }
                if (!$request->select_all_brand) {
                    foreach ($brand_ids as $brand_id) {
                        $luckydrawBrand['promotion_uuid'] = $luckydraw_uuid;
                        $luckydrawBrand['brand_id'] = $brand_id;
                        BagoLuckyDrawBrand::create($luckydrawBrand);
                    }
                }
            }
        }
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
        return redirect()->route('lucky_draws.edit', $luckydraw_uuid)->with('success', 'Pormotion is successfully Updated');
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LuckyDraw  $LuckyDraw
     * @return \Illuminate\Http\Response
     */
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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);

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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete TheikPan' . $LuckyDraw->name, $old_luckydraw_uuid);

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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Satsan"' . $LuckyDraw->name, $old_luckydraw_uuid);
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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Mawlamyine' . $LuckyDraw->name, $old_luckydraw_uuid);

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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Tampawady' . $LuckyDraw->name, $old_luckydraw_uuid);
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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete AyeTharyar' . $LuckyDraw->name, $old_luckydraw_uuid);

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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete AyeTharyar' . $LuckyDraw->name, $old_luckydraw_uuid);
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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
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
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
            if ($lbranch['branch_id'] == 23) {
                $ticket = BagoTicketHeader::where('promotion_uuid', $LuckyDraw->uuid)->first();
                if ($ticket == null) {
                    $LuckyDraw->delete();
                    $terminalm_lucky_draw = BagoLuckyDraw::where('uuid', $LuckyDraw->uuid)->first();
                    if ($terminalm_lucky_draw) {
                        $terminalm_lucky_draw->delete();
                    }
                    ////old info,new info,reason,promotion_uuid
                    create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete Lanthit' . $LuckyDraw->name, $old_luckydraw_uuid);
                } else {
                    return response()->json([
                        'error' => 'This Promotion is have ticket',
                    ]);
                }
            }
        }
        ////old info,new info,reason,promotion_uuid
        create_promotion_log($LuckyDraw->name, $LuckyDraw->name, 'Delete ' . $LuckyDraw->name, $old_luckydraw_uuid);

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

    public function lucky_draw_search_by_type(Request $request)
    {
        try {
            $ticket_type = $request->ticket_type;
            $today = date('Y-m-d');
            $result = $this->connection();
            if ($ticket_type == 1 || $ticket_type == 3) {
                $result = $result->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            }
            $result = $result->where('status', 1)->get()->toarray();
            $output = [];
            foreach ($result as $r) {
                $output[$r['uuid']] = $r['name'];
            }
            return $output;
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("lucky_draws.index"))
                ->with('error', 'Fail to Search Lucky Draw!');
        }
    }

    public function promotion_by_branch(Request $request)
    {
        // try{
        $branch_id = $request->branch_id;
        $promotions = LuckyDrawBranch::where('branch_id', $branch_id)->get();
        $output = [];
        if ($promotions->count() > 0) {
            foreach ($promotions as $r) {
                $output[$r->promotions->uuid] = $r->promotions->name;
            }
        }
        return $output;
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }

}
