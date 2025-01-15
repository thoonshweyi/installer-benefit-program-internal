<?php

namespace App\Models\TerminalM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminalMLuckyDrawBranch extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_terminalm';
    protected $table="promotion_branches";
    public $timestamps = false;
    protected $fillable = [
        'promotion_uuid',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo('App\Models\TerminalM\TerminalMBranch','branch_id','branch_id');
    }
}