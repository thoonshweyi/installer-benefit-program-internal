<?php

namespace App\Models\AyeTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyeTharyarLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_ayetharyar';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\AyeTharyar\AyeTharyarBranch','branch_id','branch_id');
    }
}