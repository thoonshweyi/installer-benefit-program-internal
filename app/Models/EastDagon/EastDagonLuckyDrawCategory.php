<?php

namespace App\Models\EastDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EastDagonLuckyDrawCategory extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_eastdagon';
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