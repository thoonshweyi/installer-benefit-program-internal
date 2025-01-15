<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionTransactionDeleteLog extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "collection_transaction_delete_logs";
    protected $fillable = [
        'action_user_uuid',
        'action_branch_id',
        'old_collection_transaction_uuid',
        'point_promotion_uud',
        'points_award_rate',
        'branch_id',
        'document_no',
        'installer_card_card_number',
        'invoice_number',
        'total_sale_cash_amount',
        'total_points_collected',
        'total_save_value',
        'collection_date',
        'user_uuid',
    ];

    public function actionuser() {
        return $this->belongsTo(User::class, "action_user_uuid", "uuid");
    }

    public function actionbranch(){
        return $this->belongsTo(Branch::class,"action_branch_id","branch_id");
    }

    public function oldcollectiontransaction(){
        return $this->belongsTo('App\Models\CollectionTransaction','old_collection_transaction_uuid','uuid');
    }
}
