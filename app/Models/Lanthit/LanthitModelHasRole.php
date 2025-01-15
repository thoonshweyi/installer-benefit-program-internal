<?php

namespace App\Models\Lanthit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanthitModelHasRole extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $connection = 'pgsql_lanthit';
    protected $table = 'model_has_roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

}
