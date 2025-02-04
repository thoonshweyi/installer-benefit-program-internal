<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallerCardsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            'card_number'=>$this->card_number,
            'branch_id'=>$this->branch_id,

            'fullname'=>$this->fullname,
            'phone'=>$this->phone,
            'address'=>$this->address,
            'gender'=>$this->gender,
            'dob'=>$this->dob,
            'nrc'=>$this->nrc,
            'passport'=>$this->passport,
            'identification_card'=>$this->identification_card,
            'member_active'=>$this->member_active,
            'customer_active'=>$this->customer_active,
            'customer_rank_id'=>$this->customer_rank_id,
            'customer_barcode'=>$this->customer_barcode,

            'titlename'=>$this->titlename,
            'firstname'=>$this->firstname,
            'lastnanme'=>$this->lastnanme,
            'province_id'=>$this->province_id,
            'amphur_id'=>$this->amphur_id,
            'nrc_no'=>$this->nrc_no,
            'nrc_name'=>$this->nrc_name,
            'nrc_short'=>$this->nrc_short,
            'nrc_number'=>$this->nrc_number,
            'gbh_customer_id'=>$this->gbh_customer_id,


            'totalpoints'=>$this->totalpoints,
            'totalamount'=>$this->totalamount,
            'credit_points'=>$this->credit_points,
            'credit_amount'=>$this->credit_amount,
            'expire_points'=>$this->expire_points,
            'expire_amount'=>$this->expire_amount,
            'issued_at'=>$this->issued_at,
            'user_uuid'=>$this->user_uuid,
            'status'=>$this->status,

            'approved_date'=>$this->approved_date,
            'bm_remark'=>$this->bm_remark,
            'stage'=>$this->stage,
            "created_at"=>$this->created_at->format("d m Y"),
            "updated_at"=>$this->updated_at->format("d m Y"),

            // "country"=>Country::where("id",$this->country_id)->select(["id","name"])->first(),
            "user"=>User::where("uuid",$this->user_uuid)->select(["uuid","name"])->first(),
            // "status"=>Status::where("id",$this->status_id)->select(["id","name"])->first()
       ];
    }
}
