<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointPromotionBranch extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table="point_promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'point_promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }

    public function promotions(){
        return $this->belongsTo('App\Models\PointPromotion','point_promotion_uuid','uuid');
    }
}
