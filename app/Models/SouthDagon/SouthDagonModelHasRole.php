<?php

namespace App\Models\SouthDagon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouthDagonModelHasRole extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $connection = 'pgsql_southdagon';
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
