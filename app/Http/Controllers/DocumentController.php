<?php

namespace App\Http\Controllers;

use PDF as MPDF;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Document;
use App\Models\Supplier;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Models\DocumentRemark;
use App\Models\DocumentStatus;
use App\Exports\DocumentExport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DocumentNotification;
use Illuminate\Support\Facades\Notification;
class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-documents', ['only' => ['index']]);
        $this->middleware('permission:create-document', ['only' => ['create', 'store', 'generate_doc_no']]);
        $this->middleware('permission:edit-document', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-document', ['only' => ['destroy']]);
        
        $this->middleware('permission:update-document-bm-complete', ['only' => ['bm_approve']]);
        $this->middleware('permission:update-document-bm-reject', ['only' => ['bm_reject']]);
        $this->middleware('permission:change_to_previous_status', ['only' => ['change_to_previous_status']]);
        
        $this->middleware('permission:update-document-ch-complete', ['only' => ['ch_approve']]);
        $this->middleware('permission:update-document-ch-reject', ['only' => ['ch_reject']]);
        
        $this->middleware('permission:update-document-mm-complete', ['only' => ['mh_approve']]);
        $this->middleware('permission:update-document-mm-reject', ['only' => ['mh_reject']]);

        $this->middleware('permission:update-document-rgout-complete', ['only' => ['re_out']]);
        $this->middleware('permission:export-dcoument-rg-out', ['only' => ['download_pdf']]);
        $this->middleware('permission:update-document-rgin-complete', ['only' => ['re_in']]);
        
        $this->middleware('permission:update-document-cn-complete', ['only' => ['document_cn']]);
        $this->middleware('permission:update-document-db-complete', ['only' => ['document_db']]);
        $this->middleware('permission:export-document-cn|export-document-db', ['only' => ['csv_export']]);
    }

    protected function connection()
    {
        return new Document();
    }

    public function index(Request $request)
    {
        try{
            $branches = BranchUser::where('user_id',auth()->user()->id)->get();
            $document_status = DocumentStatus::get();
            if ($request->type == null) {
                $document_type_title = 'All';
            } else if ($request->type == 1) {
                $document_type_title = 'Return';
                $document_status = DocumentStatus::where('document_status', '!=', 10)->where('document_status', '!=', 11)->get();
            } else if ($request->type == 2) {
                $document_type_title = 'Exchange';
                $document_status = DocumentStatus::get();
            }
            $finished_document = DocumentStatus::where('document_status', '=', 9)->get();
            $document_type = isset($request->type) ? $request->type : '';
            $categories = Category::distinct('maincatcode')->get();
            return view('documents.index',compact('branches','document_status','document_type_title','document_type','categories'));
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function document_detail_listing(Request $request)
    {
        try {
            $branches = BranchUser::where('user_id',auth()->user()->id)->get();
            $document_status = DocumentStatus::get();
            if ($request->detail_type == null) {
                $document_type_title = 'All';
                $document_type ='';
            } else if ($request->detail_type == 1) {
                $document_type_title =  __('home.finish_return_document');
                $document_type ='1';
                $document_status = DocumentStatus::where('document_status', '!=', 10)->where('document_status', '!=', 11)->get();
            } else if ($request->detail_type == 2) {
                $document_type_title = __('home.pending_return_document');
                $document_status = DocumentStatus::get();
                $document_type ='1';
            } else if ($request->detail_type == 3) {
                $document_type_title =  __('home.finish_exchange_document');
                $document_status = DocumentStatus::get();
                $document_type ='2';
            }else if ($request->detail_type == 4) {
                $document_type_title =  __('home.pending_exchange_document');
                $document_status = DocumentStatus::get();
                $document_type ='2';
            }else if ($request->detail_type == 5) {
                $document_type_title =  __('home.overdue_exchange_document');;
                $document_status = DocumentStatus::get();
                $document_type ='2'; 
            }else if ($request->detail_type == 6) {
                
                $document_type_title = __('nav.my_document');
                $document_status = DocumentStatus::get();
                $document_type ='2';
            }
            $detail_type = isset($request->detail_type) ? $request->detail_type : '';
            $categories = Category::distinct('maincatcode')->get();
            return view('documents.document_detail_listing', compact('branches', 'document_status', 'categories', 'document_type_title', 'detail_type','document_type'));
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function create()
    {
        try{
            $suppliers = Supplier::select('vendor_id','vendor_code', 'vendor_name')->get();
            $document_remark_types = DocumentRemark::get();
            $categories = Category::distinct('maincatcode')->get();
            $branches = BranchUser::where('user_id',auth()->user()->id)->with('branches')->get();
            return view('documents.create',compact('suppliers','document_remark_types','categories','branches'));
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to load Create Form!');
        }
    }

    public function store(Request $request)
    {
        try {
            $filename = null;
            if ($request->operation_attach_file) {
                $request->validate([
                    'operation_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                ]);
                $filename = 'op_' . auth()->id() . '_' . time() . '_' . $_FILES['operation_attach_file']['name'];
                // Storage::disk('ftp')->put($filename, fopen($request->file('operation_attach_file'), 'r+'));
                $request->operation_attach_file->move(public_path('images/attachFile'), $filename);
            }

            request()->validate([
                'document_type' => 'required',
            ]);
            $request['document_no'] =  $this->generate_doc_no($request->document_type, $request->document_date,$request->branch_id);
            $request['operation_id']  = Auth::id();
            $now_hour = date('H');
            $now_minute =  date('i');
            $now_secound = date('s');
            $request['operation_updated_datetime'] = date('Y-m-d H:i:s', strtotime('+ ' . $now_hour . ' hour + ' . $now_minute . ' minutes + ' . $now_secound . ' seconds', strtotime($request['document_date'])));

            $request['branch_id'] = $request->branch_id;
            $request['operation_remark'] = $request->operation_remark;
            $request['document_remark'] = (int)$request->document_remark;
            $request['category_id'] = (int)$request->category_id;
            $document = Document::create($request->except(['operation_attach_file']) + ['operation_attach_file'=>$filename]);
            
            return redirect()->route('documents.edit', $document->id);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Store Department!');
        }
    }

    public function show(Document $document)
    {
        // return view('documents.show',compact('document'));
    }

    public function edit(Document $document)
    {
        try{
            $branch = Branch::where('branch_id',$document->branch_id)->first();
            $suppliers = Supplier::select('vendor_id','vendor_code', 'vendor_name')->get();
            $user_role = Auth::user()->roles->pluck('name')->first();
            $document_remark_types = DocumentRemark::get();
            $categories = Category::distinct('maincatcode')->get();
            $document_status_name = DocumentStatus::select('document_status_name')->where('document_status',$document->document_status)->first()->document_status_name; 

            return view('documents.edit',compact('document','document_status_name','branch','suppliers','user_role','document_remark_types','categories'));
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to load Edit Form!');
        }
    }

    public function update(Request $request, Document $document)
    {
        try {
            if($document->document_status == 1){
         
                if(Gate::allows('update-document-bm-complete')){
                    $request['branch_manager_id']  = Auth::id();
                }else{
                    request()->validate([
                        'document_type' => 'required',
                        'document_remark'=>'required',
                    ]);
                    $request['operation_id']  = Auth::id();
                    if($request['document_date'] != $document->document_date){
                        $return_document_doc_no = $this->generate_doc_no($request->document_type, $request->document_date,$request->branch_id);
                        $request['document_no'] =  $return_document_doc_no;
                    }
                    $request['document_type'] =  $request->document_type;
                }

                $request['operation_updated_datetime']  = date('Y-m-d H:i:s');
                $request['operation_remark'] = $request->operation_remark;

              
                $filename = "";
                if ($request->operation_attach_file) {
                    $request->validate([
                        'operation_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                    ]);
                    File::delete(public_path('images/attachFile/' . $document->operation_attach_file));
                    $filename = 'op_' . auth()->id() . '_' . time() . '_' . $_FILES['operation_attach_file']['name'];
                    $request->operation_attach_file->move(public_path('images/attachFile'), $filename);

                    $filename = $filename ?? $document->operation_attach_file;
                    $document->update($request->except(['operation_attach_file']) + ['operation_attach_file' => $filename]);
                } else {
                    $filename = $document->operation_attach_file;
                    $document->update($request->except(['operation_attach_file']) + ['operation_attach_file' => $filename]);
                }
            }
            if($document->document_status == 2 && Gate::allows('update-document-ch-complete')) {

                $request['category_head_id']  = Auth::id();
                $request['merchandising_remark'] = $request->merchandising_remark;
                $filename = "";
                if ($request->merchandising_attach_file) {
                    $request->validate([
                        'merchandising_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                    ]);
                    File::delete(public_path('images/attachFile/' . $document->merchandising_attach_file));
                    $filename = 'mer_' . auth()->id() . '_' . time() . '_' . $_FILES['merchandising_attach_file']['name'];
                    $request->merchandising_attach_file->move(public_path('images/attachFile'), $filename);

                    $filename = $filename ?? $document->merchandising_attach_file;
                    $document->update($request->except(['merchandising_attach_file']) + ['merchandising_attach_file' => $filename]);
                } else {
                    $filename = $document->merchandising_attach_file;
                    $document->update($request->except(['merchandising_attach_file']) + ['merchandising_attach_file' => $filename]);
                }
            }

            if($document->document_status == 6 && Gate::allows('update-document-rgout-complete')) {
                $request['operation_rg_out_id']  = Auth::id();

                $filename = "";
                if ($request->operation_rg_out_id) {
                    $request->validate([
                        'operation_rg_out_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                    ]);
                    File::delete(public_path('images/attachFile/' . $document->operation_rg_out_attach_file));
                    $filename = 'rgout_' . auth()->id() . '_' . time() . '_' . $_FILES['operation_rg_out_attach_file']['name'];
                    $request->operation_rg_out_attach_file->move(public_path('images/attachFile'), $filename);

                    $filename = $filename ?? $document->operation_rg_out_attach_file;
                    $document->update($request->except(['operation_rg_out_attach_file']) + ['operation_rg_out_attach_file' => $filename]);
                } else {
                    $filename = $document->merchandising_attach_file;
                    $document->update($request->except(['operation_rg_out_attach_file']) + ['operation_rg_out_attach_file' => $filename]);
                }
            }

            if(($document->document_status == 8 && Gate::allows('update-document-cn-complete')) 
            || ($document->document_status == 10 && Gate::allows('update-document-db-complete'))) {
               
                $filename = "";
                if ($document->document_status == 8) {
                    $request['accounting_cn_id']  = Auth::id();
                    $request['accounting_remark'] = $request->accounting_remark;
                    if ($request->accounting_cn_attach_file) {
                        $request->validate([
                            'accounting_cn_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                        ]);
                        File::delete(public_path('images/attachFile/' . $document->accounting_cn_attach_file));
                        $filename = 'acn_' . auth()->id() . '_' . time() . '_' . $_FILES['accounting_cn_attach_file']['name'];
                        $request->accounting_cn_attach_file->move(public_path('images/attachFile'), $filename);

                        $filename = $filename ?? $document->accounting_cn_attach_file;
                        $document->update($request->except(['accounting_cn_attach_file']) + ['accounting_cn_attach_file' => $filename]);
                    } else {
                        $filename = $document->accounting_cn_attach_file;
                        $document->update($request->except(['accounting_db_attach_file', 'accounting_cn_attach_file']) + ['accounting_db_attach_file' => $filename, 'accounting_cn_attach_file' => $document->accounting_cn_attach_file]);
                    }
                } else if ($document->document_status == 10) {
                    $request['accounting_db_id']  = Auth::id();
                    $request['accounting_remark'] = $request->accounting_remark;
                    if ($request->accounting_db_attach_file) {
                        $request->validate([
                            'accounting_db_attach_file' => 'required|max:4096',
                        ]);
                        File::delete(public_path('images/attachFile/' . $document->accounting_db_attach_file));
                        $filename = 'adb_' . auth()->id() . '_' . time() . '_' . $_FILES['accounting_db_attach_file']['name'];
                        $request->accounting_db_attach_file->move(public_path('images/attachFile'), $filename);

                        $filename = $filename ?? $document->accounting_db_attach_file;
                        $document->update($request->except(['accounting_db_attach_file']) + ['accounting_db_attach_file' => $filename]);
                    } else {
                        $filename = $document->accounting_db_attach_file;
                        $document->update($request->except(['accounting_db_attach_file', 'accounting_cn_attach_file']) + ['accounting_db_attach_file' => $filename, 'accounting_cn_attach_file' => $document->accounting_cn_attach_file]);
                    }
                }
            }

            if($document->document_status == 9 && Gate::allows('update-document-rgin-complete')) {
                $request['operation_rg_in_id']  = Auth::id();
                $filename = "";
                if ($request->operation_rg_in_attach_file) {
                    $request->validate([
                        'operation_rg_in_attach_file' => 'required|max:4096|mimes:jpeg,jpg,png,pdf',
                    ]);
                    File::delete(public_path('images/attachFile/' . $document->operation_rg_in_attach_file));
                    $filename = 'rgin_' . auth()->id() . '_' . time() . '_' . $_FILES['operation_rg_in_attach_file']['name'];
                    $request->operation_rg_in_attach_file->move(public_path('images/attachFile'), $filename);

                    $filename = $filename ?? $document->operation_rg_in_attach_file;
                    $document->update($request->except(['operation_rg_in_attach_file']) + ['operation_rg_in_attach_file' => $filename]);
                } else {
                    $filename = $filename ?? $document->operation_rg_in_attach_file;
                    $document->update($request->except(['operation_rg_in_attach_file']) + ['operation_rg_in_attach_file' => $filename]);
                }
            }
            return redirect()->route('documents.edit', $document->id);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to update Document!');
        }
    }

    public function destroy($document_id)
    {
        try {
            $document = Document::where('id', $document_id)->first();
            $document->delete();

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Document deleted successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to delete Document!');
        }
    }

    public function search_result(Request $request)
    {
        try{
            $document_no=  (!empty($_GET["document_no"])) ? ($_GET["document_no"]) : ('');
            $fromDate=  (!empty($_GET["document_from_date"])) ? ($_GET["document_from_date"]) : ('');
            $toDate=  (!empty($_GET["document_to_date"])) ? ($_GET["document_to_date"]) : ('');
            $document_type=  ($_GET["document_type"]) ? ($_GET["document_type"]) : ('');
            $document_branch=  (!empty($_GET["document_branch"])) ? ($_GET["document_branch"]) : ('');
            $document_status= (!empty($_GET["document_status"])) ? ($_GET["document_status"]) : ('');
            $category= (!empty($_GET["category"])) ? ($_GET["category"]) : ('');

            $result = $this->connection();
            if ($document_no != "") {
                $result = $result->where('documents.document_no', 'ilike', '%' . $document_no . '%');
            }
            if (!empty($fromDate)) :
                $dateStr = str_replace("/", "-", $fromDate);
                $fromDate = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('documents.created_at', '>=', $fromDate);
            endif;
            if (!empty($toDate)) :
                $dateStr = str_replace("/", "-", $toDate);
                $toDate = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('documents.created_at', '<=', $toDate);
            endif;
            if ($document_type != "") {
                $result = $result->where('documents.document_type', $document_type);
            }
            if ($document_status != "") {
                $result = $result->where('documents.document_status', $document_status);
            }

            if ($document_branch != "") {
                $result = $result->where('documents.branch_id', $document_branch);
            }

            if($category != ""){
                $result = $result->where('documents.category_id', $category);
            }
            
            $user_branches = BranchUser::where('user_id',auth()->user()->id)->pluck('branch_id')->toArray();
            $result = $result->whereIn('branch_id',$user_branches);

            $can_delete_document = Gate::allows('delete-document');
            return DataTables::of($result->with('Category')->get())
            ->editColumn('doc_status', function ($data) {
                return $data->DocumentStatus->document_status_name;
            })
            ->editColumn('category', function ($data) {
                if(isset($data->Category)){
                    $category = $data->Category->remark;
                    $sub_category = explode (" ", $category);
                    $category = $sub_category[0];
                    $checkCategory = strpos($category,'/');
                    if($checkCategory == true){
                        $sub_category = explode ("/", $category);
                        $category = $sub_category[0];
                    }
                    return $category;
                }
                return '';
            })
                ->editColumn('operation_updated_datetime', function ($data) {
                    return $data->operation_updated_datetime ? date('d/m/Y', strtotime($data->operation_updated_datetime)) : '';
                })
                ->editColumn('branch_manager_updated_datetime', function ($data) {
                    return $data->branch_manager_updated_datetime ? date('d/m/Y', strtotime($data->branch_manager_updated_datetime)) : '';
                })
                ->editColumn('category_head_updated_datetime', function ($data) {
                    return $data->category_head_updated_datetime ? date('d/m/Y', strtotime($data->category_head_updated_datetime)) : '';
                })
                ->editColumn('merchandising_manager_updated_datetime', function ($data) {
                    return $data->merchandising_manager_updated_datetime ? date('d/m/Y', strtotime($data->merchandising_manager_updated_datetime)) : '';
                })
                ->editColumn('operation_rg_out_updated_datetime', function ($data) {
                    return $data->operation_rg_out_updated_datetime ? date('d/m/Y', strtotime($data->operation_rg_out_updated_datetime)) : '';
                })
                ->editColumn('accounting_cn_updated_datetime', function ($data) {
                    return $data->accounting_cn_updated_datetime ? date('d/m/Y', strtotime($data->accounting_cn_updated_datetime)) : '';
                })
                ->editColumn('operation_rg_in_updated_datetime', function ($data) {
                    return $data->operation_rg_in_updated_datetime ? date('d/m/Y', strtotime($data->operation_rg_in_updated_datetime)) : '';
                })
                ->editColumn('accounting_db_updated_datetime', function ($data) {
                    return $data->accounting_db_updated_datetime ? date('d/m/Y', strtotime($data->accounting_db_updated_datetime)) : '';
                })
                ->addColumn('action', function ($data) use ($can_delete_document) {
                    return $can_delete_document;
                })
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Search Document!');
        }
    }
    public function document_detail_search_result(Request $request)
    {
        try {
            $document_no =  (!empty($_GET["document_no"])) ? ($_GET["document_no"]) : ('');
            $fromDate =  (!empty($_GET["document_from_date"])) ? ($_GET["document_from_date"]) : ('');
            $toDate =  (!empty($_GET["document_to_date"])) ? ($_GET["document_to_date"]) : ('');
            $detail_type =  ($_GET["detail_type"]) ? ($_GET["detail_type"]) : ('');
            $document_branch =  (!empty($_GET["document_branch"])) ? ($_GET["document_branch"]) : ('');
            $category =  (!empty($_GET["category"])) ? ($_GET["category"]) : ('');
            $result = $this->connection();
            if ($document_no != "") {
                $result = $result->where('documents.document_no', 'ilike', '%' . $document_no . '%');
            }
            if (!empty($fromDate)) :
                $dateStr = str_replace("/", "-", $fromDate);
                $fromDate = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('documents.created_at', '>=', $fromDate);
            endif;
            if (!empty($toDate)) :
                $dateStr = str_replace("/", "-", $toDate);
                $toDate = date('Y/m/d H:i:s', strtotime($dateStr));
                $result = $result->whereDate('documents.created_at', '<=', $toDate);
            endif;

            if ($document_branch != "") {
                $result = $result->where('documents.branch_id', $document_branch);
            }
            if($category != ""){
                $result = $result->where('documents.category_id', $category);
            }
            $userBranches = BranchUser::where('user_id',auth()->user()->id)->pluck('branch_id')->toArray();
            $result = $result->whereIn('branch_id', $userBranches);
            $role = Auth::user()->roles->pluck('name')->first();
            
            if ($detail_type == 1) {
                $result = $result->where('document_status', '=', 9)->where('document_type', 1);
            }
            if ($detail_type == 2) {
                $result = $result->whereIn('document_status', ['1', '2', '4', '6', '8'])->where('document_type', 1);
            }
            if ($detail_type == 3) {
                $result = $result->where('document_status', '=', 11)->where('document_type', 2);
            }
            if ($detail_type == 4) {
                $result = $result->whereIn('document_status', ['1', '2', '4', '6', '8','9','10'])->where('document_type', 2);
            }
            if ($detail_type == 5) {
                $inetrval = date('Y-m-d', strtotime(now() . ' - 14 days'));
                $result = $result->where('document_type', '=', '2')->where('deleted_at', null)->where('operation_rg_in_updated_datetime', null)->where('operation_rg_out_updated_datetime', '<', $inetrval);
            }
            if ($detail_type == 6) {
                if(Gate::allows('my-document-operation')){
                    $result = $result->where('document_status', 1)
                    ->where('operation_id',auth()->user()->id);
                }
                elseif(Gate::allows('my-document-bm')){
                    $result = $result->where('document_status', 1);
                    
                }
                elseif (Gate::allows('my-document-ch')) {
                    $result = $result->where('document_status', 2);
                }
                elseif (Gate::allows('my-document-mm')) {
                    $result = $result->where('document_status', 4);
                }
                elseif (Gate::allows('my-document-rgout')) {
                    $result = $result->where('document_status', 6);
                }
                elseif (Gate::allows('my-document-account-cn')) {
                    $result = $result->where('document_status', 8);
                }
                elseif (Gate::allows('my-document-rgin')) {
                    $result = $result->where('document_status', 9);
                }
                elseif (Gate::allows('my-document-account-db')) {
                    $result = $result->where('document_status', 10);
                }
            }
            return DataTables::of($result->get())
                ->editColumn('doc_status', function ($data) {
                    return $data->DocumentStatus->document_status_name;
                })
                ->editColumn('category', function ($data) {
                    if(isset($data->Category)){
                        $category = $data->Category->remark;
                        $sub_category = explode (" ", $category);
                        $category = $sub_category[0];
                        $checkCategory = strpos($category,'/');
                        if($checkCategory == true){
                            $sub_category = explode ("/", $category);
                            $category = $sub_category[0];
                        }
                        return $category;
                    }
                    return '';
                })
                ->editColumn('operation_updated_datetime', function ($data) {
                    return $data->operation_updated_datetime ? date('d/m/Y', strtotime($data->operation_updated_datetime)) : '';
                })
                ->editColumn('branch_manager_updated_datetime', function ($data) {
                    return $data->branch_manager_updated_datetime ? date('d/m/Y', strtotime($data->branch_manager_updated_datetime)) : '';
                })
                ->editColumn('category_head_updated_datetime', function ($data) {
                    return $data->category_head_updated_datetime ? date('d/m/Y', strtotime($data->category_head_updated_datetime)) : '';
                })
                ->editColumn('merchandising_manager_updated_datetime', function ($data) {
                    return $data->merchandising_manager_updated_datetime ? date('d/m/Y', strtotime($data->merchandising_manager_updated_datetime)) : '';
                })
                ->editColumn('operation_rg_out_updated_datetime', function ($data) {
                    return $data->operation_rg_out_updated_datetime ? date('d/m/Y', strtotime($data->operation_rg_out_updated_datetime)) : '';
                })
                ->editColumn('accounting_cn_updated_datetime', function ($data) {
                    return $data->accounting_cn_updated_datetime ? date('d/m/Y', strtotime($data->accounting_cn_updated_datetime)) : '';
                })
                ->editColumn('operation_rg_in_updated_datetime', function ($data) {
                    return $data->operation_rg_in_updated_datetime ? date('d/m/Y', strtotime($data->operation_rg_in_updated_datetime)) : '';
                })
                ->editColumn('accounting_db_updated_datetime', function ($data) {
                    return $data->accounting_db_updated_datetime ? date('d/m/Y', strtotime($data->accounting_db_updated_datetime)) : '';
                })
                ->addColumn('action', function ($data) use ($role) {
                    return $role;
                })
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("document_detail_listing"))
                ->with('error', 'Fail to Search Document!');
        }
    }

    public static function generate_doc_no($type, $date,$branch_id)
    {
        try {
            $type == '1' ? $prefix = 'RT' : $prefix = 'EXC';
            $branch_prefix = Branch::select('branch_short_name')->where('branch_id',$branch_id)->first()->branch_short_name;
            $dateStr =str_replace("/","-",$date);
            $date = date('Y/m/d H:i:s', strtotime($dateStr));

            $prefix = $prefix . $branch_prefix;
            $last_id = Document::select('id', 'document_no')->where('document_type', $type)
                ->whereDate('documents.document_date', '=', $date)
                ->latest()->get()->take(1);
            if (isset($last_id[0]) == False) {
                return $doc_no = $prefix . date('ymd-', strtotime($date)) . '0001';
            } else {
                $doc_no = $last_id[0]->document_no;
                $doc_no_arr = explode("-", $doc_no);
                $old_ymd = substr($doc_no_arr[0], -6);

                if ($old_ymd == date('ymd', strtotime($date))) {
                    $last_no = str_pad($doc_no_arr[1] + 1, 4, 0, STR_PAD_LEFT);
                } else {
                    $last_no = '0001';
                }
                return $doc_no = $prefix . date('ymd-', strtotime($date)) . $last_no;
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to generate Document No!');
        }
    }

    public function bm_approve(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['branch_manager_id']  = Auth::id();
            $request['branch_manager_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 2;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Branch Manager Approved Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Branch Manager Approve!');
        }
    }

    public function bm_reject(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['branch_manager_id']  = Auth::id();
            $request['branch_manager_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 3;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Branch Manager Rejected Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Branch Manager Reject!');
        }
    }

    public function bm_supplier_cancel(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['branch_manager_id']  = Auth::id();
            $request['supplier_cancel_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 12;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Supplier Cancelled Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Supplier Cancelled!');
        }
    }

    public function change_to_return(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $return_document_doc_no = $this->generate_doc_no(1, date('Y-m-d H:i:s'),$document->branch_id);
            $request['document_no'] =  $return_document_doc_no;
            $request['document_type'] =  1;
            $request['exchange_to_return'] = date('Y-m-d H:i:s');
            $request['exchange_to_return_bm'] =  Auth::id();
            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Changing to Return from Exchange Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail Changing to Return!');
        }
    }

    public function ch_approve(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }

            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['category_head_id']  = Auth::id();
            $request['category_head_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 4;
            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Category Head Approved Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Category Head Approve!');
        }
    }

    public function ch_reject(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['category_head_id']  = Auth::id();
            $request['category_head_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 5;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Category Head Rejected Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Category Head Reject!');
        }
    }

    public function mm_approve(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['merchandising_manager_id']  = Auth::id();
            $request['merchandising_manager_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 6;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Merchandising Manager Approved Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Merchandising Manager Approved!');
        }
    }

    public function mm_reject(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['merchandising_manager_id']  = Auth::id();
            $request['merchandising_manager_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 7;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Merchandising Manager Rejected Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Merchandising Manager Reject!');
        }
    }

    public function rg_out_complete(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['operation_rg_out_id']  = Auth::id();
            $request['operation_rg_out_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 8;
            $document->update($request->all());
            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'RG Out Completed Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to RG Out Complete!');
        }
    }

    public function cn_complete(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['accounting_cn_id']  = Auth::id();
            $request['accounting_cn_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 9;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'CN Completed Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to CN Complete!');
        }
    }

    public function rg_in_complete(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['operation_rg_in_id']  = Auth::id();
            $request['operation_rg_in_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 10;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'RG In completed Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to RG In Complete!');
        }
    }

    public function db_complete(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['accounting_db_id']  = Auth::id();
            $request['accounting_db_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 11;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'DB Complted Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to DB Complete!');
        }
    }

    public function change_to_previous_status(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $document_status = $document->document_status;
            $message = "Your Document ". $document->document_no ." is changed";
            if(Gate::allows('update-document-bm-complete') && $document_status == 2){
                $request['branch_manager_id']  = null;
                $request['branch_manager_updated_datetime']  = null;
                $request['document_status'] = 1;
                $user = User::where('id',$document->branch_manager_id)->first();
            }
            if(Gate::allows('update-document-ch-complete') && $document_status == 2){
                $request['branch_manager_id']  = null;
                $request['branch_manager_updated_datetime']  = null;
                $request['document_status'] = 1;
                $user = User::where('id',$document->branch_manager_id)->first();
            }
            if(Gate::allows('update-document-mm-complete') && $document_status == 4){
                $request['category_head_id']  = null;
                $request['category_head_updated_datetime']  = null;
                $request['document_status'] = 2;
                $user = User::where('id',$document->category_head_id)->first();
            }
            if(Gate::allows('update-document-bm-complete') && $document_status == 8){
                $request['operation_rg_out_id']  = null;
                $request['operation_rg_out_updated_datetime']  = null;
                $request['document_status'] = 6;
                $user = User::where('id',$document->operation_rg_out_id)->first();
            }
            if(Gate::allows('update-document-cn-complete') && $document_status == 9){
                $request['accounting_cn_id']  = null;
                $request['accounting_cn_updated_datetime']  = null;
                $request['document_status'] = 8;
                $user = User::where('id',$document->accounting_cn_id)->first();
            }
            if(Gate::allows('update-document-bm-complete') && $document_status == 10){
                $request['operation_rg_in_id']  = null;
                $request['operation_rg_in_updated_datetime']  = null;
                $request['document_status'] = 9;
                $user = User::where('id',$document->operation_rg_in_id)->first();
            }
           
            if(Gate::allows('update-document-db-complete') && $document_status == 11){
                $request['accounting_db_id']  = null;
                $request['accounting_db_updated_datetime']  = null;
                $request['document_status'] = 10;
                $user = User::where('id',$document->accounting_db_id)->first();
            }
            // $user = User::where('id',181)->first();
            Notification::send($user, new DocumentNotification($message,$document_id));
            $document->update($request->all()); 

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Change to Previous Level Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to DB Complete!');
        }
    }

    public function exchange_deducted(Request $request)
    {
        try {
            if ($request->document_id == null) {
                return redirect()->route('documents.index')
                    ->with('error', 'Error');
            }
            $document_id = $request->document_id;
            $document = $this->connection()->where('id', $document_id)->first();
            $request['exchange_deducted_id']  = Auth::id();
            $request['exchange_deducted_updated_datetime']  = date('Y-m-d H:i:s');
            $request['document_status'] = 13;

            $document->update($request->all());

            return redirect()->route('documents.index', 'type=' . $document->document_type)
                ->with('success', 'Exchange Deducted Successfully');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Make Exchange Deducted!');
        }
    }

    public function download_pdf($document_id = null)
    {
        try {
            $products = Product::where('document_id', $document_id)->get();
            $document = Document::where('id', $document_id)->with('rg_out', 'branch_manager','suppliers')->withTrashed()->first();

            $type = $document->document_type === 1 ? 'Return' : 'Exchange';
            $title = "Product " . $type  . " Form";
            $user_name = Auth::user()->name;
            $pdf = MPDF::loadView('documents.download_pdf', compact('title', 'document', 'products','user_name'));
            return $pdf->stream($document->document_no . '.pdf');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to show File!');
        }
    }

    public static function imagenABase64($ruta_relativa_al_public)
    {
        try {
            $path = $ruta_relativa_al_public;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = File::get($path);

            $base64 = "";
            if ($type == "svg") {
                $base64 = "data:image/svg+xml;base64," . base64_encode($data);
            } else {
                $base64 = "data:image/" . $type . ";base64," . base64_encode($data);
            }
            return $base64;
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Download PDF!');
        }
    }

    public function view_document_attach_file($document_id, $attach_type)
    {
        try {
            $document = Document::where('id', $document_id)->first();
            $user_name = Auth::user()->name;
            if ($attach_type == 1) {
                $attach_file_name = 'Operation Attach File';
                $attach_file_type = $document->operation_attach_file;
            } else if ($attach_type == 2) {
                $attach_file_name = 'Merchandising Attach File';
                $attach_file_type = $document->merchandising_attach_file;
            } else if ($attach_type == 3) {
                $attach_file_name = 'RG Out Attach File';
                $attach_file_type = $document->operation_rg_out_attach_file;
            } else if ($attach_type == 4) {
                $attach_file_name = 'Accounting CN Attach File';
                $attach_file_type = $document->accounting_cn_attach_file;
            } else if ($attach_type == 5) {
                $attach_file_name = 'RG IN Attach File';
                $attach_file_type = $document->operation_rg_in_attach_file;
            } else if ($attach_type == 6) {
                $attach_file_name = 'Accounting DB Attach File';
                $attach_file_type = $document->accounting_db_attach_file;
            }
            if (substr($attach_file_type, -3) == 'pdf' || substr($attach_file_type, -3) == 'PDF') {
                return response()->file(public_path('images/attachFile/' . $attach_file_type));
            }
            $pdf = MPDF::loadView('documents.view_document_attach_file', compact('user_name', 'attach_file_name','attach_file_type'));
            return $pdf->stream($document->document_no . '_' . $attach_file_name . ".pdf");
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to View Attach File!');
        }
    }

    public function excel_export($document_id)
    {
        try {
            return Excel::download(new ProductExport($document_id), 'Product-Export.xlsx');
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Excel Export!');
        }
    }

    public function document_export($fromDate=null, $toDate=null,$other=0)
    {
        $fromDate= ($fromDate==" ") ? null : $fromDate;
        $toDate= ($toDate == " ") ? null : $toDate;

        $other = explode('-',$other);

        $document_no=  $other[0];
        $document_type= $other[1];
        $document_branch= $other[2];
        $document_status=  $other[3];
        $category=  $other[4];

        try {
            return Excel::download(new DocumentExport($fromDate,$toDate,$document_no,$document_type,$document_branch,$document_status,$category), 'Document-Export.xlsx');
        } catch (\Exception $e) {
            return redirect()
                ->intended(route("documents.index"))
                ->with('error', 'Fail to Excel Export!');
        }
    }
}
