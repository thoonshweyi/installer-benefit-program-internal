<?php

namespace App\Models\Tampawady;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TampawadyLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_tampawady';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Tampawady\TampawadyBranch','branch_id','branch_id');
    }
}