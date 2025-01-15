<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeOwner extends Model
{
    use HasFactory;
     // protected $connection = 'centralpgsql';

     protected $table = "home_owners";
     protected $fillable = [
        'uuid',
        'branch_id',

         'fullname',
         'phone',
         'address',
         'gender',
         'dob',
         'nrc',
         'passport',
         'identification_card',
         'member_active',
         'customer_active',
         'customer_rank_id',
         'customer_barcode',

         'titlename',
         'firstname',
         'lastnanme',
         'province_id',
         'amphur_id',
         'nrc_no',
         'nrc_name',
         'nrc_short',
         'nrc_number',
         'gbh_customer_id',


         'user_uuid',
     ];

     public function users(){
         return $this->belongsTo(User::class,"user_uuid","uuid");
     }

     public function branch(){
         return $this->belongsTo(Branch::class,"branch_id","branch_id");
     }

     public function customers(){
         return $this->belongsTo(Pos101GbhCustomer::class,"identification_card","identification_card");
     }

     protected static function boot(){
         parent::boot();

         // static::creating(function($installercard){
         //     $installercard->card_number = self::generatecardnumber();
         // });
     }
     protected static function generatecardnumber(){
         return \DB::transaction(function(){
             $latestinscard = \DB::table("installer_cards")->orderBy("id","desc")->first();
             $latestid= $latestinscard ?  $latestinscard->id : 0;
             $branchshortname = "LAN1";
             $newinscardnumber = "INS".$branchshortname.str_pad($latestid+1,5,"0",STR_PAD_LEFT);

             while(\DB::table("installer_cards")->where("card_number",$newinscardnumber)->exists()){
                 $latestid++;
                 $newinscardnumber = "INS".$branchshortname.str_pad($latestid+1,5,"0",STR_PAD_LEFT);
             }
             return $newinscardnumber;
         });
     }

     public function ismembercustomer(){
         return $this->identification_card != null && $this->member_active && $this->customer_active && $this->customer_rank_id == 1013;
     }

     public function installercardfiles(){
         return $this->hasMany(InstallerCardFile::class,"installer_card_card_number","card_number");
     }

}
