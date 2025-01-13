<?php

namespace App\Models\HlaingTharyar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HlaingTharyarLuckyDrawCategory extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_hlaingtharyar';
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