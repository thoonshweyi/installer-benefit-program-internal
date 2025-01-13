@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                                <h4 class="card-title">Edit Document</h4>
                            </div>
                        </div>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    

                        <div class="card-body">
                            <form action="{{ route('documents.update',$document->id) }}"  method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="document_status" id="document_status" value="{{$document->document_status}}">
                                <input type="hidden" name="user_role" id="user_role" value="{{$user_role}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Document No *</label>
                                            <label  class="form-control image-file">{{$document->document_no}}</label>
                                        </div> 
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Document Type *</label>
                                            <select name="document_type" class="selectpicker form-control" data-style="py-0"   
                                                @if($document->document_status == 1) @else disabled @endif 
                                                @role('OperationPerson|BranchManager')  @else disabled @endrole>
                                                <option value=""> Select Document Type</option>    
                                                <option value='1' {{$document->document_type == 1 ? 'selected' : ''}}>Return Document</option>
                                                <option value='2' {{$document->document_type == 2 ? 'selected' : ''}}>Exchange Document</option>
                                            </select>
                                        </div> 
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Document Date * </label>

                                            <input name="document_date" type="date" class="form-control" id="document_date" value="{{date('Y-m-d',strtotime($document->document_date))}}"  
                                                @if($document->document_status == 1) @else disabled @endif 
                                                @role('OperationPerson|BranchManager')  @else disabled @endrole>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Supplier </label>
                                            <select name="supplier_id" id="supplier_id" class="form-control"  
                                                @if($document->document_status == 1) @else disabled @endif 
                                                @role('OperationPerson|BranchManager')  @else disabled @endrole>
                                                <option value=""> </option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ ($supplier->id == $document->supplier_id) ? 'selected' : '' }}>
                                                        {{ $supplier->supplier_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operation Attach File</label>
                                            @if($document->document_status >= 2)
                                                @if($document->operation_attach_file)
                                                <a id="view_document_file" href="{{ route('document.view_document_attach_file', [$document->id,1]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                {{$document->operation_attach_file}}
                                                @endif
                                            @else 
                                                @role('OperationPerson|BranchManager')
                                                    <a href="{{ route('document.view_document_attach_file', [$document->id,1]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                    {{$document->operation_attach_file}}
                                                    <input type="file" name="operation_attach_file" id="operation_attach_file" class="form-control image-file" name="operation_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                                    @else
                                                    <a href="{{ route('document.view_document_attach_file', [$document->id,1]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                @endrole
                                            @endif
                                        </div>
                                    </div>
                                    @if($document->document_status >= 2 )
                                        @role('CategoryHead|MerchandisingManager')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Merchandising Attach File</label>
                                                    @if($document->document_status >= 3)
                                                        @if($document->merchandising_attach_file)
                                                            {{$document->merchandising_attach_file}}
                                                            <a href="{{ route('document.view_document_attach_file', [$document->id,2]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                        @endif
                                                    @else
                                                        @if($document->merchandising_attach_file)
                                                            <a href="{{ route('document.view_document_attach_file', [$document->id,2]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                            {{$document->merchandising_attach_file}}
                                                        @endif
                                                        @role('CategoryHead')
                                                        <input type="file" id="merchandising_attach_file" class="form-control image-file" name="merchandising_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                                        @endrole
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            @if($document->document_status > 2)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Merchandising Attach File</label>
                                                        @if($document->merchandising_attach_file)
                                                        <a href="{{ route('document.view_document_attach_file', [$document->id,2]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                        {{$document->merchandising_attach_file}}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endrole
                                    @endif

                                    @if($document->document_status >= 7 )
                                        @role('RGOut')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RG Out Attach</label>
                                                @if($document->document_status == 8)
                                                    @if($document->operation_rg_out_attach_file)
                                                    <a href="{{ route('document.view_document_attach_file', [$document->id,3]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                    @endif
                                                @else
                                                    <a  href="{{ route('document.view_document_attach_file', [$document->id,3]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                    {{$document->operation_rg_out_attach_file}}
                                                    <input type="file" id="operation_rg_out_attach_file" class="form-control image-file" name="operation_rg_out_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                                @endif
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>RG Out Attach</label>
                                                @if($document->operation_rg_out_attach_file)
                                                <a href="{{ route('document.view_document_attach_file', [$document->id,3]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                {{$document->operation_rg_out_attach_file}}
                                                @endif
                                            </div>
                                        </div>
                                        @endrole
                                    @endif

                                    @if($document->document_status >= 8)
                                        @role('Accounting')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Accounting CN Attach</label>
                                                    @if($document->document_status >= 9)
                                                        @if($document->accounting_cn_attach_file)
                                                        <a href="{{ route('document.view_document_attach_file', [$document->id,4]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('document.view_document_attach_file', [$document->id,4]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                        {{$document->accounting_cn_attach_file}}
                                                        <input type="file" id="accounting_cn_attach_file" class="form-control image-file" name="accounting_cn_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                                    @endif
                                                
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Accounting CN Attach</label>
                                                    @if($document->accounting_cn_attach_file)
                                                    <a href="{{ route('document.view_document_attach_file', [$document->id,4]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                    {{$document->accounting_cn_attach_file}}
                                                    @endif
                                                </div>
                                            </div>
                                        @endrole
                                    @endif

                                    @if($document->document_status >= 9)
                                        @role('RGIn')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> RG In Attach</label>
                                                    @if($document->document_status == 10)
                                                        @if($document->operation_rg_in_attach_file)
                                                            <a href="{{ route('document.view_document_attach_file', [$document->id,5]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                            {{$document->operation_rg_in_attach_file}}
                                                        @endif
                                                    @else
                                                    <a href="{{ route('document.view_document_attach_file', [$document->id,5]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                    {{$document->operation_rg_in_attach_file}}
                                                    <input type="file" id="operation_rg_in_attach_file" class="form-control image-file" name="operation_rg_in_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> RG In Attach</label>
                                                    @if($document->operation_rg_in_attach_file)
                                                    <a href="{{ route('document.view_document_attach_file', [$document->id,5]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                    {{$document->operation_rg_in_attach_file}}
                                                    @endif
                                                </div>
                                            </div>
                                        @endrole
                                    @endif

                                    @if($document->document_status >= 10)
                                        @role('Accounting')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Accounting DB Attach</label>
                                                    @if($document->document_status == 11)
                                                        @if($document->accounting_db_attach_file)
                                                            <a href="{{ route('document.view_document_attach_file', [$document->id,6]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                            {{$document->accounting_db_attach_file}}
                                                        @endif
                                                    @else
                                                        @if($document->accounting_db_attach_file)
                                                            <a href="{{ route('document.view_document_attach_file', [$document->id,6]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                            {{$document->accounting_db_attach_file}}
                                                        @endif
                                                        <input type="file" id="accounting_db_attach_file" class="form-control image-file" name="accounting_db_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Accounting DB Attach</label>
                                                    @if($document->accounting_db_attach_file)
                                                        <a href="{{ route('document.view_document_attach_file', [$document->id,6]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                                        {{$document->accounting_db_attach_file}}
                                                    @endif
                                                </div>
                                            </div>
                                        @endrole
                                    @endif
                                    <br> <br>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operation Remark</label>
                                            <textarea name="operation_remark" class="form-control" rows="2" 
                                            @role('OperationPerson|BranchManager') 

                                            @else readonly 
                                            @endrole 
                                            @if($document->document_status > 1)
                                            readonly
                                            @endif>{{$document->operation_remark}} </textarea>
                                        </div>
                                    </div> 

                                    @if($document->document_status >= 2) 
                                        @role('CategoryHead|MerchandisingManager')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Merchandising Remark</label>
                                                    @if($document->document_status >= 4)
                                                        <textarea name="merchandising_remark" class="form-control" rows="2" 
                                                        readonly >{{$document->merchandising_remark}}</textarea>
                                                    @else
                                                        <textarea name="merchandising_remark" class="form-control" rows="2" 
                                                            @role('CategoryHead|MerchandisingManager') 
                                                            @else readonly 
                                                            @endrole>{{$document->merchandising_remark}}</textarea>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Merchandising Remark</label>
                                                    <textarea name="merchandising_remark" class="form-control" rows="2" 
                                                    readonly>{{$document->merchandising_remark}}</textarea>
                                                </div>
                                            </div>
                                        @endrole
                                    @endif

                                    @if($document->document_status >= 8 )
                                        @role('Accounting') 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Accounting Remark</label>
                                                <textarea name="accounting_remark" class="form-control" rows="2"
                                                @role('Accounting') 
                                                @else readonly
                                                @endrole>{{$document->accounting_remark}}</textarea>
                                            </div>
                                        </div>
                                        @endrole
                                    @endif
                                </div>

                                @can('document-edit')
                                    @if($document->document_status == 1 )
                                        @role('OperationPerson|BranchManager')
                                        <button type="submit" class="btn btn-success mr-2">Update Document </button>
                                        @endrole
                                    @elseif($document->document_status == 2 )
                                        @role('CategoryHead')
                                        <button type="submit" class="btn btn-success mr-2">Update Document </button>
                                        @endrole
                                    @elseif($document->document_status == 8 || $document->document_status == 10)
                                        @role('Accounting')
                                        <button type="submit" class="btn btn-success mr-2">Update Document </button>
                                        @endrole
                                    @elseif($document->document_status == 9 )
                                        @role('RGIn')
                                        <button type="submit" class="btn btn-success mr-2">Update Document </button>
                                        @endrole
                                    @endif


                                    @if($document->document_status == 1)
                                        @role('OperationPerson|BranchManager')
                                        <a href="#" class="btn btn-secondary mr-2" id="add_product">Add Product</a>
                                        @endrole
                                    @endif
                                @endcan
                                @can('document-csv-export')
                                    @role('Accounting')
                                        @if($document->document_status == 9 || $document->document_status == 11 )
                                            <a href="{{ route('document.excel_export', $document->id) }}" class="btn btn-secondary mr-2">Product Excel Export</a>
                                        @endif
                                    @endrole
                                @endcan
                                @can('document-bm-approve')
                                    @if($document->document_status == 1 )
                                        <a href="{{ route('document.bm_approve', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">Approve Document</a>
                                    @endif
                                @endcan
                                @can('document-bm-reject')
                                    @if($document->document_status == 1)
                                        <a href="{{ route('document.bm_reject', 'document_id='.$document->id)}}" class="btn btn-danger mr-2">Reject Document</a>
                                    @endif
                                @endcan
                                @can('document-ch-approve')
                                    @if($document->document_status == 2 )
                                        <a href="{{ route('document.ch_approve', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">Approve Document</a>
                                    @endif
                                @endcan
                                @can('document-ch-reject')
                                    @if($document->document_status == 2 )
                                        <a href="{{ route('document.ch_reject', 'document_id='.$document->id)}}" class="btn btn-danger mr-2">Reject Document</a>
                                    @endif
                                @endcan
                                @can('document-mm-approve')
                                    @if($document->document_status == 4)
                                        <a href="{{ route('document.mm_approve', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">Approve Document</a>
                                    @endif
                                @endcan
                                @can('document-mh-reject')
                                    @if($document->document_status == 4)
                                        <a href="{{ route('document.mm_reject', 'document_id='.$document->id) }}" class="btn btn-danger mr-2">Approve Document</a>
                                    @endif
                                @endcan
                                @can('document-rg-out')
                                    @if($document->document_status == 6 ||  $document->document_status == 7 )
                                        <a href="{{ route('document.rg_out_complete', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">RG Out Complete</a>
                                    @endif
                                @endcan
                                @can('document-cn')
                                    @if($document->document_status == 8 )
                                        <a href="{{ route('document.cn_complete', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">CN Complete</a>
                                    @endif
                                @endcan
                                @can('document-rg-in')
                                    @if($document->document_status == 9)
                                        <a href="{{ route('document.rg_in_complete', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">RG In Complete</a>
                                    @endif
                                @endcan
                                @can('document-db')
                                    @if($document->document_status == 10)
                                        <a href="{{ route('document.db_complete', 'document_id='.$document->id) }}" class="btn btn-primary mr-2">DB Complete</a>
                                    @endif
                                @endcan
                                @can('document-print')
                                    @if($document->document_status == 8 )
                                        <a href="{{ route('document.download_pdf', $document->id) }}"  target="_blank" class="btn btn-success mr-2">Print Document</a>
                                    @endif
                                @endcan
                                <a class="btn btn-light" href="{{ route('documents.index','type='.$document->document_type) }}"> Back to Listing</a>
                            </form>
                        </div>
                        
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                                <div>
                                    <h4 class="mb-3">Added Product List</h4>
                                    <p class="mb-0">Please Add Only 10 Product in One Document</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="product_list_by_document">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Return Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Page end  -->
        </div>
    </div>
    <div class="modal fade add_product" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="product_modal_title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="product_form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" name="document_id" id="document_id" value="{{$document->id}}" />
                    <input type="hidden" name="branch_code" id="branch_code" value="{{$branch->branch_code}}" />
                    <input type="hidden" name="product_id" id="product_id" value="" />

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    <label>Product Code *</label>
                                    <input type="text" name="product_code_no" id="product_code_no" class="form-control" placeholder="Enter Product Code" data-errors="Please Enter Product Code." required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Name *</label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter Product Name" data-errors="Please Enter Code." readonly>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit *</label>
                                    <input type="text" name="product_unit" id="product_unit" class="form-control" placeholder="Enter Product Unit" data-errors="Please Enter Code." readonly>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Stock Qty *</label>
                                    <input type="text" name="stock_quantity" id="stock_quantity" class="form-control" placeholder="Enter Stock Qty" data-errors="Please Enter Stock Qty." readonly>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>RG No *</label>
                                    <input type="text" name="rg_out_doc_no" id="rg_out_doc_no" class="form-control" placeholder="Enter No" data-errors="Please Enter Code."  @can('document-rg-out')  @else readonly @endcan  @if($document->document_status == 6) @else readonly @endif >
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Qty *</label>
                                    <input type="text" name="return_quantity" id="return_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty." @role('OperationPerson|BranchManager') @else readonly @endrole>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Acutal Qty *</label>
                                    <input type="text" name="operation_actual_quantity" id="operation_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty." @role('OperationPerson|BranchManager') @else readonly @endrole>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mar. Acutal Qty *</label>
                                    <input type="text" name="merchandising_actual_quantity" id="merchandising_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty." @role('CategoryHead')  @else readonly @endrole >
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>RG. Acutal Qty *</label>
                                    <input type="text" name="operation_rg_out_actual_quantity" id="operation_rg_out_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty." @can('document-rg-out')  @else readonly @endcan  @if($document->document_status != 4) readonly @endif >
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Attach</label>
                                    @if($document->document_status > 2)
                                    <a  id="view_product_file" href="#" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a>
                                    <p id="product_attach_file_name"></p>
                                    @else
                                    <a  id="view_product_file" href="#" class="btn btn-success mr-2" target="_blank"><i class="ri-eye-line mr-0"></i></a> 
                                    <p id="product_attach_file_name"></p>
                                    <input type="file" id="product_attach_file" class="form-control image-file" name="product_attach_file" accept=".jpg,.jpeg,.png,.pdf">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Remark</label>
                                    <textarea id="operation_remark" name="operation_remark" class="form-control" rows="1"></textarea>
                                </div>
                            </div>     
                        </div>                            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="product_modal_submit_button">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
@endsection
@section('js')
<script type="text/javascript">

        function validateProductForm() {
            if ($('#product_code_no').val() == "") {
                alert("Product Code must be filled out");
                return false;
            }
            if ($('#product_name').val() == "") {
                alert("Product is not Available");
                return false;
            }

            if ($('#return_quantity').val() == "") {
                alert("Qty must be filled out");
                return false;
            }
            if (parseInt($('#return_quantity').val()) < 1){
                alert("Qty must be greater than 0");
                return false;
            }
            if (parseInt($('#return_quantity').val()) > parseInt($('#stock_quantity').val())){
                alert("Qty must be less than Stock Quantity");
                return false;
            }
            if ($('#operation_actual_quantity').val() == "") {
                alert("Operation Actual Qty must be filled out");
                return false;
            }
            if (parseInt($('#operation_actual_quantity').val()) < 1){
                alert("Operation Actual Qty must be greater than 0");
                return false;
            }
            if (parseInt($('#operation_actual_quantity').val()) > parseInt($('#return_quantity').val())){
                alert("Operation Actual Qty must be less than Quantity");
                return false;
            }
            if ( !$('#merchandising_actual_quantity').prop('readonly')) {
                if($('#merchandising_actual_quantity').val() == ""){
                    alert("Merchandising Actual Qty must be filled out");
                    return false;
                }
            }
            if (parseInt($('#merchandising_actual_quantity').val()) < 1){
                alert("Merchandising Actual Qty must be greater than 0");
                return false;
            }
            if (parseInt($('#merchandising_actual_quantity').val()) > parseInt($('#operation_actual_quantity').val())){
                alert("Merchandising Actual Qty must be less than Quantity");
                return false;
            }
            if (parseInt($('#merchandising_actual_quantity').val()) > parseInt($('#stock_quantity').val())){
                alert("Merchandising Actual Qty must be less than Stock Quantity");
                return false;
            }

            if ($('#product_code_no').val() == "") {
                alert("Product Code must be filled out");
                return false;
            }
        }

    $(document).ready(function() {
        $('#supplier_id').select2({
            width: '100%',
            placeholder: "Select an Supplier",
            allowClear: true
        });
    
        $(document).on("click", "#add_product", function () {
            $('#product_code_no').val('');
            $('#product_code_no').prop('disabled', false);
            $('#product_name').val('');
            $('#product_unit').val('');
            $('#stock_quantity').val('');
            $('#rg_out_doc_no').val('');
            $('#return_quantity').val('');
            $('#product_attach_file_name').val('');
            $('#return_quantity').prop('disabled', false);
            $('#operation_actual_quantity').val('');
            $('#operation_actual_quantity').prop('disabled', false);
            $('#operation_remark').val('');
            $('#operation_remark').prop('disabled', false);
            $('#view_product_file').hide();
            $('.add_product').modal('show');
        });

        $('#product_code_no').focusout(function () {
            var id = $(this).val();
            if(id != null){
                var branch_code = $('#branch_code').val();
                $.ajax({
                    url: '../../products/get_product_by_id/' + id + '/' + branch_code,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function () {
                        jQuery("#loading").hide();
                    },
                    success: function (response) {
                        if (response.data != null) {
                            if (branch_code == response.data.branch_code) {
                                $('#product_code_no').removeClass('is-invalid');
                                $('#product_name').val('');
                                $('#product_name').val(response.data.product_name);
                                $('#product_name').attr('readonly', true);
                                $('#product_unit').val(response.data.product_unit);
                                $('#product_unit').attr('readonly', true);
                                $('#stock_quantity').val(Number(response.data.stock_qty));
                                $('#stock_quantity').attr('readonly', true);
                            } else {
                                alert('Validation Error');
                            }

                        } else {
                            alert('Product Not Found in DB');
                        }
                    },
                    error: function () {
                        $('#product_code_no').addClass('is-invalid');
                        $('#product_code_noFeedback').removeClass("d-none");
                        $('#product_name').val("");
                        $('#product_unit').val("");
                        $('#stock_quantity').val("");
                        $('#operation_remark').val("");
                    }


                });
            }
        });

        var table = $('#product_list_by_document').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange":false,
            "autoWidth": true,
            "responsive": true,
            // "order": [[ 5, 'des' ]],
            'ajax': {
                'url': '/products/product_list_by_document',
                    'type': 'GET',
                    'data': function (d) {
                        d.document_id = $('#document_id').val();
                    }
                },
                columns: [
                    {data: 'product_code_no', name: 'product_code_no',orderable: false},
                    {data: 'product_name', name: 'product_name',orderable: false},
                    {data: 'operation_actual_quantity',name: 'operation_actual_quantity',orderable: false},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        render: function(data, type, row) {
                            if(row.action[0] == 1){
                                return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-info mr-2" data-product_id="${row.id}" id="view"  href="#"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-success mr-2" data-product_id="${row.id}" id="edit"  href="#"><i class="ri-pencil-line mr-0"></i></a>
                                    <a class="badge bg-warning mr-2" data-product_id="${row.id}" id="delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>`
                            }
                            if(row.action[0]  == 2){
                                return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-info mr-2" data-product_id="${row.id}" id="view"  href="#"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-success mr-2" data-product_id="${row.id}" id="edit"  href="#"><i class="ri-pencil-line mr-0"></i></a>
                                </div>`
                            }
                            if(row.action[0]  == 6){
                                return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-info mr-2" data-product_id="${row.id}" id="view"  href="#"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-success mr-2" data-product_id="${row.id}" id="edit"  href="#"><i class="ri-pencil-line mr-0"></i></a>
                                </div>`
                            }
                            if(row.action[0]  >= 3){
                                return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-info mr-2" data-product_id="${row.id}" id="view"  href="#"><i class="ri-eye-line mr-0"></i></a>
                                </div>`
                            }
                        }
                    }
                ],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false, "targets": 0,
                }],
            });

        table.on('click','#view',function(e){
            e.preventDefault();
            var product_id = $(this).data('product_id');
            $.ajax({
                url: '../../products/' + product_id,
            type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function () {
                    jQuery("#loading").hide();
                },
                success: function (response) {
                    $('#product_code_no').val('');
                    $('#product_code_no').val(response.product_code_no);
                    $('#product_code_no').prop('disabled', true);
                    $('#product_name').val('');
                    $('#product_name').val(response.product_name);
                    $('#product_unit').val('');
                    $('#product_unit').val(response.product_unit);
                    $('#stock_quantity').val('');
                    $('#stock_quantity').val(response.stock_quantity);
                    $('#rg_out_doc_no').val('');
                    $('#rg_out_doc_no').val(response.rg_out_doc_no);
                    $('#rg_out_doc_no').prop('disabled', true);
                    $('#return_quantity').val('');
                    $('#return_quantity').val(response.return_quantity);
                    $('#operation_actual_quantity').val('');
                    $('#operation_actual_quantity').val(response.operation_actual_quantity);
                    $('#merchandising_actual_quantity').val('');
                    $('#merchandising_actual_quantity').val(response.merchandising_actual_quantity);
                    $('#operation_rg_out_actual_quantity').val('');
                    $('#operation_rg_out_actual_quantity').val(response.operation_rg_out_actual_quantity);
                    $('#operation_remark').val('');
                    $('#product_attach_file_name').html(response.product_attach_file);
                    $('#product_attach_file').hide();
                    $('#product_id').val(response.id);
                    $('#operation_remark').prop('disabled', true);
                    var product_id = response.id;
                    var link = "/products/attach_file/"+ product_id;
                    var view_product_file = document.getElementById("view_product_file"); 
                    view_product_file.setAttribute("href", link);

                   
                    $('#operation_remark').val(response.operation_remark);
                    $('#product_modal_submit_button').hide();
                    $('#product_modal_title').text('View Product');
                    $('.add_product').modal('show');
                },
                error: function () {
                    $('#product_code_no').addClass('is-invalid');
                    $('#product_name').val("");
                    $('#product_unit').val("");
                    $('#stock_quantity').val("");
                    $('#operation_remark').val("");
                }


            });
        })

        table.on('click','#edit',function(e){
            e.preventDefault();
            var product_id = $(this).data('product_id');
            var user_role =  $('#user_role').val();
            $.ajax({
                url: '../../products/' + product_id,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function () {
                    jQuery("#loading").hide();
                },
                success: function (response) {
                    $('#product_code_no').val('');
                    $('#product_code_no').val(response.product_code_no);
                    $('#product_name').val('');
                    $('#product_name').val(response.product_name);
                    $('#product_unit').val('');
                    $('#product_unit').val(response.product_unit);
                    $('#stock_quantity').val('');
                    $('#stock_quantity').val(response.stock_quantity);
                    $('#rg_out_doc_no').val('');
                    $('#rg_out_doc_no').val(response.rg_out_doc_no);
                    $('#return_quantity').val('');
                    $('#return_quantity').val(response.return_quantity);
                    $('#operation_actual_quantity').val('');
                    $('#operation_actual_quantity').val(response.operation_actual_quantity);
                    $('#merchandising_actual_quantity').val('');
                    $('#merchandising_actual_quantity').val(response.merchandising_actual_quantity);
                    $('#operation_rg_out_actual_quantity').val('');
                    $('#operation_rg_out_actual_quantity').val(response.operation_rg_out_actual_quantity);
                    $('#operation_remark').val('');
                    $('#operation_remark').val(response.operation_remark);
                    $('#product_attach_file_name').html(response.product_attach_file);
                    $('#operation_remark').prop('disabled', false);
                    $('#rg_out_doc_no').prop('disabled', false);
                    $('#product_id').val(response.id)
                    var product_id = response.id;
                    var link = "/products/attach_file/"+ product_id;
                    var view_product_file = document.getElementById("view_product_file"); 
                    view_product_file.setAttribute("href", link);
                 
                    $('#product_modal_submit_button').text('Update');
                    $('#product_modal_submit_button').show();
                    $('#product_modal_title').text('Edit Product');
                    $('.add_product').modal('show');
                },
                error: function () {
                    $('#product_code_no').addClass('is-invalid');
                    $('#product_name').val("");
                    $('#product_unit').val("");
                    $('#stock_quantity').val("");
                    $('#operation_remark').val("");
                }
            });
            if(user_role != 'OperationPerson' || user_role != 'BranchManager'){
                $('#product_attach_file').hide();
            }else{
                $('#product_attach_file').show();
            }
        })

        table.on('click','#delete',function(e){
            e.preventDefault();
            var result =confirm("Do You want to Delete!")
            if (result == true){
                var product_id = $(this).data('product_id');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../products/' + product_id,
                    type: 'DELETE',
                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function () {
                        jQuery("#loading").hide();
                    },
                    success: function (response) {
                        $('#product_code_no').val('');
                        $('#product_name').val('');
                        $('#product_unit').val('');
                        $('#stock_quantity').val('');
                        $('#rg_out_doc_no').val('');
                        $('#return_quantity').val('');
                        $('#operation_actual_quantity').val('');
                        $('#operation_remark').val('');
                        $('#product_list_by_document').DataTable().draw(true);
                    },
                    error: function () {
                        $('#product_code_no').addClass('is-invalid');
                        $('#product_name').val("");
                        $('#product_unit').val("");
                        $('#stock_quantity').val("");
                        $('#operation_remark').val("");
                    }
                });
            }
        })
        $(document).on("click", "#product_modal_submit_button", function () {
            var status = validateProductForm();
            if(status != false){
                $('#product_form').submit();
            }
        });
        
        function validateForm() {
            if ($('#document_type').val() == "") {
                alert("Docuemnt Type must be filled out");
                return false;
            }
        }

     

       
    });
</script>
@endsection