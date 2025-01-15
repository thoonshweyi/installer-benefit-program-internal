<?php

namespace App\Models\Mawlamyine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MawlamyineLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mawlamyine';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Mawlamyine\MawlamyineBranch','branch_id','branch_id');
    }
}