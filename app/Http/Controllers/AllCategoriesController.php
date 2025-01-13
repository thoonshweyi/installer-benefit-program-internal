<?php

namespace App\Http\Controllers;

use App\Models\AllCategory;
use Illuminate\Http\Request;

class AllCategoriesController extends Controller
{


    public function groupfilterbyMaincatid($maincatid){
        $groups = AllCategory::where('maincatid',$maincatid)->distinct('product_group_id')->get();

        return response()->json(["groups"=>$groups]);
    }
}
