<?php

namespace App\Models;

use App\Models\POS101\Pos101Province;
use App\Models\POS102\Pos102Province;
use App\Models\POS103\Pos103Province;
use App\Models\POS104\Pos104Province;
use App\Models\POS106\Pos106Province;
use App\Models\POS107\Pos107Province;
use App\Models\POS108\Pos108Province;
use App\Models\POS110\Pos110Province;
use App\Models\POS112\Pos112Province;
use App\Models\POS113\Pos113Province;
use App\Models\POS114\Pos114Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use App\Models\{Amphur,Province};


class Customer extends Model
{
    use HasFactory;

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
        'nrc',
        'foreigner'
    ];

    public function NRCNumbers()
    {
        return $this->belongsTo('App\Models\NRCNumber', 'nrc_no', 'id');
    }

    public function NRCNames()
    {
        return $this->belongsTo('App\Models\NRCName', 'nrc_name', 'id');
    }

    public function NRCNaings()
    {
        return $this->belongsTo('App\Models\NRCNaing', 'nrc_short', 'id');
    }

    public function amphurs()
    {

        return $this->belongsTo(Amphur::class,'amphur_id','amphur_id');

    }

    public function provinces()
    {

        return $this->belongsTo(Province::class,'province_id','province_id');

    }

    // public function nrc()
    // {
    //     return $this->
    // }



}
