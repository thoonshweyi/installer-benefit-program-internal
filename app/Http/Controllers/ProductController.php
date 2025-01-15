<?php

namespace App\Http\Controllers;

use PDF as MPDF;
use App\Models\Product;
use App\Models\Document;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade as DomPDF;

class ProductController extends Controller
{
    protected function connection()
    {
        return new Product();
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try{
            $checkProductCount = Product::where('document_id',$request->document_id)->count();
            if($checkProductCount < 20){
                $filename = null;
                if ($request->product_attach_file) {
                    $request->validate([
                        'product_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                    ]);

                    // File::delete(public_path('images/attachFile/' . $document->operation_rg_in_attach_file));
                    $filename = 'op_' . auth()->id() . '_' . time() . '_'.$_FILES['product_attach_file']['name'];
                    $request->product_attach_file->move(public_path('images/attachFile'), $filename);
                }
                $request['operation_remark'] = $request->operation_remark;
                $request['operation_actual_quantity'] =  $request->operation_actual_quantity;
                $request['merchandising_actual_quantity'] =  $request->merchandising_actual_quantity;
                $request['operation_rg_out_actual_quantity'] =  $request->operation_rg_out_actual_quantity;
                $request['operation_rg_in_actual_quantity'] = $request->operation_rg_in_actual_quantity;
    
                if($request->product_id == ""){
                    Product::create($request->except(['product_attach_file']) + ['product_attach_file'=>$filename]);
                }else{
                    $product = Product::where('id',$request['product_id'])->first();
                    $filename = $filename ?? $product->product_attach_file;
                    $product->update($request->except(['product_attach_file']) + ['product_attach_file'=>$filename]);
                }
                return redirect()->route('documents.edit',$request->document_id)->with('success', 'Products is successfully added!');
            }
            return redirect()->route('documents.edit',$request->document_id)->with('error', 'Please Add Only 20 Products in One Document!');
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Manage Product!');
        }
    }

    public function show(Product $product)
    {
        try{
            return $product;
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to show Product!');
        }
    }

    public function edit(Product $products)
    {
        //
    }

    public function update(Request $request, Product $products)
    {
        //
    }

    public function destroy(Product $product)
    {
        try{
            $product->delete();
    
            return response()->json([
                'success' => 'Product deleted successfully!'
            ]);
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to delete Product!');
        }
    }

    protected function get_product_by_id($product_code, $branch_code)
    {
        try{
            $db_ext  = DB::connection('pgsql2');
            $spr = $db_ext->table('master_data.master_product')
            ->select('inventory.stock_poerp.brchcode as branch_code','master_data.master_product.product_code',
            'master_data.master_product_barcode.barcode_code as barcode',
            'master_data.master_product.product_name1 as product_name',
            'inventory.stock_poerp.sum as stock_sum',
            'master_data.master_product_unit.product_unit_name as product_unit',
            'master_data.master_product_barcode.product_unit_rate as unit_rate'
            )
            ->join('master_data.master_product_barcode','master_data.master_product.product_id','master_data.master_product_barcode.product_id')
            ->join('master_data.master_product_unit','master_data.master_product_barcode.product_unit_id','master_data.master_product_unit.product_unit_id')
            ->join('inventory.stock_poerp','master_data.master_product.product_code','inventory.stock_poerp.productcode')
            ->where('master_data.master_product.product_code',$product_code)
            ->where('inventory.stock_poerp.brchcode',$branch_code)
            ->get();
            if ($spr->isNotEmpty()) {
                return  response()->json(['data' => $spr[0]], 200);
            } else {
                return  response()->json(NULL, 200);
            }
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to get Products!');
        }
    }

    public function product_list_by_document(Request $request)
    {
        try{
            $document_id =  (!empty($_GET["document_id"])) ? ($_GET["document_id"]) : ('');
            $result = $this->connection()->where('document_id',$document_id)->with('document')->orderby('id')->get();

            return DataTables::of($result)
            ->editColumn('product_code_no', function ($data) {
                return $data->product_code_no ? $data->product_code_no : '';
            })
            ->editColumn('product_name', function ($data) {
                return $data->product_name ? $data->product_name : '';
            }) 
            ->editColumn('return_quantity', function ($data) {
                return $data->return_quantity;
            })
            ->editColumn('operation_actual_quantity', function ($data) {
                return $data->operation_actual_quantity;
            }) 
            ->editColumn('merchandising_actual_quantity', function ($data) {
                return $data->merchandising_actual_quantity;
            })  
            ->editColumn('operation_rg_out_actual_quantity', function ($data) {
                return $data->operation_rg_out_actual_quantity;
            })  
            ->editColumn('operation_rg_in_actual_quantity', function ($data) {
                return $data->operation_rg_in_actual_quantity;
            })  
            ->addColumn('action', function ($data) {
                return $data->document->document_status;
            }) 
            ->rawColumns(['action','operation','branch_manager',
            'category_head','merchandising_manager','operation_rg_out',
            'account_cn','operation_rg_in','account_db'])
            ->addIndexColumn()
            ->make(true);
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to get Products!');
        }
    }

    public function view_product_attach_file($product_id)
    {
        try{
            $product = Product::where('id',$product_id)->first();
            $user_name = Auth::user()->name;

            if (substr($product->product_attach_file, -3) == 'pdf') {
                return response()->file(public_path('images/attachFile/' . $product->product_attach_file));
            }
            $pdf = MPDF::loadView('products.view_product_attach_file', compact('product','user_name'));
            return $pdf->stream($product->product_code_no."_attached_File.pdf", array("Attachment" => false));
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to get Product Attach File!');
        }
    }

    public static function imagenABase64($ruta_relativa_al_public)
    {
        try{
            $path = $ruta_relativa_al_public;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = File::get($path);

            $base64 = "";
            if ($type == "svg") {
                $base64 = "data:image/svg+xml;base64,".base64_encode($data);
            } else {
                $base64 = "data:image/". $type .";base64,".base64_encode($data);
            }
            return $base64;
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Open File!');
        }
    }

}
