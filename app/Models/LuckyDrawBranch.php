<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyDrawBranch extends Model
{
    use HasFactory;
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }

    public function promotions(){
        return $this->belongsTo('App\Models\LuckyDraw','promotion_uuid','uuid');
    }
}