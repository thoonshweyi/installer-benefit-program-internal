<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeownerInstaller extends Model
{
    use HasFactory;
    protected $table = "homeowner_installers";
    protected $fillable = [
      "installer_card_card_number",
      "home_owner_uuid"
    ];

    public function homeowner(){
        return $this->belongsTo(HomeOwner::class,"home_owner_uuid","uuid");
    }
}
