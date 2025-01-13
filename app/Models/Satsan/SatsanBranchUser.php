<?php

namespace App\Models\Satsan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatsanBranchUser extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_satsan';
    protected $table="branch_users";

    protected $fillable = [
        'user_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
}