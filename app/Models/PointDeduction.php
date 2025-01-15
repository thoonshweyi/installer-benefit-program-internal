<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointDeduction extends Model
{
    use HasFactory;
    protected $table = "point_deductions";
    protected $fillable = [
        'installer_card_point_uuid',
        'points_decudted',
        'deduction_amount',
        'return_deduct_record_uuid'
    ];
}
