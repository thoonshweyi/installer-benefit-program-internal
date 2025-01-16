<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeownerInstallerHistory extends Model
{
    use HasFactory;
    protected $table = "homeowner_installer_histories";
    protected $fillable = [
      "installer_card_card_number",
      "home_owner_uuids",
      "user_uuid",
    ];

    // public function homeowner(){
    //     return $this->belongsTo(HomeOwner::class,"home_owner_uuid","uuid");
    // }

    public function homeowners(){
        $home_owner_uuids = json_decode($this->home_owner_uuids,true); // Decode Json-encoded tags

        $homeowners = HomeOwner::whereIn('uuid',$home_owner_uuids)->get(); // Fetch users in a single query
        return $homeowners;
    }

    public function user(){
        return $this->belongsTo(User::class,"user_uuid","uuid");
    }
}
