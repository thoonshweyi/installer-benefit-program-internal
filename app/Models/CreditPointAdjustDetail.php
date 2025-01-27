<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPointAdjustDetail extends Model
{
    use HasFactory;
    protected $table = "credit_point_adjust_details";
    protected $fillable = [
        "point_based",
        "points_adjusted",
        "amount_adjusted",
        "installer_card_point_uuid",
        "point_adjust_uuid"
    ];
}
