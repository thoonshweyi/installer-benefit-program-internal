<?php

namespace App\Models\HlaingTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HlaingTharyarLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_hlaingtharyar';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\HlaingTharyar\HlaingTharyarBranch','branch_id','branch_id');
    }
}