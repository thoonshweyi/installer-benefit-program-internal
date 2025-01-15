<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_terminalm';
    protected $table="promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\TerminalM\TerminalMBrand','brand_id','product_brand_id');
    }
}