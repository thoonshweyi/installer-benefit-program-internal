<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function currentbranch(){
        $user = Auth::user();
        $user_uuid = $user->uuid;
        $userbranches = BranchUser::where("user_uuid",$user_uuid)->pluck("branch_id");
        $branches = Branch::whereIn("branch_id",$userbranches)->get();
        return view('customauth.currentbranch',compact('branches'));
    }

    public function store(Request $request){
        request()->validate([
            'branch_id' => 'required',
        ]);

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $user->branch_id = $request->branch_id;
        $user->save();
        return redirect()->route('home');
    }
}
