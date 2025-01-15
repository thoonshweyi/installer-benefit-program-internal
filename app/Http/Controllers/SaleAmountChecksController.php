<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CusSaleAmounts;
use App\Models\SaleAmountCheck;
use Illuminate\Support\Facades\DB;

class SaleAmountChecksController extends Controller
{
    public function index(){
        $branch_id = getCurrentBranch();
        $saleamountchecks = SaleAmountCheck::
                                where('branch_id',$branch_id)
                                ->orderBy("created_at",'desc')
                                ->paginate(10);

        // **Preparation for multiple branch deployment
        // Fetch related user UUIDs
        // $userUuids = $saleamountchecks->pluck('user_uuid')->filter();
        // // Fetch users from the local branch database
        // $users = DB::table('users')
        //     ->whereIn('uuid', $userUuids)
        //     ->get()
        //     ->keyBy('uuid'); // Index users by UUID for easy mapping
        // // Map user details to installer cards
        // $saleamountchecks->each(function ($saleamountcheck) use ($users) {
        //     $saleamountcheck->user = $users->get($saleamountcheck->user_uuid);
        // });


        return view("saleamountchecks.index",compact('saleamountchecks'));
    }

    public function show($uuid){
        $saleamountcheck = SaleAmountCheck::where('uuid',$uuid)->first();
        $cussaleamounts = CusSaleAmounts::where("sale_amount_check_uuid",$uuid)->get();

        // **Preparation for multiple branch deployment
        // $userUuid = $saleamountcheck->user_uuid; // Single value, not a collection
        // $user = User::where('uuid', $userUuid)->first();
        // // Assign the user model to the `prepareby` attribute
        // $saleamountcheck->user = $user;

        return view("saleamountchecks.show",compact(
            'saleamountcheck',
            'cussaleamounts'
        ));
    }


    public function search(Request $request){
        $queryprimary_phone = $request->primary_phone;

        $results = SaleAmountCheck::query();
        // dd($results);

        if($queryprimary_phone){
            $results = $results->where('primary_phone','LIKE','%'.$queryprimary_phone.'%');
        }


        $branch_id = getCurrentBranch();
        $saleamountchecks = $results->where('branch_id',$branch_id)->paginate(10);
        // dd($results);
        // Fetch related user UUIDs
        $userUuids = $saleamountchecks->pluck('user_uuid')->filter();
        // Fetch users from the local branch database
        $users = DB::table('users')
            ->whereIn('uuid', $userUuids)
            ->get()
            ->keyBy('uuid'); // Index users by UUID for easy mapping
        // Map user details to installer cards
        $saleamountchecks->each(function ($saleamountcheck) use ($users) {
            $saleamountcheck->user = $users->get($saleamountcheck->user_uuid);
        });

        return view("saleamountchecks.index",compact('saleamountchecks'));

    }


}
