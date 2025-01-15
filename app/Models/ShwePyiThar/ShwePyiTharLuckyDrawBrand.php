<?php

namespace App\Models\ShwePyiThar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShwePyiTharLuckyDrawBrand extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_shwepyithar';
    protected $table = "promotion_brands";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'brand_id'
    ];

    public function brands()
    {
        return $this->belongsTo('App\Models\ShwePyiThar\ShwePyiTharBrand', 'brand_id', 'product_brand_id');
    }
}
