<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallerCardTransferLog extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "installer_card_transfer_logs";
    protected $fillable = [
        'uuid',
        'transfer_type',
        'old_installer_card_card_number',
        'new_installer_card_card_number',
        'transferred_points',
        'transferred_amount',
        'transferred_credit_points',
        'transferred_credit_amount',
        'user_uuid'
    ];
}
