<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRuleGroup extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "point_rule_groups";
    protected $fillable = [
        'point_rule_uuid',
        'group_id',
    ];

    public function pointrule(){
        return $this->belongsTo('App\Models\PointRule','point_rule_uuid','uuid');
    }

}
