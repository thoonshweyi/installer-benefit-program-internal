<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "point_rules";
    protected $fillable = [
        'uuid',
        'point_promotion_uuid',
        'category_id',
        // 'group_id',
        'redemption_value',
    ];

    public function pointpromotion(){
        return $this->belongsTo('App\Models\PointPromotion','point_promotion_uuid','uuid');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','maincatid');
    }

    public function pointrulegroups()
    {
        return $this->hasMany(PointRuleGroup::class,'point_rule_uuid','uuid');
    }

    public function groupsfilterBycategory_id(){

        $groups = AllCategory::where('maincatid',$this->category_id)->distinct('product_group_id')->get();
        // dd($groups);
        return $groups;
    }
}
