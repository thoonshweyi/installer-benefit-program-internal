<?php

namespace App\Models\ShwePyiThar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShwePyiTharBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_shwepyithar';
    protected $table="master_branch";

    protected $fillable = [
        'branch_id', 'branch_code', 'branch_name', 'branch_short_name', 'branch_address', 'branch_phone_no', 'branch_active'
    ];
}
