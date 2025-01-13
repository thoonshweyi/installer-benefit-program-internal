<?php

namespace App\Models\Mawlamyine;

use Illuminate\Database\Eloquent\Model;

class MawlamyineCustomer extends Model
{
    protected $connection = 'pgsql_mawlamyine';
    protected $table = 'customers';

    protected $fillable = [ 
        'uuid',
        'customer_id', 
        'titlename',
        'firstname',
        'lastname',
        'phone_no',
        'nrc_no',
        'nrc_name',
        'nrc_short',
        'nrc_number',
        'passport',
        'email',
        'phone_no_2',
        'national_id',
        'member_no',
        'amphur_id',
        'province_id',
        'address',
        'customer_no',
        'customer_type',
    ];
}
