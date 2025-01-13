<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory,SoftDeletes;
      /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'document_no', 
        'document_type', 
        'branch_id',
        'supplier_id',
        'document_date',
        'document_status',
        'operation_id',
        'operation_updated_datetime',
        'branch_manager_id',
        'branch_manager_updated_datetime',
        'operation_attach_file',
        'operation_remark',
        
        'category_head_id',
        'category_head_updated_datetime',
        'delivery_date',
        'merchandising_manager_id',
        'merchandising_manager_updated_datetime',
        'merchandising_remark',
        'merchandising_attach_file',
        
        'operation_rg_out_id',
        'operation_rg_out_updated_datetime',
        'operation_rg_out_attach_file',

        'accounting_cn_id',
        'accounting_cn_updated_datetime',
        'accounting_cn_attach_file',
        'accounting_remark',

        'operation_rg_in_id',
        'operation_rg_in_updated_datetime',
        'operation_rg_in_attach_file',

        'accounting_db_id',
        'accounting_db_updated_datetime',
        'accounting_db_attach_file',

        'exchange_to_return',
        'exchange_to_return_bm',
        'document_remark',
        'category_id', 
    ];
    public function branches(){
        return $this->belongsTo('App\Models\Branch','branch_id','branch_id');
    }
    public function rg_out()
    {
       return $this->belongsTo(User::class,'operation_rg_out_id');
    }
    public function branch_manager()
    {
       return $this->belongsTo(User::class,'branch_manager_id');
    }
    public function suppliers()
    {
       return $this->belongsTo(Supplier::class,'supplier_id','vendor_id');
    }
    public function DocumentStatus()
    {
       return $this->belongsTo(DocumentStatus::class,'document_status','document_status');
    }
    public function Category()
    {
       return $this->belongsTo(Category::class,'category_id','product_category_id');
    }

}
