<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\PointPromotion;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionTransactionsResource extends JsonResource
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
            'uuid'=> $this->uuid,
            'point_promotion_uud'=> $this->point_promotion_uud,
            'points_award_rate'=> $this->points_award_rate,
            'branch_id'=> $this->branch_id,
            'document_no'=> $this->document_no,
            'installer_card_card_number'=>$this->installer_card_card_number,
            'invoice_number'=>$this->installer_card_card_number,
            'total_sale_cash_amount'=> $this->total_sale_cash_amount,
            'total_points_collected'=> $this->total_points_collected,
            'total_save_value'=> $this->total_save_value,
            'collection_date'=> $this->collection_date,
            'user_uuid'=> $this->user_uuid,
            'buy_date'=> $this->buy_date,
            'gbh_customer_id'=> $this->gbh_customer_id,
            'sale_cash_document_id'=> $this->sale_cash_document_id,
            'branch_code'=> $this->branch_code,
            "created_at"=>$this->created_at->format("d m Y"),
            "updated_at"=>$this->updated_at->format("d m Y"),

            // "country"=>Country::where("id",$this->country_id)->select(["id","name"])->first(),
            "pointpromotion"=>PointPromotion::where("uuid",$this->point_promotion_uud)->select(["uuid","name"])->first(),
            "user"=>User::where("uuid",$this->user_uuid)->select(["uuid","name"])->first(),
            // "status"=>Status::where("id",$this->status_id)->select(["id","name"])->first()
       ];
    }
}
