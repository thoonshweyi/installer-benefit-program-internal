<?php

namespace App\Models;

use App\Models\AmountCheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AmountCheckBranch extends Model
{
    use HasFactory;
    protected $table = "amount_check_branch";
    protected $fillable = [
        'branch_id',
        'amount_check_uuid',
    ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
    public function amountchecks(){
        return $this->belongsTo('App\Models\AmountCheck','uuid','amount_check_uuid');
    }
}


