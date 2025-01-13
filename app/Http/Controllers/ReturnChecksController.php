<?php

namespace App\Http\Controllers;

use App\Models\ReturnCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnChecksController extends Controller
{
    public function index(){
        $branch_id = getCurrentBranch();
        $returnchecks = ReturnCheck::
                                where('branch_id',$branch_id)
                                ->orderBy("created_at",'desc')
                                ->paginate(10);

        // Fetch related user UUIDs
        $userUuids = $returnchecks->pluck('user_uuid')->filter();
        // Fetch users from the local branch database
        $users = DB::table('users')
            ->whereIn('uuid', $userUuids)
            ->get()
            ->keyBy('uuid'); // Index users by UUID for easy mapping
        // Map user details to installer cards
        $returnchecks->each(function ($returncheck) use ($users) {
            $returncheck->user = $users->get($returncheck->user_uuid);
        });

        return view("returnchecks.index",compact('returnchecks'));
    }
}
