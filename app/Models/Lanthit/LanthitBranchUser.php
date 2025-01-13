<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitBranchUser extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_lanthit';
    protected $table="branch_users";

    protected $fillable = [
        'user_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
}