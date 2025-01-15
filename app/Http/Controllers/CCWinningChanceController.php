<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CCWinningChance;
use App\Models\LuckyDrawBranch;
use App\Models\PrizeCCCheck;
use App\Models\WinningChanceHistory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CCWinningChanceController extends Controller
{
    public function view_winning_chance($promotion_uuid, $sub_promotion_uuid)
    {
        $winningChances = CCWinningChance::where('sub_promotion_uuid', $sub_promotion_uuid)
            ->where('promotion_uuid', $promotion_uuid)->get();
        $luckydraw_branches = [];
        return view('promotion.view_winning_chance', compact('promotion_uuid', 'sub_promotion_uuid', 'winningChances', 'luckydraw_branches'));
    }

    public function store_winning_chance(Request $request)
    {
        $prizeCCChecks = PrizeCCCheck::where('promotion_uuid', $request->promotion_uuid)->where('sub_promotion_uuid', $request->sub_promotion_uuid)
            ->with('prizeCCBranch')->get();

        $promotion_branchs = LuckyDrawBranch::where('promotion_uuid', $request->promotion_uuid)->get()->pluck('branch_id')->toarray();

        if (!$promotion_branchs) {
            $promotion_branchs = Branch::get()->pluck('branch_id')->toarray();
        }
        foreach ($prizeCCChecks as $prizeCCCheck) {
            $prizeCCCheckUUID = $prizeCCCheck->uuid;

            foreach ($promotion_branchs as $promotion_branch) {
                $cc_winning_chance['uuid'] = (string) Str::uuid();
                $cc_winning_chance['promotion_uuid'] = $request->promotion_uuid;
                $cc_winning_chance['sub_promotion_uuid'] = $request->sub_promotion_uuid;
                $cc_winning_chance['prize_cc_check_uuid'] = $prizeCCCheckUUID;
                $cc_winning_chance['branch_id'] = $promotion_branch;
                $cc_winning_chance['minimum_amount'] = $request->minimum_amount;
                $cc_winning_chance['winning_percentage'] = $this->find_default_winning_percentage($prizeCCChecks, $prizeCCCheck, $request->calculation_type);

                CCWinningChance::create($cc_winning_chance);
            }
        }

        return redirect()->route('view_winning_chance', [$request->promotion_uuid, $request->sub_promotion_uuid]);
    }

    public function find_default_winning_percentage($prizeCCChecks, $prizeCCCheck, $calculation_type)
    {
        if ($calculation_type == 1) {
            $default_winning_percentage = 100 / $prizeCCChecks->count();
            return (int) $default_winning_percentage;
        } else {
            $total_qty = 0;
            foreach ($prizeCCChecks as $pCCCheck) {
                $total_qty += $pCCCheck->prizeCCBranch->remain_qty;
            }

            $default_winning_percentage = 100 * ($prizeCCCheck->prizeCCBranch->remain_qty / $total_qty);
            return (int) $default_winning_percentage;
        }
    }
    public function winning_result(Request $request)
    {
        $result = CCWinningChance::select("*")->DISTINCT('branch_id', 'minimum_amount')
            ->where('sub_promotion_uuid', $request->sub_promotion_uuid)
            ->where('promotion_uuid', $request->promotion_uuid)->get();
        return DataTables::of($result)
            ->editColumn('branch_id', function ($data) {
                if (isset($data->branch_id)) {
                    return $data->branches->branch_name_eng;
                }
                return '';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function winning_chance_destory($id)
    {
        $winning_chance = CCWinningChance::where('uuid', $id)->first();
        $winning_chance_amount = CCWinningChance::where('minimum_amount', $winning_chance->minimum_amount)
            ->where('promotion_uuid', $winning_chance->promotion_uuid)
            ->where('sub_promotion_uuid', $winning_chance->sub_promotion_uuid)
            ->get();
        foreach ($winning_chance_amount as $w_c_a) {

            $w_c_a->delete();
        }
        return response()->json([
            'success' => 'Winning Chance is deleted successfully',
        ]);
    }

    public function winning_chance_edit($uuid)
    {
        $cCWinningChances = CCWinningChance::select('branch_id', 'minimum_amount', 'sub_promotion_uuid','promotion_uuid')->where('uuid', $uuid)->first();
        $same_branch_and_amounts = CCWinningChance::select('c_c_winning_chances.uuid as main_uuid', 'c_c_winning_chances.winning_percentage', 'prize_items.name')
            ->where('c_c_winning_chances.branch_id', $cCWinningChances->branch_id)
            ->where('c_c_winning_chances.minimum_amount', $cCWinningChances->minimum_amount)
            ->where('c_c_winning_chances.sub_promotion_uuid', $cCWinningChances->sub_promotion_uuid)
            ->where('c_c_winning_chances.promotion_uuid', $cCWinningChances->promotion_uuid)
            ->join('prize_c_c_checks', 'prize_c_c_checks.uuid', 'c_c_winning_chances.prize_cc_check_uuid')
            ->join('prize_items', 'prize_items.uuid', 'prize_c_c_checks.prize_item_uuid')
            ->get()->toarray();
        $winning_chance_info = CCWinningChance::where('c_c_winning_chances.branch_id', $cCWinningChances->branch_id)
            ->where('c_c_winning_chances.minimum_amount', $cCWinningChances->minimum_amount)
            ->join('branches', 'branches.branch_id', 'c_c_winning_chances.branch_id')
            ->first();
        return response()->json([
            'infor' => [
                'branch_name' => $winning_chance_info->branch_name_eng,
                'winning_chance_amount' => $winning_chance_info->minimum_amount,
            ],
            'data' => $same_branch_and_amounts,
        ]);
    }
    public function winning_chance_percentage_store(Request $request, CCWinningChance $cCWinningChance)
    {
        $i = 0;
        foreach ($request->main_uuid as $main_uuid) {
            $winning_uuid = CCWinningChance::where('uuid', $main_uuid)->first();
            ////store winning history/////
            $search_uuid = WinningChanceHistory::where('c_c_winning_chance_uuid', $main_uuid)->first();
            $uuid = (string) Str::uuid();
            $winning_chance_history['uuid'] = $uuid;
            $winning_chance_history['user_uuid'] = auth()->user()->uuid;
            $winning_chance_history['c_c_winning_chance_uuid'] = $main_uuid;
            $winning_chance_history['winning_percentage'] = $request->winning_chance[$i];
            if ($search_uuid) {
                if ($request->winning_chance[$i] != $winning_uuid->winning_percentage) {
                    $winning_chance_history['action'] = 'update';
                    WinningChanceHistory::create($winning_chance_history);
                }
            } else {
                $user = User::where('uuid', auth()->user()->uuid)->first();
                $winning_chance_history['action'] = 'create';
                WinningChanceHistory::create($winning_chance_history);
            }
            $winning_chance_percentage['winning_percentage'] = $request->winning_chance[$i];
            $winning_uuid->update($winning_chance_percentage);
            $i++;
        }
        return back()->withInput();
    }
}
