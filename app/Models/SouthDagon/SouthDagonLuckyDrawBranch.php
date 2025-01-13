<?php

namespace App\Models\SouthDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouthDagonLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_southdagon';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\SouthDagon\SouthDagonBranch','branch_id','branch_id');
    }
}