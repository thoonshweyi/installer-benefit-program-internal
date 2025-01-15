<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHeader extends Model
{
    use HasFactory;
    protected $table = 'ticket_headers';
    protected $fillable = [
        'uuid',
        'promotion_uuid',
        'ticket_header_no',
        'customer_uuid',
        'total_valid_amount',
        'total_remain_amount',
        'total_valid_ticket_qty',
        'created_at',
        'created_by',
        'reprint',
        'printed_at',
        'printed_by',
        'status',
        'branch_id',
        'ticket_type',
        'canceled_at',
        'canceled_by',
    ];
    public function printed_users(){
        return $this->belongsTo('App\Models\User','printed_by','uuid');
    }
    public function created_users(){
        return $this->belongsTo('App\Models\User','created_by','uuid');
    }
    public function canceled_users(){
        return $this->belongsTo('App\Models\User','canceled_by','uuid');
    }
    public function customers(){
        return $this->belongsTo('App\Models\Customer','customer_uuid','uuid');
    }

    public function invoices(){
        return $this->hasMany('App\Models\TicketHeaderInvoice','ticket_header_uuid','uuid');
    }

    public function promotions(){
        return $this->belongsTo('App\Models\LuckyDraw','promotion_uuid','uuid');
    }

    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
    public function tickets(){
        return $this->hasMany('App\Models\Ticket','id','ticket_id');
    }

}
