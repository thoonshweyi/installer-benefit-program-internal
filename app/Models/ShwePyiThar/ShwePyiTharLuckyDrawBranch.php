<?php

namespace App\Models\ShwePyiThar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShwePyiTharLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_shwepyithar';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\ShwePyiThar\ShwePyiTharBranch','branch_id','branch_id');
    }
}
