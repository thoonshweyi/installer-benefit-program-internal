<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'document_id', 
        'product_code_no', 
        'product_name',
        'product_unit',
        'stock_quantity',
        'return_quantity',
        'operation_actual_quantity',
        'merchandising_actual_quantity',
        'operation_rg_out_actual_quantity',
        'operation_rg_in_actual_quantity',
        'product_attach_file',
        'operation_remark',
        'rg_out_doc_no',
    ];

    public function document()
    {
        return $this->hasOne(Document::class, 'id', 'document_id');
    }
}
