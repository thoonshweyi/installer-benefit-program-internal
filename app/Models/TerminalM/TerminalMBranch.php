<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_terminalm';
    protected $table="master_branch";

    protected $fillable = [
        'branch_id', 'branch_code', 'branch_name', 'branch_short_name', 'branch_address', 'branch_phone_no', 'branch_active'
    ];
}