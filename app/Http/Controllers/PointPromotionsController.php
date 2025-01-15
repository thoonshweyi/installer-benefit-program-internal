<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Branch;
use App\Jobs\SyncRowJob;
use App\Models\Category;
use App\Models\PointRule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PointPromotion;
use App\Models\PointRuleGroup;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\PointPromotionBranch;
use Illuminate\Support\Facades\Auth;

class PointPromotionsController extends Controller
{

    public function index()
    {
        $pointpromotions = PointPromotion::orderBy('created_at', 'desc')->paginate(10);
        // dd($pointpromotions);
        return view('pointpromotions.index', compact("pointpromotions"));
    }

    public function create()
    {
        $branches = Branch::select('branch_id', 'branch_name_eng')
            ->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28])
            ->get();
        $categories = getAllCategory();


        return view('pointpromotions.create',compact('branches', 'categories'));
    }

    public function store(Request $request)
    {
        // dd($request->group_id);



        request()->validate([
            'name' => 'required|string|max:50|unique:point_promotions,name',
            // 'name' => [
            //     'required',
            //     'string',
            //     'max:50',
            //     function ($attribute, $value, $fail) {
            //         // Query the centralized database to check uniqueness
            //         $exists = DB::connection('centralpgsql') // Use the 'centralpgsql' connection
            //             ->table('point_promotions') // Target the 'point_promotions' table
            //             ->where('name', $value) // Check if the 'name' already exists
            //             ->exists();

            //         if ($exists) {
            //             $fail("The {$attribute} has already been taken in the centralized database.");
            //         }
            //     },
            // ],
            'pointperamount' => "required",
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'branch_id'=> 'required',
            'category_id' => 'required|array',
            'redemption_value' => 'required|array',
            'redemption_value.*' => 'required|numeric|min:0',

            'discon_status' => 'required',
        ], [
            'category_id.required' => 'Please select a category.',
            'category_id.*.exists' => 'Selected category is invalid.',

            'redemption_value.*.required' => 'Please enter the redemption value.',
            'redemption_value.*.numeric' => 'Redemption value must be a valid number.',
            'redemption_value.*.min' => 'Redemption value must be at least 0.',
        ]);

        // dd($request);

        \DB::beginTransaction();
        try{
            $user = Auth::user();
            $user_uuid = $user->uuid;

            $pointpromotion = new PointPromotion();
            $pointpromotion->uuid = (string) Str::uuid();
            $pointpromotion->name = $request->name;
            $pointpromotion->pointperamount = $request->pointperamount;
            $pointpromotion->start_date = $request->start_date;
            $pointpromotion->end_date = $request->end_date;
            $pointpromotion->status = $request->status;
            $pointpromotion->discon_status = $request->discon_status;
            $pointpromotion->remark = $request->remark;
            $pointpromotion->user_uuid = $user_uuid;
            $pointpromotion->save();
            dispatch(new SyncRowJob("point_promotions","insert",$pointpromotion));

            $branch_ids = $request->branch_id;
            foreach ($branch_ids as $branch_id)
            {
                $pointPromotionBranch['point_promotion_uuid'] = $pointpromotion->uuid;
                $pointPromotionBranch['branch_id'] = $branch_id;
                PointPromotionBranch::create($pointPromotionBranch);
            }

            $category_ids = $request->category_id;
            $redemption_values = $request->redemption_value;
            // dd($category_ids);
            // dd($redemption_values);
            $rulegroup_ids = $request->group_id;

            foreach($category_ids as $key=>$category_id){
                $pointRule['uuid'] = (string) Str::uuid();
                $pointRule['point_promotion_uuid'] = $pointpromotion->uuid;
                $pointRule['category_id'] = $category_id;
                $pointRule['redemption_value'] = $redemption_values[$key];

                $newpointrule =  PointRule::create($pointRule);

                foreach($rulegroup_ids[$key] as $rulegroup_id){
                    PointRuleGroup::create([
                        "point_rule_uuid"=> $newpointrule->uuid,
                        "group_id"=>$rulegroup_id
                    ]);
                    // $pointRule['group_id'] = $rulegroup_id;
                }
            }

            // Commit the transaction
            \DB::commit();

            return redirect()->route('pointpromos.index')->with("success","Point Promotion created successfully");
        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('pointpromos.index')->with("error","There is an error in creation Point Promotion");
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $pointpromotion = PointPromotion::where('uuid', $uuid)->first();
        $pointpromotionbranches = PointPromotionBranch::where('point_promotion_uuid',$pointpromotion->uuid)->pluck('branch_id');
        $pointrules = PointRule::where('point_promotion_uuid',$pointpromotion->uuid)->get();
        // dd($pointpromotionbranches);
        $branches = Branch::select('branch_id', 'branch_name_eng')
            ->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28])
            ->get();
        $categories = getAllCategory();

        return view('pointpromotions.edit',compact('pointpromotion','pointpromotionbranches','pointrules','branches', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        // dd('hay');
        request()->validate([
            'name' => 'required|string|max:50|unique:point_promotions,name,'. $uuid . ',uuid',
            // 'name' => [
            //     'required',
            //     'string',
            //     'max:50',
            //     function ($attribute, $value, $fail) use ($uuid) {
            //         $exists = DB::connection('centralpgsql')
            //             ->table('point_promotions')
            //             ->where('name', $value)
            //             ->where('uuid', '!=', $uuid) // Exclude the current record
            //             ->exists();

            //         if ($exists) {
            //             $fail("The {$attribute} has already been taken in the centralized database.");
            //         }
            //     },
            // ],
            'pointperamount' => "required",
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'branch_id'=> 'required',
            'category_id' => 'required|array',
            'redemption_value' => 'required|array',
            'redemption_value.*' => 'required|numeric|min:0',

            'discon_status' => 'required',
        ], [
            'category_id.required' => 'Please select a category.',
            'category_id.*.exists' => 'Selected category is invalid.',

            'redemption_value.*.required' => 'Please enter the redemption value.',
            'redemption_value.*.numeric' => 'Redemption value must be a valid number.',
            'redemption_value.*.min' => 'Redemption value must be at least 0.',
        ]);


        \DB::beginTransaction();
        try{
            $user = Auth::user();
            $user_uuid = $user->uuid;

            $pointpromotion = PointPromotion::where('uuid',$uuid)->first();
            $pointpromotion->name = $request->name;
            $pointpromotion->pointperamount = $request->pointperamount;
            $pointpromotion->start_date = $request->start_date;
            $pointpromotion->end_date = $request->end_date;
            $pointpromotion->status = $request->status;
            $pointpromotion->discon_status = $request->discon_status;

            $pointpromotion->remark = $request->remark;
            $pointpromotion->user_uuid = $user_uuid;
            $pointpromotion->save();
            dispatch(new SyncRowJob("point_promotions","update",$pointpromotion));


            $pointpromotion->pointpromotionbranches()->delete();
            $branch_ids = $request->branch_id;
            foreach ($branch_ids as $branch_id)
            {
                $pointPromotionBranch['point_promotion_uuid'] = $pointpromotion->uuid;
                $pointPromotionBranch['branch_id'] = $branch_id;
                PointPromotionBranch::create($pointPromotionBranch);
            }

            $category_ids = $request->category_id;
            $redemption_values = $request->redemption_value;
            // dd($category_ids);
            // dd($redemption_values);
            $rulegroup_ids = $request->group_id;

            PointRule::where('point_promotion_uuid',$pointpromotion->uuid)->delete();
            foreach($category_ids as $key=>$category_id){
                $pointRule['uuid'] = (string) Str::uuid();
                $pointRule['point_promotion_uuid'] = $pointpromotion->uuid;
                $pointRule['category_id'] = $category_id;
                $pointRule['redemption_value'] = $redemption_values[$key];

                $newpointrule =  PointRule::create($pointRule);

                foreach($rulegroup_ids[$key] as $rulegroup_id){
                    PointRuleGroup::create([
                        "point_rule_uuid"=> $newpointrule->uuid,
                        "group_id"=>$rulegroup_id
                    ]);
                    // $pointRule['group_id'] = $rulegroup_id;
                }
            }

            // Commit the transaction
            \DB::commit();

            return redirect()->route('pointpromos.index')->with("success","Point Promotion updated successfully");
        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('pointpromos.index')->with("error","There is an error in creation Point Promotion");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $pointpromotion = PointPromotion::where('uuid',$uuid)->first();
        $pointpromotionId = $pointpromotion->id;
        $pointpromotion->delete();
        dispatch(new SyncRowJob("point_promotions", "delete", ['id' => $pointpromotionId]));

        return redirect()->route('pointpromos.index')->with("success","Point Promotion deleted successfully");
    }

    public function search(Request $request){
        $queryname = $request->name;
        // dd($queryname);

        $results = PointPromotion::query();
        // dd($results);
        if($queryname){
            $results = $results->where('name','LIKE','%'.$queryname.'%');
        }

        $pointpromotions = $results->paginate(10);
        // dd($results);

        return view('pointpromotions.index',compact("pointpromotions"));
    }
}
