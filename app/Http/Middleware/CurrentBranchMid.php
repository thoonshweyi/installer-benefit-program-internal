<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Branch;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrentBranchMid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if(Auth::check() && Auth::user()->branch_id != null){
            return $next($request);
        }

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $userbranches = BranchUser::where("user_uuid",$user_uuid)->pluck("branch_id");
        $branches = Branch::whereIn("branch_id",$userbranches)->get();
        if(count($branches) == 1){
            // dd($branches->first());
            $branch_id = $branches->first()->branch_id;
            $user->branch_id = $branch_id;
            $user->save();

            return $next($request);
        }
        return redirect()->route("customauth.currentbranch");
    }
}
