<?php

namespace App\Models\TheikPan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheikPanLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_theikpan';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\TheikPan\TheikPanBranch','branch_id','branch_id');
    }
}