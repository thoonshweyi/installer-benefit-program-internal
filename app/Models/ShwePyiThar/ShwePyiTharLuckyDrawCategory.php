<?php

namespace App\Models\ShwePyiThar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShwePyiTharLuckyDrawCategory extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_shwepyithar';
    protected $table="promotion_categories";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'category_id'
    ];

    public function categories(){
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
}
