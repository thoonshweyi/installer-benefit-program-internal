<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerIPs extends Model
{
    use HasFactory;
    protected $table ='customer_ips';
    protected $fillable = [
                'uuid',
                'no',
                'branch_id',
                'ip_address'
                ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id');
    }
}
