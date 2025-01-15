<?php

namespace App\Http\Controllers;

use File;
use App\Models\AmountCheck;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\PrizeCCCheck;
use App\Models\ProductCheck;
use App\Models\SubPromotion;
use Illuminate\Http\Request;
use App\Models\PrizeTicketCheck;
use App\Models\AmountCheckBranch;
use App\Models\ProductCheckBranch;
use App\Models\PromotionChangeLog;
use App\Models\FixedPrizeAmountCheck;
use App\Models\PromotionSubPromotion;
use Yajra\DataTables\Facades\DataTables;

class SubPromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function connection()
    {
        return new PromotionSubPromotion();
    }
    public function index()
    {
        //
    }
    public function search_sub_promotion_result(Request $request)
    {
        // try{
        $promotion_uuid = (!empty($_GET["promotion_uuid"])) ? ($_GET["promotion_uuid"]) : ('');
        $result = $this->connection()->where('promotion_uuid', $promotion_uuid)->with('sub_promotions')->get();

        return DataTables::of($result)
            ->editColumn('name', function ($data) {
                if (isset($data->sub_promotions)) {
                    return $data->sub_promotions->name;

                }
                return '';

            })
            ->addIndexColumn()
            ->make(true);
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws_prices.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }
    public function product_result(Request $request)
    {
        // try{
        $sub_promotion_uuid = (!empty($_GET["sub_promotion_uuid"])) ? ($_GET["sub_promotion_uuid"]) : ('');
        $promotion_uuid = (!empty($_GET["promotion_uuid"])) ? ($_GET["promotion_uuid"]) : ('');
        $result = ProductCheck::query();
        if ($sub_promotion_uuid) {
            $result = $result->where('sub_promotion_uuid', $sub_promotion_uuid);
        }
        if ($promotion_uuid) {
            $result = $result->where('promotion_uuid', $promotion_uuid);
        }
        $result = $result->get();

        return DataTables::of($result)
            ->addIndexColumn()
            ->make(true);
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("lucky_draws_prices.index"))
        //         ->with('error', 'Fail to Search Lucky Draw!');
        // }
    }

    public function store(Request $request)
    {
        request()->validate([
            'sub_promotion_name' => 'required',
            'promotion_uuid' => 'required',
            'invoice_check_type' => 'required',
            'prize_check_type' => 'required',
        ]);
        $sub_promotion['name'] = $request->sub_promotion_name;

        if ($request->sub_promotion_uuid == 'other') {
            $check_sub_promotion = null;
        } else {
            $check_sub_promotion = SubPromotion::where('uuid', $request->sub_promotion_uuid)->first();
        }

        if (!$check_sub_promotion) {
            $sub_promotion['uuid'] = (string) Str::uuid();
            $old_sub_promotion = SubPromotion::where('name', $request->sub_promotion_name)->first();
            if ($old_sub_promotion) {
                $sub_promotion_uuid = $old_sub_promotion->uuid;
            } else {
                $sub_promotion = SubPromotion::create($sub_promotion);

                $sub_promotion_uuid = $sub_promotion->uuid;

                $promotion_sub_promotion['promotion_uuid'] = $request->promotion_uuid;
                $promotion_sub_promotion['sub_promotion_uuid'] = $sub_promotion_uuid;
                $promotion_sub_promotion['invoice_check_type'] = $request->invoice_check_type;
                $promotion_sub_promotion['prize_check_type'] = $request->prize_check_type;

                $promotion_sub_promotion = PromotionSubPromotion::create($promotion_sub_promotion);
            }
        } else {
            $promotion_sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $check_sub_promotion->uuid)->where('promotion_uuid', $request->promotion_uuid)->first();

            $update_promotion_sub_promotion['promotion_uuid'] = $request->promotion_uuid;
            $update_promotion_sub_promotion['sub_promotion_uuid'] = $check_sub_promotion->uuid;

            $update_promotion_sub_promotion['invoice_check_type'] = $request->invoice_check_type;
            $update_promotion_sub_promotion['prize_check_type'] = $request->prize_check_type;
 
            if ($promotion_sub_promotion) {
                $promotion_sub_promotion->update($update_promotion_sub_promotion);
            } else {
                $promotion_sub_promotion = PromotionSubPromotion::create($update_promotion_sub_promotion);
            }
        }
        // Add or Update 3 Image
        // if ($request->images) {
        //     $request->validate([
        //         'images' => 'required',
        //         'images.*' => 'image|mimes:png|max:2048'
        //     ]);
        //     $i = 0;
        //     foreach($request->images as $image){
        //         if($i < 3){
        //             File::delete(public_path('images/promotion_icons/' . $promotion_sub_promotion->promotion_uuid .'/'. $promotion_sub_promotion->sub_promotion_uuid .'/'. $i));
        //             $filename = $i . '.png';
        //             $image->move(public_path('images/promotion_icons/'. $promotion_sub_promotion->promotion_uuid.'/'. $promotion_sub_promotion->sub_promotion_uuid), $filename);
        //         }
        //         $i++;
        //     }
        // }

        //// Add one Image for New Design//////
        if ($request->images) {
            // $request->validate([
            //     'images' => 'required',
            //     'images.*' => 'image|mimes:png|max:2048'
            // ]);
            $filename = $promotion_sub_promotion->promotion_uuid .'.png';
            $request->images->move(public_path('images/promotion_images/'. $promotion_sub_promotion->promotion_uuid.'/'.
            $promotion_sub_promotion->sub_promotion_uuid .'/'.'show_image/'), $filename);

        }
        ////store change log////
        ////old info,new info,reason,promotion_uuid
        create_promotion_log($request->sub_promotion_name,$request->sub_promotion_name,'Create Sub Promotion- '.$request->sub_promotion_name,$request->promotion_uuid);

        return redirect()->route('new_promotion.edit', $request->promotion_uuid);
    }

    public function find_subpromotion_checkprize()
    {
        $sub_promotion_uuid = (!empty($_POST["sub_promotion_uuid"])) ? ($_POST["sub_promotion_uuid"]) : ('');
        return SubPromotion::select('prize_check_type')->where('uuid', $sub_promotion_uuid)->first()->prize_check_type;

    }

    public function get_sub_promotion($promotion_uuid, $sub_promotion_uuid)
    {
        $data = PromotionSubPromotion::where('promotion_uuid', $promotion_uuid)->where('sub_promotion_uuid', $sub_promotion_uuid)->first();
        return [
            'sub_promotion_uuid' => $data->sub_promotion_uuid,
            'sub_promotion_name' => $data->sub_promotions->name,
            'invoice_check_type' => $data->invoice_check_type,
            'prize_check_type' => $data->prize_check_type,
        ];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubPromotion  $subPromotion
     * @return \Illuminate\Http\Response
     */
    public function edit(SubPromotion $subPromotion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubPromotion  $subPromotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubPromotion $subPromotion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubPromotion  $subPromotion
     * @return \Illuminate\Http\Response
     */
    public function sub_promotion_destory($promotion_uuid, $sub_promotion_uuid)
    {
        $old_sub_promotion = $sub_promotion_uuid;
        //TODO Check Ticket Used
        PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->delete();
        AmountCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->delete();
        ProductCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->delete();
        //old info,new info,reason,promotion_uuid
        create_promotion_log($old_sub_promotion,$old_sub_promotion,'Delete Sub Promotion-'.$old_sub_promotion .' is changed ',$promotion_uuid);
        return response()->json([
            'success' => 'Sub Promotion delete successfully!',
        ]);

    }

    public function get_check_invoice_info($promotion_uuid, $sub_promotion_uuid)
    {
        $invoice_check_type = PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first()->invoice_check_type;

        if ($invoice_check_type) {
            if ($invoice_check_type == 1) {
                $check = AmountCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
                $check_branches = AmountCheckBranch::where('amount_check_uuid', $check->uuid)->get();
            } else if ($invoice_check_type == 1) {
                $check = ProductCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
                $check_branches = ProductCheckBranch::where('product_check_uuid', $check->uuid)->get();
            } else {
                $check = null;
                $check_branches = null;
            }
        } else {
            $check = null;
            $check_branches = null;
        }
        return response()->json([
            'invoice_check_type' => $invoice_check_type,
            'check' => $check,
            'check_value' => isset($check->amount) ? $check->amount : '',
            'check_branches' => $check_branches,

        ]);
    }

    public function check_invoice_check_type($promotion_uuid, $sub_promotion_uuid, $type)
    {
        $claimHistory = ClaimHistory::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
        if ($claimHistory) {
            return response()->json([
                'data' => 'sub_prmotion_is_used',
            ]);
        }
        if ($sub_promotion_uuid == 'other') {
            return null;
        }
        $invoice_check_type = PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
        if ($invoice_check_type) {
            if ($invoice_check_type->invoice_check_type == null) {
                return response()->json([
                    'data' => 'first_time',
                ]);
            }
            if ($invoice_check_type->invoice_check_type == $type) {
                return response()->json([
                    'data' => 'same_type',
                ]);
            } else {
                return response()->json([
                    'data' => 'different_type',
                ]);
            }
        }
        return response()->json([
            'error' => 'validation error',
        ]);
    }

    public function get_check_prize_info($promotion_uuid, $sub_promotion_uuid)
    {
        if ($sub_promotion_uuid == 'other') {
            return null;
        }
        $prize_check_type = PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first()->prize_check_type;
        if ($prize_check_type) {
            if ($prize_check_type == 1) {
                $check = PrizeTicketCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
            } else if ($prize_check_type == 2) {
                $check = PrizeCCCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
            } else if ($prize_check_type == 3) {
                $check = FixedPrizeAmountCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
            } else {
                $check = null;
            }
        } else {
            $check = null;
        }
        return response()->json([
            'prize_check_type' => $prize_check_type,
            'check' => $check,
        ]);
    }

    public function check_prize_check_type($promotion_uuid, $sub_promotion_uuid, $type)
    {
        $claimHistory = ClaimHistory::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
        if ($claimHistory) {
            return response()->json([
                'data' => 'sub_prmotion_is_used',
            ]);
        }
        if ($sub_promotion_uuid == 'other') {
            return null;
        }
        $prize_check_type = PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
        if ($prize_check_type) {
            if ($prize_check_type->prize_check_type == null) {
                return response()->json([
                    'data' => 'first_time',
                ]);
            }
            if ($prize_check_type->prize_check_type == $type) {
                return response()->json([
                    'data' => 'same_type',
                ]);
            } else {
                return response()->json([
                    'data' => 'different_type',
                ]);
            }
        }
        return response()->json([
            'error' => 'validation error',
        ]);
    }
}
