<?php

namespace App\Models\Bago;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagoLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_bago';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Bago\BagoBranch','branch_id','branch_id');
    }
}
