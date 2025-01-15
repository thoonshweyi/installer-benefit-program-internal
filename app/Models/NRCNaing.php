<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NRCNaing extends Model
{
    use HasFactory;
    protected $table = 'nrc_naings';
    public $timestamps = false;
    protected $fillable = [
        'id', 
        'nrc_number_name', 
    ];
}
