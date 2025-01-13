<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardNumber extends Model
{
    use HasFactory;
    protected $table = "card_numbers";
    protected $fillable = [
        'card_number',
        'image',
        'card_number_generator_uuid',
    ];

}
