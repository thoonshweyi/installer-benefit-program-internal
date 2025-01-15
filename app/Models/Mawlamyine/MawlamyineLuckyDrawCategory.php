<?php

namespace App\Models\Mawlamyine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MawlamyineLuckyDrawCategory extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mawlamyine';
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