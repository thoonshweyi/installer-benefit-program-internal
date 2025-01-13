<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCheckBranch extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'product_check_uuid',
    ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
    public function productchecks(){
        return $this->belongsTo('App\Models\LuckyDraw','uuid','product_check_uuid');
    }
}
