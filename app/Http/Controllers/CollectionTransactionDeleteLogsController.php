<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CollectionTransactionDeleteLog;

class CollectionTransactionDeleteLogsController extends Controller
{
    public function index(){
        $branch_id = getCurrentBranch();
        $collectiontransactiondeletelogs = CollectionTransactionDeleteLog::
                                where('action_branch_id',$branch_id)
                                ->orderBy("created_at",'desc')
                                ->paginate(10);

        // Fetch related user UUIDs
        $actionuserUuids = $collectiontransactiondeletelogs->pluck('action_user_uuid')->filter();
        // Fetch users from the local branch database
        $users = DB::table('users')
            ->whereIn('uuid', $actionuserUuids)
            ->get()
            ->keyBy('uuid'); // Index users by UUID for easy mapping
        // Map user details to installer cards
        $collectiontransactiondeletelogs->each(function ($collectiontransactiondeletelog) use ($users) {
            $collectiontransactiondeletelog->actionuser = $users->get($collectiontransactiondeletelog->action_user_uuid);
        });
        return view("collectiontransactiondeletelogs.index",compact('collectiontransactiondeletelogs'));
    }

}
