<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallerCardTransferFile extends Model
{
    use HasFactory;
    protected $table = "installer_card_transfer_files";
    protected $primaryKey = "id";
    protected $fillable = [
         "installer_card_transfer_log_uuid",
         "image",
    ];

    // public function redemptiontransaction(){
    //      return $this->belongsTo(RedemptionTransaction::class);
    // }
}
