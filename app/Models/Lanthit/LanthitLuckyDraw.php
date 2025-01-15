<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitLuckyDraw extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_lanthit';
    protected $table = "promotions";
    protected $fillable = [
        'uuid',
        'name',
        'start_date',
        'end_date',
        'prefix',
        'number_of_ticket',
        'amount_for_one_ticket',
        'status',
        'remark',
        'discon_status',
        'lucky_draw_type_uuid',
    ];

}
