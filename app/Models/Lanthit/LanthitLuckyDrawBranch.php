<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_lanthit';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Lanthit\LanthitBranch','branch_id','branch_id');
    }
}