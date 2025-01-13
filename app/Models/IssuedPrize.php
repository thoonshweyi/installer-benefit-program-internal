<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\LuckyDraw;
use App\Models\TicketHeaderInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssuedPrize extends Model
{
    use HasFactory;
    protected $fillable = [
            'uuid',
            'branch_id',
            'prize_date',
            'prize_code',
            'customer_uuid',
            'prize_qty',
            'prize_amount',
            'prize_total_amount',
            'ticket_header_uuid',
            'promotion_uuid',
            'sub_promotion_uuid',
            'sale_amount',
            'prize_type',
            'serial_no',
    ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
    public function customers(){
        return $this->belongsTo('App\Models\Customer','customer_uuid','uuid');
    }
    public function ticket_header_invoice(){
        return $this->belongsTo('App\Models\TicketHeaderInvoice','ticket_header_uuid','ticket_header_uuid');
    }
    public function promotions(){
        return $this->belongsTo('App\Models\LuckyDraw','promotion_uuid','uuid');
    }
}
