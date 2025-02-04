<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Jobs\SyncRowJob;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    public function sync(Request $request){
        // dd('hi');

        $branch = Branch::where('branch_id',14)->first();
        // dd($branch);
        dispatch(new SyncRowJob("branches","insert",$branch));
    }
}
