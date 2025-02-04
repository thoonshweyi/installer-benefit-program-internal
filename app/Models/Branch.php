<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $timezone = null;
    protected $fillable = [
        'branch_id', 'branch_code', 'branch_name_eng', 'branch_short_name', 'branch_address', 'branch_phone_no', 'branch_active'
    ];
}
