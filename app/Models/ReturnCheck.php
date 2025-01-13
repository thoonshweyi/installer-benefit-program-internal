<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCheck extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "return_checks";
    protected $fillable = [
        'branch_id',
        'invoice_number',
        'collection_transaction_uuid',
        'flag',
        'user_uuid'
    ];

    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    // public function user(){
    //     return $this->belongsTo(User::class,"user_uuid","uuid");
    // }

    public function collectiontransaction(){
        return $this->belongsTo(CollectionTransaction::class,"collection_transaction_uuid","uuid");
    }
}
