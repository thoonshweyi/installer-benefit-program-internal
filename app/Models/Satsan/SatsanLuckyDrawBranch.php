<?php

namespace App\Models\Satsan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatsanLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_satsan';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Satsan\SatsanBranch','branch_id','branch_id');
    }
}