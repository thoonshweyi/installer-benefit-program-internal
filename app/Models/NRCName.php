<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NRCName extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'nrc_names';
    protected $fillable = [
        'id',
        'nrc_number_id',
        'district',
    ];

}
