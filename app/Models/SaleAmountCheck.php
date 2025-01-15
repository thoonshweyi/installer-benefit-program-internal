<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleAmountCheck extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "sale_amount_checks";
    protected $fillable = [
        'uuid',
        'primary_phone',
        'total_sale_amount',
        'branch_id',
        'user_uuid',
    ];

    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function user(){
        return $this->belongsTo(User::class,"user_uuid","uuid");
    }

}
