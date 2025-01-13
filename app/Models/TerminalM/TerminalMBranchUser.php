<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMBranchUser extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_terminalm';
    protected $table="branch_users";

    protected $fillable = [
        'user_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
}