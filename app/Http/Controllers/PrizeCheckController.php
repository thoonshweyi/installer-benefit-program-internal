<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\CCWinningChance;
use App\Models\ExtendCheckPrizeGrabChance;
use App\Models\FixedPrizeAmountCheck;
use App\Models\LuckyDraw;
use App\Models\LuckyDrawBranch;
use App\Models\PrizeCCBranch;
use App\Models\PrizeCCCheck;
use App\Models\PrizeItem;
use App\Models\PrizeTicketCheck;
use App\Models\PromotionSubPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PrizeCheckController extends Controller
{
    public function view_prize_check($promotion_uuid, $sub_promotion_uuid)
    {
        $luckydraw_branches = [];
        $branches = [];
        $promotion_sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $sub_promotion_uuid)
            ->where('promotion_uuid', $promotion_uuid)->first();
        if ($promotion_sub_promotion) {
            // dd($promotion_sub_promotion);
            if ($promotion_sub_promotion->prize_check_type == 1) {
                $prizeTicketCheck = PrizeTicketCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
                // dd($prizeTicketCheck);
                return view('promotion.check_prize_ticket', compact('promotion_uuid', 'promotion_sub_promotion', 'prizeTicketCheck'));
            }
            if ($promotion_sub_promotion->prize_check_type == 2) {
                $cash_coupons = PrizeItem::where('type', '1')->get();
                $presents = PrizeItem::where('type', '2')->get();
                $lucky_draw_branches = LuckyDrawBranch::where('promotion_uuid', $promotion_sub_promotion->promotion_uuid)->get();
                return view('promotion.check_prize_grab_chance', compact('promotion_uuid', 'promotion_sub_promotion', 'cash_coupons', 'presents', 'lucky_draw_branches'));
            }
            if ($promotion_sub_promotion->prize_check_type == 3) {
                $fixedPrizeAmountCheck = FixedPrizeAmountCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
                return view('promotion.check_prize_fix_prize', compact('promotion_uuid', 'promotion_sub_promotion', 'fixedPrizeAmountCheck'));
            }
        } else {
            return 'Error';
        }
    }

    public function store_prize_check(Request $request)
    {
        if ($request->prize_check_type == 1) {
            //Delete other Type Data
            FixedPrizeAmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            PrizeCCCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            //Find Old Data
            $prizeTicketCheck = PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
            $prize_ticket_check['promotion_uuid'] = $request->promotion_uuid;
            $prize_ticket_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
            $prize_ticket_check['ticket_prize_amount'] = $request->ticket_prize_amount;
            //If Found Update
            if ($prizeTicketCheck) {
                $prizeTicketCheck->update($prize_ticket_check);
                if ($request->prize_ticket_image) {
                    // File::delete(public_path('images/prize_ticket_image/' . $prizeTicketCheck->prize_ticket_image));
                    // $request->prize_ticket_image->move(public_path('images/prize_ticket_image'), $prizeTicketCheck->prize_ticket_image);
                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $prizeTicketCheck->prize_ticket_image));
                    $request->prize_ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $prizeTicketCheck->prize_ticket_image);
                }
            } else {
                $update_status = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
                if ($update_status->status == 0) {
                    $promotion_sub_promotion_status['status'] = '1';
                    $update_status->update($promotion_sub_promotion_status);
                } else {
                    $promotion_sub_promotion_status['status'] = '2';
                    $update_status->update($promotion_sub_promotion_status);
                }

                $uuid = (string) Str::uuid();
                $prize_ticket_check['uuid'] = $uuid;
                $prize_ticket_check['ticket_prize_qty'] = $request->ticket_prize_qty;
                $prize_ticket_check['ticket_prize_image'] = $uuid . '.png';
                PrizeTicketCheck::create($prize_ticket_check);
                ////update promotion_sub_promotion prize check status////
                $update_status = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
                $promotion_sub_promotion_status['prize_check_status'] = '1';
                $update_status->update($promotion_sub_promotion_status);

                if ($request->prize_ticket_image) {
                    File::delete(public_path('images/prize_ticket_image/' . $uuid . '.png'));
                    $request->prize_ticket_image->move(public_path('images/prize_ticket_image'), $uuid . '.png');
                }
            }
        }
        //Grab the Chance
        // dd($request->all());
        if ($request->prize_check_type == 2) {
            request()->validate([
                'branch_id' => 'required',
            ]);
            //Delete other Type Data
            PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            FixedPrizeAmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();

            //Find Old Data
            $oldPrizeItem = PrizeItem::query();
            if ($request->grab_the_chance_type == 1) {

                $isUuid = Str::isUuid($request->cash_coupon_name);
                if ($isUuid) {
                    $oldPrizeItem = $oldPrizeItem->where('uuid', $request->cash_coupon_name);
                } else {
                    $oldPrizeItem = $oldPrizeItem->where('name', $request->cash_coupon_name);
                }
            }
            if ($request->grab_the_chance_type == 2) {
                $isUuid = Str::isUuid($request->present_name);
                if ($isUuid) {
                    $oldPrizeItem = $oldPrizeItem->where('uuid', $request->present_name);
                } else {
                    $oldPrizeItem = $oldPrizeItem->where('name', $request->present_name);
                }
            }
            $oldPrizeItem = $oldPrizeItem->first();

            //If Found Update
            if (!$oldPrizeItem) {
                $prize_item['uuid'] = (string) Str::uuid();
                $prize_item['name'] = $request->cash_coupon_name ?? $request->present_name;
                $prize_item['type'] = $request->grab_the_chance_type;
                $prize_item['gp_code'] = $request->gp_code;
                $oldPrizeItem = PrizeItem::create($prize_item);
            }

            $check_old_pricecc = PrizeCCCheck::where('prize_item_uuid', $oldPrizeItem->uuid)->where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
            // dd($check_old_pricecc);
            $uuid = (string) Str::uuid();
            $prize_check_item['promotion_uuid'] = $request->promotion_uuid;
            $prize_check_item['sub_promotion_uuid'] = $request->sub_promotion_uuid;
            $prize_check_item['prize_item_uuid'] = $oldPrizeItem->uuid;
            $prize_check_item['ticket_image'] = $uuid . '.png';

            if ($check_old_pricecc) {
                $check_old_pricecc->update($prize_check_item);
                if ($request->ticket_image) {
                    // $request->ticket_image->move(public_path('images/prize_items'), $check_old_pricecc->ticket_image);
                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $check_old_pricecc->ticket_image));
                    $request->ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $check_old_pricecc->ticket_image);

                }
            } else {
                //Store in Prize CC Check

                $prize_check_item['uuid'] = $uuid;

                $check_old_pricecc = PrizeCCCheck::create($prize_check_item);

                if ($request->ticket_image) {
                    // File::delete(public_path('images/prize_items/' . $check_old_pricecc->ticket_image));
                    // $request->ticket_image->move(public_path('images/prize_items'), $check_old_pricecc->ticket_image);
                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $check_old_pricecc->ticket_image));
                    $request->ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $check_old_pricecc->ticket_image);
                }
            }

            //Store in Price CC Branch
            foreach ($request->branch_id as $b_id) {
                $prize_cc_branch['uuid'] = (string) Str::uuid();
                $prize_cc_branch['prize_c_c_uuid'] = $check_old_pricecc->uuid;
                $prize_cc_branch['branch_id'] = $b_id;
                $prize_cc_branch['total_qty'] = 0;
                $prize_cc_branch['remain_qty'] = 0;
                PrizeCCBranch::create($prize_cc_branch);

                //Update in existing winning chance
                $old_winningChance = CCWinningChance::select("*")->DISTINCT('branch_id', 'minimum_amount')
                    ->where('promotion_uuid', $check_old_pricecc->promotion_uuid)->where('sub_promotion_uuid', $check_old_pricecc->sub_promotion_uuid)
                    ->where('branch_id', $b_id)->get();
                if ($old_winningChance) {
                    foreach ($old_winningChance as $o_w_Chance) {
                        if ($old_winningChance) {
                            $cc_winning_chance['uuid'] = (string) Str::uuid();
                        }

                        $cc_winning_chance['promotion_uuid'] = $check_old_pricecc->promotion_uuid;
                        $cc_winning_chance['sub_promotion_uuid'] = $check_old_pricecc->sub_promotion_uuid;
                        $cc_winning_chance['prize_cc_check_uuid'] = $check_old_pricecc->uuid;
                        $cc_winning_chance['branch_id'] = $b_id;
                        $cc_winning_chance['minimum_amount'] = $o_w_Chance->minimum_amount;
                        $cc_winning_chance['winning_percentage'] = 0;

                        CCWinningChance::create($cc_winning_chance);
                    }
                }
            }

            ////update promotion_sub_promotion prize check status////
            $update_status = PromotionSubPromotion::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
            $promotion_sub_promotion_status['prize_check_status'] = '1';
            $update_status->update($promotion_sub_promotion_status);

            return redirect()->route('view_prize_check', [$request->promotion_uuid, $request->sub_promotion_uuid]);
        }
        //Fix Amount
        if ($request->prize_check_type == 3) {

            //Delete other Type Data
            PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            PrizeCCCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            //Find Old Data
            $fixedPrizeAmountCheck = FixedPrizeAmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();
            $fixed_prize_check['promotion_uuid'] = $request->promotion_uuid;
            $fixed_prize_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
            $fixed_prize_check['fixed_prize_name'] = $request->fixed_prize_name;
            $fixed_prize_check['fixed_prize_qty'] = $request->fixed_prize_qty;
            $fixed_prize_check['fixed_prize_gp_code'] = $request->fixed_prize_gp_code;
            //If Found Update
            if ($fixedPrizeAmountCheck) {
                $fixedPrizeAmountCheck->update($fixed_prize_check);
                if ($request->fixed_prize_ticket_image) {
                    // File::delete(public_path('images/fixed_prize_image/' . $fixedPrizeAmountCheck->fixed_prize_ticket_image));
                    // $request->fixed_prize_ticket_image->move(public_path('images/fixed_prize_image'), $fixedPrizeAmountCheck->fixed_prize_ticket_image);

                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $fixedPrizeAmountCheck->uuid . '.png'));
                    $request->fixed_prize_ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $fixedPrizeAmountCheck->uuid . '.png');
                }
            } else {

                if ($request->fixed_prize_type == 1) {
                    $uuid = (string) Str::uuid();
                    $fixed_prize_check['uuid'] = $uuid;
                    $fixed_prize_check['fixed_prize_type'] = $request->fixed_prize_type;
                    $fixed_prize_check['fixed_prize_name'] = 'Gold Ring';
                    $fixed_prize_check['fixed_prize_ticket_image'] = $uuid . '.png';
                    $fixed_prize_check = FixedPrizeAmountCheck::create($fixed_prize_check);
                } else if ($request->fixed_prize_type == 2) {
                    $uuid = (string) Str::uuid();
                    $fixed_prize_check['uuid'] = $uuid;
                    $fixed_prize_check['fixed_prize_type'] = $request->fixed_prize_type;
                    $fixed_prize_check['fixed_prize_name'] = 'Gold Coin';
                    $fixed_prize_check['fixed_prize_ticket_image'] = $uuid . '.png';
                    $fixed_prize_check = FixedPrizeAmountCheck::create($fixed_prize_check);
                } else {
                    $uuid = (string) Str::uuid();
                    $fixed_prize_check['uuid'] = $uuid;
                    $fixed_prize_check['fixed_prize_type'] = $request->fixed_prize_type;
                    $fixed_prize_check['fixed_prize_name'] = $request->fixed_prize_name;
                    $fixed_prize_check['fixed_prize_ticket_amount'] = $request->fixed_prize_ticket_amount;
                    $fixed_prize_check['fixed_prize_ticket_image'] = $uuid . '.png';
                    $fixed_prize_check = FixedPrizeAmountCheck::create($fixed_prize_check);
                }
                if ($request->fixed_prize_ticket_image) {
                    // File::delete(public_path('images/fixed_prize_image/' . $uuid . '.png'));
                    // $request->fixed_prize_ticket_image->move(public_path('images/fixed_prize_image'), $uuid . '.png');

                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $uuid . '.png'));
                    $request->fixed_prize_ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $uuid . '.png');
                }
            }
        }
        return redirect()->route('new_promotion.edit', $request->promotion_uuid);
    }

    public function prize_check_item_list(Request $request)
    {
        $promotion_uuid = (!empty($_GET["promotion_uuid"])) ? ($_GET["promotion_uuid"]) : ('');
        $sub_promotion_uuid = (!empty($_GET["sub_promotion_uuid"])) ? ($_GET["sub_promotion_uuid"]) : ('');

        $result = PrizeCCCheck::where('promotion_uuid', $promotion_uuid)->where('sub_promotion_uuid', $sub_promotion_uuid)->with('prizeItem')->get();

        return DataTables::of($result)
            ->editColumn('name', function ($data) {
                if (isset($data->prizeItem)) {
                    return $data->prizeItem->name;
                }
                return '';
            })
            ->editColumn('used_qty', function ($data) {
                $stock_qty = $data->stock_qty ? $data->stock_qty : 0;
                return $stock_qty - $data->qty;
            })
            ->editColumn('remain_qty', function ($data) {
                return $data->qty;
            })
            ->editColumn('type', function ($data) {
                if (isset($data->prizeItem)) {
                    if ($data->prizeItem->type == 1) {
                        return 'Cash Coupon';
                    };
                    if ($data->prizeItem->type == 2) {
                        return 'Present';
                    };
                }
                return '';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit_prize_check($uuid, $prize_check_type)
    {
        //Ticket
        if ($prize_check_type == 1) {
            $prizeTicketCheck = PrizeTicketCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
            return view('promotion.check_prize_ticket', compact('promotion_uuid', 'promotion_sub_promotion', 'prizeTicketCheck'));
        }
        //Grab The Chance
        if ($prize_check_type == 2) {
            $cash_coupons = PrizeItem::where('type', '1')->get();
            $presents = PrizeItem::where('type', '2')->get();
            $prizeCCCheck = PrizeCCCheck::where('uuid', $uuid)->first();
            $promotion_sub_promotion = PromotionSubPromotion::where('sub_promotion_uuid', $prizeCCCheck->sub_promotion_uuid)
                ->where('promotion_uuid', $prizeCCCheck->promotion_uuid)->first();
            $promotion_uuid = $prizeCCCheck->promotion_uuid;
            $lucky_draw_branches = LuckyDrawBranch::where('promotion_uuid', $promotion_uuid)->get();
            $prize_cc_branches = PrizeCCBranch::select('branch_id')->where('prize_c_c_uuid', $uuid)->get()->pluck('branch_id')->toArray();
            return view('promotion.edit_check_prize_grab_chance', compact('promotion_uuid', 'promotion_sub_promotion', 'cash_coupons', 'presents', 'prizeCCCheck', 'lucky_draw_branches', 'prize_cc_branches'));
        }
        //Fixed Amount
        if ($prize_check_type == 3) {
            $fixedPrizeAmountCheck = FixedPrizeAmountCheck::where('sub_promotion_uuid', $sub_promotion_uuid)->where('promotion_uuid', $promotion_uuid)->first();
            return view('promotion.check_prize_fix_prize', compact('promotion_uuid', 'promotion_sub_promotion', 'fixedPrizeAmountCheck'));
        }
        return 'Error'; //TODO RETURN ERROR MESSAGE
    }

    public function extend_prize_check($uuid, $branch_id)
    {
        $prizeCCCheck = PrizeCCCheck::where('uuid', $uuid)->first();
        $branch = Branch::where('branch_id', $branch_id)->first();
        return view('promotion.extend_check_prize_grab_chance', compact('prizeCCCheck', 'branch'));
    }

    public function extended_prize_check_list(Request $request)
    {
        $prizeCCCheckUUid = (!empty($_GET["prizeCCCheckUUid"])) ? ($_GET["prizeCCCheckUUid"]) : ('');
        $branch_id = (!empty($_GET["branch_id"])) ? ($_GET["branch_id"]) : ('');
        $result = ExtendCheckPrizeGrabChance::where('prize_c_c_check_uuid', $prizeCCCheckUUid)
            ->where('branch_id', $branch_id)->with('users')->get();
        return DataTables::of($result)
            ->editColumn('name', function ($data) {
                if (isset($data->users)) {
                    return $data->users->name;
                }
                return '';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->diffForHumans();
            })
            ->editColumn('action', function ($data) {
                if ($data->action == 1) {
                    return 'Add';
                };
                if ($data->action == 2) {
                    return 'Extend';
                };

                return '';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function update_prize_check(Request $request, $uuid)
    {
       
        //Ticket
        if ($request->prize_check_type == 1) {
            //Delete other Type Data
            FixedPrizeAmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            PrizeCCCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            //Find Old Data
            $prizeTicketCheck = PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();

            $prize_ticket_check['promotion_uuid'] = $request->promotion_uuid;
            $prize_ticket_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
            $prize_ticket_check['ticket_prize_amount'] = $request->ticket_prize_amount;
            //If Found Update
            if ($prizeTicketCheck) {
                $prizeTicketCheck->update($prize_ticket_check);
                if ($request->prize_ticket_image) {
                    File::delete(public_path('images/prize_ticket_image/' . $prizeTicketCheck->prize_ticket_image));
                    $request->prize_ticket_image->move(public_path('images/prize_ticket_image'), $prizeTicketCheck->prize_ticket_image);
                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $prizeTicketCheck->prize_ticket_image));
                    $request->prize_ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $prizeTicketCheck->prize_ticket_image);
                }
            } else {
                $uuid = (string) Str::uuid();
                $prize_ticket_check['uuid'] = $uuid;
                $prize_ticket_check['ticket_prize_image'] = $uuid . '.png';
                PrizeTicketCheck::create($prize_ticket_check);
                if ($request->prize_ticket_image) {
                    File::delete(public_path('images/prize_ticket_image/' . $uuid . '.png'));
                    $request->prize_ticket_image->move(public_path('images/prize_ticket_image'), $uuid . '.png');
                }
            }
        }
        //Grab the Chance
        if ($request->prize_check_type == 2) {
            //Delete other Type Data
            PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            FixedPrizeAmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();

            //Find Old Data
            $oldPrizeItem = PrizeItem::query();
            if ($request->grab_the_chance_type == 1) {

                $isUuid = Str::isUuid($request->cash_coupon_name);
                $cash_coupon = $request->cash_coupon_name;
                if ($isUuid) {
                    $oldPrizeItem = $oldPrizeItem->where('uuid', $request->cash_coupon_name);
                } else {
                    $oldPrizeItem = $oldPrizeItem->where('name', $request->cash_coupon_name);
                }
            }
            if ($request->grab_the_chance_type == 2) {
                $isUuid = Str::isUuid($request->present_name);
                if ($isUuid) {
                    $oldPrizeItem = $oldPrizeItem->where('uuid', $request->present_name);
                } else {
                    $oldPrizeItem = $oldPrizeItem->where('name', $request->present_name);
                }
            }
            // dd($oldPrizeItem->first());
            $oldPrizeItem = $oldPrizeItem->first();
            //If Found Update
            if (!$oldPrizeItem) {
                $prize_item['uuid'] = (string) Str::uuid();
                $prize_item['name'] = $request->cash_coupon_name ?? $request->present_name;
                $prize_item['type'] = $request->grab_the_chance_type;
                $prize_item['gp_code'] = $request->gp_code;
                $oldPrizeItem = PrizeItem::create($prize_item);
            }

            $prize_check_item['prize_item_uuid'] = $oldPrizeItem->uuid;

            if ($request->qty) {
                $prize_check_item['qty'] = $request->qty;
            }
            if ($request->stock_qty) {
                $prize_check_item['stock_qty'] = $request->stock_qty;
            }
          
            $prize_check_item['ticket_image'] = $oldPrizeItem->uuid . '.png';
            $prizeCCheck = PrizeCCCheck::where(['uuid'=> $request->prize_cc_check_uuid])
            ->first();
            // $prizeCCheck = PrizeCCCheck::where(['sub_promotion_uuid'=>$request->sub_promotion_uuid,'promotion_uuid'=>$request->promotion_uuid,'prize_item_uuid'=> $oldPrizeItem->uuid])
            // ->first();
            // ->where('promotion_uuid', $request->promotion_uuid)->where('prize_item_uuid', $oldPrizeItem->uuid)
            // dd($prizeCCheck);
            $prizeCCheck->update($prize_check_item);
            if ($request->ticket_image) {
                // File::delete(public_path('images/prize_items/' . $prize_cash_coupon_check->ticket_image));
                // $request->ticket_image->move(public_path('images/prize_items'), $prize_cash_coupon_check->ticket_image);
                ////New Design/////
                File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                    $request->sub_promotion_uuid . '/' . 'promotion_image/' . $prizeCCheck->ticket_image));
                $request->ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                    $request->sub_promotion_uuid . '/' . 'promotion_image/'), $prizeCCheck->ticket_image);
            }
            return redirect()->route('view_prize_check', [$request->promotion_uuid, $request->sub_promotion_uuid]);
        }
        //Fix Amount
        if ($request->prize_check_type == 3) {
            //Delete other Type Data
            PrizeTicketCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            PrizeCCCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->delete();
            //Find Old Data
            $fixedPrizeAmountCheck = FixedPrizeAmountCheck::where('sub_promotion_uuid', $request->sub_promotion_uuid)->where('promotion_uuid', $request->promotion_uuid)->first();

            $fixed_prize_check['promotion_uuid'] = $request->promotion_uuid;
            $fixed_prize_check['sub_promotion_uuid'] = $request->sub_promotion_uuid;
            $fixed_prize_check['fixed_prize_ticket_amount'] = $request->fixed_prize_ticket_amount;
            //If Found Update
            if ($fixedPrizeAmountCheck) {
                $fixedPrizeAmountCheck->update($fixed_prize_check);
                if ($request->fixed_prize_ticket_image) {
                    // File::delete(public_path('images/fixed_prize_image/' . $fixedPrizeAmountCheck->fixed_prize_ticket_image));
                    // $request->fixed_prize_ticket_image->move(public_path('images/fixed_prize_image'), $fixedPrizeAmountCheck->fixed_prize_ticket_image);
                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $fixedPrizeAmountCheck->fixed_prize_ticket_image));
                    $request->fixed_prize_ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $fixedPrizeAmountCheck->fixed_prize_ticket_image);
                }
            } else {
                $uuid = (string) Str::uuid();
                $fixed_prize_check['uuid'] = $uuid;
                $fixed_prize_check['fixed_prize_ticket_image'] = $uuid . '.png';
                $fixed_prize_check = FixedPrizeAmountCheck::create($fixed_prize_check);
                if ($request->fixed_prize_ticket_image) {
                    // File::delete(public_path('images/fixed_prize_image/' . $uuid . '.png'));
                    // $request->fixed_prize_ticket_image->move(public_path('images/fixed_prize_image'), $uuid . '.png');
                    ////New Design/////
                    File::delete(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/' . $fixedPrizeAmountCheck->fixed_prize_ticket_image));
                    $request->fixed_prize_ticket_image->move(public_path('images/promotion_images/' . $request->promotion_uuid . '/' .
                        $request->sub_promotion_uuid . '/' . 'promotion_image/'), $fixedPrizeAmountCheck->fixed_prize_ticket_image);
                }
            }
        }
        return redirect()->route('new_promotion.edit', $request->promotion_uuid);
    }

    public function delete($uuid)
    {
        $product_check = ProductCheck::where('uuid', $uuid)->delete();
        return response()->json([
            'success' => 'Item is deleted successfully',
        ]);
    }
    public function prize_item_destory($uuid)
    {
        $product_check = PrizeCCCheck::where('uuid', $uuid)->first();

        PrizeItem::where('uuid', $product_check->prize_item_uuid)->firsst();
        return response()->json([
            'success' => 'Item is deleted successfully',
        ]);
    }

    public function update_extend_items(Request $request)
    {
        $extendPrize = ExtendCheckPrizeGrabChance::where('prize_c_c_check_uuid', $request->prizeCCCheckUUid)
            ->where('branch_id', $request->branch_id)->first();
        $update_extended['uuid'] = (string) Str::uuid();
        $update_extended['extended_by'] = Auth::user()->uuid;
        $update_extended['extended_qty'] = $request->qty;
        $update_extended['prize_c_c_check_uuid'] = $request->prizeCCCheckUUid;
        $update_extended['branch_id'] = $request->branch_id;
        if ($extendPrize) {
            $update_extended['action'] = '2';

        } else {
            $update_extended['action'] = '1';
        }
        //Update Prize CC Branch
        $prize_cc_branch = PrizeCCBranch::where('prize_c_c_uuid', $request->prizeCCCheckUUid)
            ->where('branch_id', $request->branch_id)->first();
        $update_total_qty = $prize_cc_branch->total_qty + $request->qty;
        $update_remain_qty = $prize_cc_branch->remain_qty + $request->qty;
        $prize_cc_branch->update(
            ['total_qty' => $update_total_qty,
                'remain_qty' => $update_remain_qty,
            ]);
        ExtendCheckPrizeGrabChance::create($update_extended);

        return redirect()->route('extend_prize_check', [$request->prizeCCCheckUUid, $request->branch_id]);

    }

    public function prize_cc_branch_list(Request $request)
    {
        $prize_cc_check_uuid = (!empty($_GET["prize_cc_check_uuid"])) ? ($_GET["prize_cc_check_uuid"]) : ('');

        $result = PrizeCCBranch::where('prize_c_c_uuid', $prize_cc_check_uuid)->with('branch')->get();
        return DataTables::of($result)
            ->editColumn('name', function ($data) {
                if (isset($data->branch)) {
                    return $data->branch->branch_name_eng;
                }
                return '';
            })
            ->editColumn('used_qty', function ($data) {
                $total_qty = $data->total_qty ? $data->total_qty : 0;
                return $total_qty - $data->remain_qty;
            })

            ->addIndexColumn()
            ->make(true);
    }

    public function view_prize_list()
    {
        $promotions = LuckyDraw::where('status', 1)->get();
        $branches = BranchUser::where('user_uuid', auth()->user()->uuid)->with('branches')->get();
        return view('promotion.view_prize_list', compact('branches', 'promotions'));
    }

    protected function prize_cc_branch_connection()
    {
        return new PrizeCCBranch();
    }

    public function prize_list(Request $request)
    {
        $promotion_uuid = (!empty($_GET["promotion_uuid"])) ? ($_GET["promotion_uuid"]) : ('');
        $branch_id = (!empty($_GET["branch_id"])) ? ($_GET["branch_id"]) : ('');
        $result = $this->prize_cc_branch_connection();
        if ($promotion_uuid != '') {
            $result = $result->whereHas('prize_cc', function ($query) use ($promotion_uuid) {
                return $query->where('promotion_uuid', $promotion_uuid);
            });
        }
        if ($branch_id != '') {
            $result = $result->where('branch_id', $branch_id);
        }
        $result = $result->with('prize_cc', 'branch');
        return DataTables::of($result)
            ->addColumn('promotion_name', function ($data) {
                if (isset($data->prize_cc)) {
                    if (isset($data->prize_cc->promotion)) {
                        return $data->prize_cc->promotion->name;
                    } else {
                        return '';
                    }
                }
                return '';
            })
            ->addColumn('branch_name', function ($data) {
                if (isset($data->branch)) {
                    return $data->branch->branch_name_eng;
                }
                return '';
            })
            ->editColumn('prize_name', function ($data) {
                if (isset($data->prize_cc)) {
                    return $data->prize_cc->prizeItem->name;
                }
                return '';
            })
            ->editColumn('used_qty', function ($data) {
                return $data->total_qty - $data->remain_qty;
            })

            ->make(true);
    }
}
