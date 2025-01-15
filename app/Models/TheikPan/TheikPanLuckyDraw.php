<?php

namespace App\Models\TheikPan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheikPanLuckyDraw extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_theikpan';
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
