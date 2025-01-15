@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{__('document.edit_document')}} - <u>{{$document_status_name}}</u></h4>
                        </div>
                    </div>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

                    <div class="card-body">
                        <form action="{{ route('documents.update',$document->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch *</label>
                                        <select name="branch_id" id="branch_id" class="form-control" data-style="py-0" disabled>
                                            <option value="{{ $branch->branch_id }}" {{ $branch->branch_id == old('branch_id') ? 'selected' : '' }}>
                                                {{ $branch->branch_name}}
                                            </option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('document.document_type')}}  *</label>
                                        <label class="form-control image-file">{{$document->document_no}}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('document.document_type')}} * </label>
                                        <select name="document_type" class="selectpicker form-control" data-style="py-0" 
                                            @if($document->document_status == 1 && auth()->user()->can('edit-document-type')) @else disabled @endif>
                                            <option value=""> Select Document Type</option>
                                            <option value='1' {{$document->document_type == 1 ? 'selected' : ''}}>Return Document</option>
                                            <option value='2' {{$document->document_type == 2 ? 'selected' : ''}}>Exchange Document</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('document.document_date')}} * </label>
                                        <input name="document_date" type="date" class="form-control" id="document_date" value="{{date('Y-m-d',strtotime($document->document_date))}}"
                                        @if($document->document_status == 1 && auth()->user()->can('edit-document-date')) @else disabled @endif>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('document.supplier')}} </label>
                                        <select name="supplier_id" id="supplier_id" class="selectpicker form-control" data-style="py-0" 
                                            @if(($document->document_status == 1 && auth()->user()->can('edit-document-supplier') && auth()->user()->can('create-document')) || ($document->document_status == 2 && auth()->user()->can('edit-document-supplier'))) @else disabled @endif>
                                            <option value=""> </option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->vendor_id }}" {{ ($supplier->vendor_id == $document->supplier_id) ? 'selected' : '' }}>
                                                {{ $supplier->vendor_name ." | ". $supplier->vendor_code }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('document.remark_type')}} </label>
                                        <select name="document_remark" id="document_remark" class="selectpicker form-control" data-style="py-0" 
                                        @if($document->document_status == 1 && auth()->user()->can('edit-document-remark-type')) @else disabled @endif>
                                            <option value=""> </option>
                                            @foreach($document_remark_types as $document_remark_type)
                                            <option value="{{ $document_remark_type->id }}" {{ ($document_remark_type->id == $document->document_remark) ? 'selected' : '' }}>
                                                {{ $document_remark_type->document_remark}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('document.category')}}</label>
                                        <select name="category_id" id="category_id" class="selectpicker form-control" data-style="py-0" 
                                            @if($document->document_status == 1 && auth()->user()->can('edit-document-category')) @else disabled @endif>
                                            <option value="">Select Category </option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->product_category_id }}" {{ $category->product_category_id == $document->category_id ? 'selected' : '' }}>
                                                {{ $category->remark }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br> <br>
                                @can('view-document-operation-remark')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Operation {{__('document.remark')}}</label>
                                        <textarea name="operation_remark" class="form-control" rows="2" 
                                        @if($document->document_status == 1 && auth()->user()->can('edit-document-operation-remark')) @else disabled @endif>{{$document->operation_remark}}</textarea>
                                    </div>
                                </div>
                                @endcan
                                @can('view-document-delivery-date')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> {{__('document.delivery_date')}}* </label>
                                        <input name="delivery_date" type="date" class="form-control" id="delivery_date" value="{{$document->delivery_date ? date('Y-m-d',strtotime($document->delivery_date)) : date('Y-m-d')}}" 
                                        @if($document->document_status == 2 && auth()->user()->can('edit-document-delivery-date'))
                                        @else disabled @endif>
                                    </div>
                                </div>
                                @endcan
                                @can('view-document-merchandising-remark')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Merchandising {{__('document.remark')}}</label>
                                        <textarea name="merchandising_remark" class="form-control" rows="2" 
                                        @if($document->document_status == 2 && auth()->user()->can('edit-document-merchandising-remark'))
                                        @else disabled @endif>{{$document->merchandising_remark}}</textarea>
                                    </div>
                                </div>
                                @endcan
                                @if($document->document_type == 8 && auth()->user()->can('view-document-accounting-remark'))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Accounting {{__('document.remark')}}</label>
                                        <textarea name="accounting_remark" class="form-control" rows="2"
                                        @if(($document->document_status == 8 || $document->document_status == 10) && auth()->user()->can('edit-document-accounting-remark')) 
                                        @else disabled @endif>{{$document->accounting_remark}}</textarea>
                                    </div>
                                </div>
                                @endif
                               
                                @can('view-document-operation-attach-file')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Operation {{__('document.attach')}} &emsp;</label>
                                        @if($document->operation_attach_file)
                                            <a id="view_document_file" href="{{ route('document.view_document_attach_file', [$document->id,1]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-attachment-2 mr-0"></i></a>
                                            &emsp;{{$document->operation_attach_file}}
                                        @endif
                                        @if($document->document_status == 1 && auth()->user()->can('edit-document-operation-remark'))
                                            <input type="file" name="operation_attach_file" id="operation_attach_file" class="form-control image-file">
                                        @endif
                                    </div>
                                </div>
                                @endcan

                                @can('view-document-rgout-attach-file')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>RG Out {{__('document.attach')}}&emsp;</label>
                                        @if($document->operation_rg_out_attach_file)
                                            <a href="{{ route('document.view_document_attach_file', [$document->id,3]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-attachment-2 mr-0"></i></a>
                                            &emsp;{{$document->operation_rg_out_attach_file}}
                                        @endif
                                        @if($document->document_status == 6 && auth()->user()->can('edit-document-rgout-attach-file'))
                                            <input type="file" id="operation_rg_out_attach_file" class="form-control image-file" name="operation_rg_out_attach_file">
                                        @endif
                                    </div>
                                </div>
                                @endcan
                                @if(in_array($document->document_status,[8,9,10,11]) && auth()->user()->can('view-document-accounting-cn-attach-file'))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Accounting CN {{__('document.attach')}}&emsp;</label>
                                        @if($document->accounting_cn_attach_file)
                                            <a href="{{ route('document.view_document_attach_file', [$document->id,4]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-attachment-2 mr-0"></i></a>
                                            &emsp;{{$document->accounting_cn_attach_file}}
                                        @endif
                                        @if($document->document_status == 8 && auth()->user()->can('edit-document-accounting-cn-attach-file'))
                                            <input type="file" id="accounting_cn_attach_file" class="form-control image-file" name="accounting_cn_attach_file">
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($document->document_type == 2 && in_array($document->document_status,[9,10,11]) && auth()->user()->can('view-document-rgin-attach-file'))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>RG In {{__('document.attach')}}&emsp;</label>
                                        @if($document->operation_rg_in_attach_file)
                                            <a href="{{ route('document.view_document_attach_file', [$document->id,5]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-attachment-2 mr-0"></i></a>
                                            &emsp;{{$document->operation_rg_in_attach_file}}
                                        @endif
                                        @if($document->document_status == 9 && auth()->user()->can('edit-document-rgin-attach-file'))
                                            <input type="file" id="operation_rg_in_attach_file" class="form-control image-file" name="operation_rg_in_attach_file">
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($document->document_type == 2 && in_array($document->document_status,[10,11])  && auth()->user()->can('view-document-accounting-db-attach-file'))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Accounting DB {{__('document.attach')}}&emsp;</label>
                                        @if($document->accounting_db_attach_file)
                                            <a href="{{ route('document.view_document_attach_file', [$document->id,6]) }}" class="btn btn-success mr-2" target="_blank"><i class="ri-attachment-2 mr-0"></i></a>
                                            &emsp;{{$document->accounting_db_attach_file}}
                                        @endif
                                        @if($document->document_status == 10 && auth()->user()->can('edit-document-accounting-db-attach-file'))
                                            <input type="file" id="accounting_db_attach_file" class="form-control image-file" name="accounting_db_attach_file">
                                        @endif
                                    </div>
                                </div>
                                @endcan
                            </div>
                            @if($document->document_status == 1  && auth()->user()->can('create-document') && auth()->user()->can('update-document') 
                            || $document->document_status == 1  && auth()->user()->can('update-document-bm-complete') && auth()->user()->can('update-document') 
                            || $document->document_status == 2  && auth()->user()->can('update-document-ch-complete') && auth()->user()->can('update-document') 
                            || $document->document_status == 6  && auth()->user()->can('update-document-rgout-complete') && auth()->user()->can('update-document')
                             || $document->document_status == 8  && auth()->user()->can('update-document-cn-complete') && auth()->user()->can('update-document') 
                             || $document->document_status == 9  && auth()->user()->can('update-document-rgin-complete') && auth()->user()->can('update-document') 
                             || $document->document_status == 10  && auth()->user()->can('update-document-cn-complete') && auth()->user()->can('update-document'))
                                <button type="submit" class="btn btn-success mr-2 mb-2">{{__('button.update')}} </button>
                            @endif

                            @if($document->document_status == 1 && auth()->user()->can('add-product'))
                                <a href="#" class="btn btn-secondary mr-2 mb-2" id="add_product">{{__('button.add_product')}}</a>
                            @endif
                            @if($document->document_status == 9 && auth()->user()->can('update-document-supplier-cancel'))
                            <button type="button" class="btn btn-secondary mr-2 mb-2" onclick="return comfirmMessage('supplier_cancel')">{{__('button.supplier_cancel')}}</button>
                            @endif
                            @if($document->document_status == 8 && auth()->user()->can('export-document-cn'))
                                <a href="{{ route('document.excel_export', $document->id) }}" class="btn btn-secondary mr-2 mb-2">{{__('button.product_excel_export')}}</a>
                            @endif
                            @if($document->document_status == 10 && auth()->user()->can('export-document-cn'))
                                <a href="{{ route('document.excel_export', $document->id) }}" class="btn btn-secondary mr-2 mb-2">{{__('button.product_excel_export')}}</a>
                            @endif
                            @if($document->document_status == 1 && auth()->user()->can('update-document-bm-complete'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('bm-approve')">{{__('button.check_document')}}</button>
                            @endif

                            @if($document->document_status == 1 && auth()->user()->can('update-document-bm-reject'))
                            <button type="button" class="btn btn-danger mr-2 mb-2" onclick="return comfirmMessage('bm-reject')">{{__('button.reject_document')}}</button>
                            @endif
                            @if($document->document_status == 2 && auth()->user()->can('update-document-ch-complete'))
                                <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('ch-approve')">{{__('button.check_document')}}</button>
                            @endif
                            @if($document->document_status == 2 && auth()->user()->can('update-document-ch-reject'))
                            <button type="button" class="btn btn-danger mr-2 mb-2" onclick="return comfirmMessage('ch-reject')">{{__('button.reject_document')}}</button>
                            @endif
                            @if($document->document_status == 4 && auth()->user()->can('update-document-mm-complete'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('mm-approve')">{{__('button.confirm_document')}}</button>
                            @endif
                            @if($document->document_status == 4 && auth()->user()->can('update-document-mm-reject'))
                            <button type="button" class="btn btn-danger mr-2 mb-2" onclick="return comfirmMessage('mm-reject')">{{__('button.reject_document')}}</button>
                            @endif
                            @if($document->document_status == 6 && auth()->user()->can('update-document-rgout-complete'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('rg_out_complete')">{{__('button.rg_out_complete')}}</button>
                            @endif
                            @if($document->document_status == 8 && auth()->user()->can('update-document-cn-complete'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('cn_complete')">{{__('button.cn_complete')}}</button>
                            @endif
                            @if($document->document_status == 9 && $document->document_type == 2 && auth()->user()->can('update-document-rgin-complete'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('rg_in_complete')">{{__('button.rg_in_complete')}}</button>
                            @endif
                            @if($document->document_status == 10 && $document->document_type == 2 && auth()->user()->can('update-document-db-complete'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('db_complete')">{{__('button.db_complete')}}</button>
                            @endif
                            @if($document->document_status == 12 && auth()->user()->can('update-document-deducted'))
                            <button type="button" class="btn btn-primary mr-2 mb-2" onclick="return comfirmMessage('exchange_deducted')">{{__('button.exchange_deducted')}}</button>
                            @endif
                            @if($document->document_status == 6 && auth()->user()->can('export-dcoument-rg-out'))
                                <a href="{{ route('document.download_pdf', $document->id) }}" target="_blank" class="btn btn-success mr-2 mb-2">{{__('button.print_document')}}</a>
                            @endif
                            @if($document->document_status != 1 && auth()->user()->can('change_to_previous_status') &&
                                ($document->document_status == 2 && auth()->user()->can('update-document-bm-complete')) ||
                                
                                ($document->document_status == 8 && auth()->user()->can('update-document-bm-complete')) ||
                                ($document->document_status == 9 && auth()->user()->can('update-document-cn-complete')) ||
                                ($document->document_status == 10 && auth()->user()->can('update-document-bm-complete')) ||
                                ($document->document_status == 11 && auth()->user()->can('update-document-db-complete'))
                            )
                            <button type="button" class="btn btn-warning mr-2 mb-2" onclick="return comfirmMessage('change_to_previous_status')">{{__('button.back_to_previous_level')}}</button>
                            @endif
                            <a class="btn btn-light mb-2" href="{{ route('documents.index','type='.$document->document_type) }}"> {{__('button.back_to_listing')}}</a>
                        </form>
                    </div>

                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="mb-3">Added Product List</h4>
                                <p class="mb-0">Please Add Only 20 Products in One Document</p>
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
                                        <th>
                                            @if($document->document_type == 1)
                                            Return Qty
                                            @else
                                            Exchange Qty</th>
                                        @endif
                                        <th>Br.Mgr Qty</th>
                                        <th>Mer. Qty</th>
                                        <th>RG Out Qty</th>
                                        <th>RG In Qty</th>
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
                <input type="hidden" name="document_type" id="document_type" value="{{$document->document_type}}" />
                <input type="hidden" name="document_status" id="document_status" value="{{$document->document_status}}" /> 

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Product Code *</label>
                                <input type="text" name="product_code_no" id="product_code_no" class="form-control" placeholder="Enter Product Code" data-errors="Please Enter Product Code." required 
                                @if($document->document_status == 1 && auth()->user()->can('edit-product-product-code')) @else readonly @endcan>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>RG No *</label>
                                <input type="text" name="rg_out_doc_no" id="rg_out_doc_no" class="form-control" placeholder="Enter No" data-errors="Please Enter Code." 
                                @if(($document->document_status == 6 || $document->document_status == 8) && auth()->user()->can('edit-product-rg-no'))
                                @else readonly @endif>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Qty *</label>
                                <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="return_quantity" id="return_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty." 
                                @if(($document->document_status == 1) && auth()->user()->can('edit-product-qty'))
                                @else readonly @endif>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Br.Mgr Qty * </label>
                                <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="operation_actual_quantity" id="operation_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty."   
                                @if(($document->document_status == 1) && auth()->user()->can('edit-product-bm-qty'))
                                @else readonly @endif>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Mer. Qty *</label>
                                <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="merchandising_actual_quantity" id="merchandising_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty."
                                @if(($document->document_status == 2) && auth()->user()->can('edit-product-mer-qty'))
                                @else readonly @endif>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>RG Out. Qty *</label>
                                <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="operation_rg_out_actual_quantity" id="operation_rg_out_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty."
                                @if(($document->document_status == 6) && auth()->user()->can('edit-product-rgout-qty'))
                                @else readonly @endif>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>RG In. Qty *</label>
                                <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="operation_rg_in_actual_quantity" id="operation_rg_in_actual_quantity" class="form-control" placeholder="Enter Qty" data-errors="Please Enter Qty." 
                                @if(($document->document_status == 9) && auth()->user()->can('edit-product-rgin-qty'))
                                @else readonly @endif>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Product Attach</label>
                                <a id="view_product_file" href="#" class="btn btn-success mr-2" target="_blank"><i class="ri-attachment-2 mr-0"></i></a>
                                <p id="product_attach_file_name"></p>
                                @if(($document->document_status == 1) && auth()->user()->can('edit-product-attachfile'))
                                <input type="file" id="product_attach_file" class="form-control image-file" name="product_attach_file">
                                @endcan
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Remark</label>
                                <textarea id="operation_remark" name="operation_remark" class="form-control" rows="1"
                                @can('edit-product-remark') @else readonly @endcan></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="product_modal_submit_button">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js')
<script type="text/javascript">
    function checkProductQty($column_no) {
        var column_data = table.column($column_no).data();
        column_array = Object.values(column_data);
        found = false; 
        $.each(column_array, function(i, v){
            if(((v == '' || v == null) && found == false)) {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.product_qty_empty') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                found = true;
                return false;
            }
        })
        return found;
    }
    function checkDuplicateProduct(product_no) {
        var column_data = table.column(0).data();
        column_array = Object.values(column_data);
        found = false; 
        $.each(column_array, function(i, v){
            if((v == product_no && found == false)) {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.product_duplicate') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                found = true;
                event.preventDefault();
            }
        })

        return found;
    }
    function comfirmMessage($case) {
        var result;
        var document_id = $('#document_id').val();
      
        if ($case == 'bm-approve') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_check') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    result = false;
                    result = checkProductQty(3);
                    if(result == false){
                        window.location =  `/documents/bm_approve?document_id=${document_id}`;
                    }
                }
            });
        } else if ($case == 'bm-reject') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_reject') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/bm_reject?document_id=${document_id}`;
                }
            });
        } else if ($case == 'ch-approve') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_check') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    result = false;
                    result = checkProductQty(4);
                    if(result == false){
                        window.location =  `/documents/ch_approve?document_id=${document_id}`;
                    }
                }
            });
        } else if ($case == 'ch-reject') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_reject') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/ch_reject?document_id=${document_id}`;
                }
            });
        } else if ($case == 'mm-approve') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_confirm') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/mm_approve?document_id=${document_id}`;
                }
            });
        } else if ($case == 'mm-reject') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_reject') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/mm_reject?document_id=${document_id}`;
                }
            });
        } else if ($case == 'rg_out_complete') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_rg_out_complete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    result = false;
                    result = checkProductQty(5);
                    if(result == false){
                        window.location =  `/documents/rg_out_complete?document_id=${document_id}`;
                    }
                }
            });
        } else if ($case == 'cn_complete') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_cn_complete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/cn_complete?document_id=${document_id}`;
                }
            });
        } else if ($case == 'rg_in_complete') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_rg_in_complete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    result = false;
                    result = checkProductQty(6);
                    if(result == false){
                        window.location =  `/documents/rg_in_complete?document_id=${document_id}`;
                    }
                }
            });
        } else if ($case == 'db_complete') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_db_complete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/db_complete?document_id=${document_id}`;
                }
            });
        } else if ($case == 'supplier_cancel') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_supplier_cancel') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/supplier_cancel?document_id=${document_id}`;
                }
            });
        } else if ($case == 'exchange_deducted') {
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_exchange_deducted') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/exchange_deducted?document_id=${document_id}`;
                }
            });
            
        } else if ($case == 'change_to_previous_status') {
         
            Swal.fire({  
                icon: 'warning',
                title: "{{ __('message.warning') }}",  
                text: "{{ __('message.document_change_previous_level') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result)=> {
                if (result.isConfirmed){
                    window.location =  `/documents/change_to_previous_status?document_id=${document_id}`;
                }
            });
          
        }
    }
    $('#supplier_id').select2({
        width: '100%',
        placeholder: "Select an Supplier",
        allowClear: true
    });

    $(document).on("click", "#add_product", function() {
        let result = checkAddedProductCount();
        if(result){
            $('#product_code_no').val('');
            $('#product_name').val('');
            $('#product_unit').val('');
            $('#stock_quantity').val('');
            $('#rg_out_doc_no').val('');
            $('#return_quantity').val('');
            $('#product_attach_file_name').val('');
            $('#product_attach_file_name').html('');
            $('#product_attach_file').show();
            $('#operation_actual_quantity').val('');
            $('#merchandising_actual_quantity').val('');
            $('#operation_remark').val('');
            $('#view_product_file').hide();
            $('#product_modal_submit_button').text('Save');
            $('.add_product').modal('show');
        }
    });
    $('#return_quantity').on("keypress",function (evt) {
        if (this.value.length == 0 && evt.which == 48 && event.charCode >= 48 && event.charCode <= 57)
        {
            evt.preventDefault();
        }
    });
    $('#operation_actual_quantity').on("keypress",function (evt) {
        if (this.value.length == 0 && evt.which == 48 && event.charCode >= 48 && event.charCode <= 57)
        {
            evt.preventDefault();
        }
    });
    $('#merchandising_actual_quantity').on("keypress",function (evt) {
        if (this.value.length == 0 && evt.which == 48 && event.charCode >= 48 && event.charCode <= 57)
        {
            evt.preventDefault();
        }
    });
    $('#operation_rg_out_actual_quantity').on("keypress",function (evt) {
        if (this.value.length == 0 && evt.which == 48 && event.charCode >= 48 && event.charCode <= 57)
        {
            evt.preventDefault();
        }
    });
    $('#operation_rg_in_actual_quantity').on("keypress",function (evt) {
        if (this.value.length == 0 && evt.which == 48 && event.charCode >= 48 && event.charCode <= 57)
        {
            evt.preventDefault();
        }
    });
    $('#product_code_no').focusout(function() {
        var id = $(this).val();
        if (id) {
            var branch_code = $('#branch_code').val();
            
            var product_result = checkDuplicateProduct(branch_code);
            if(product_result == false){
                $.ajax({
                    url: '../../products/get_product_by_id/' + id + '/' + branch_code,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (branch_code == response.data.branch_code) {
                                $('#product_code_no').removeClass('is-invalid');
                                $('#product_name').val('');
                                $('#product_name').val(response.data.product_name);
                                $('#product_name').attr('readonly', true);
                                $('#product_unit').val(response.data.product_unit);
                                $('#product_unit').attr('readonly', true);
                                $('#stock_quantity').val(Number(response.data.stock_sum) * Number(response.data.unit_rate));
                                $('#stock_quantity').attr('readonly', true);
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: "{{ __('message.validation_error') }}",
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
    
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: "{{ __('message.product_not_found') }}",
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                        }
                    },
                    error: function() {
                        $('#product_code_no').addClass('is-invalid');
                        $('#product_code_noFeedback').removeClass("d-none");
                        $('#product_name').val("");
                        $('#product_unit').val("");
                        $('#stock_quantity').val("");
                        $('#operation_remark').val("");
                    }
                });
            }
        }
    });
    var document_type =  $('#document_type').val()== 1 ? false : true;
    var table = $('#product_list_by_document').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "autoWidth": true,
        "pageLength":20,
        'ajax': {
            'url': '/products/product_list_by_document',
            'type': 'GET',
            'data': function(d) {
                d.document_id = $('#document_id').val();
            }
        },
        columns: [{
                data: 'product_code_no',
                name: 'product_code_no',
                orderable: true
            },
            {
                data: 'product_name',
                name: 'product_name',
                orderable: false
            },
            {
                data: 'return_quantity',
                name: 'return_quantity',
                orderable: false
            },
            {
                data: 'operation_actual_quantity',
                name: 'operation_actual_quantity',
                orderable: false
            },
            {
                data: 'merchandising_actual_quantity',
                name: 'merchandising_actual_quantity',
                orderable: false
            },
            {
                data: 'operation_rg_out_actual_quantity',
                name: 'operation_rg_out_actual_quantity',
                orderable: false
            },
            {
                data: 'operation_rg_in_actual_quantity',
                name: 'operation_rg_in_actual_quantity',
                orderable: false,
                visible :document_type
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    return `  
                            <div class="d-flex align-items-center list-action">
                                <a class="badge bg-info mr-2" data-product_id="${row.id}" title="Detail" id="view"  href="#"><i class="ri-eye-line mr-0"></i></a>
                                @if(($document->document_status == 1 || $document->document_status == 2 || $document->document_status == 6 || $document->document_status == 9) && auth()->user()->can('edit-product'))
                                <a class="badge bg-success mr-2" data-product_id="${row.id}" title="Edit" id="edit"  href="#"><i class="ri-pencil-line mr-0"></i></a>
                                @endif
                                @can('delete-product')
                                <a class="badge bg-warning mr-2" data-product_id="${row.id}" title="Delete" id="delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                @endcan
                            </div>`;
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0,
        }],
    });

    table.on('click', '#view', function(e) {
        e.preventDefault();
        var product_id = $(this).data('product_id');
        $.ajax({
            url: '../../products/' + product_id,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
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
                $('#operation_rg_in_actual_quantity').val('');
                $('#operation_rg_in_actual_quantity').val(response.operation_rg_in_actual_quantity);
                $('#operation_remark').val('');
                if (response.product_attach_file) {
                    $('#product_attach_file_name').html(response.product_attach_file);
                    var product_id = response.id;
                    var link = "/products/attach_file/" + product_id;
                    var view_product_file = document.getElementById("view_product_file");
                    view_product_file.setAttribute("href", link);
                } else {
                    $('#product_attach_file_name').html('');
                    $('#view_product_file').hide();
                }
                $('#product_attach_file').hide();
                $('#product_id').val(response.id);
                $('#operation_remark').prop('readonly', true);
                $('#operation_remark').val(response.operation_remark);
                $('#product_modal_submit_button').hide();
                $('#product_modal_title').text('View Product');
                $('.add_product').modal('show');
            },
            error: function() {
                $('#product_code_no').addClass('is-invalid');
                $('#product_name').val("");
                $('#product_unit').val("");
                $('#stock_quantity').val("");
                $('#operation_remark').val("");
            }


        });
    })

    table.on('click', '#edit', function(e) {
        e.preventDefault();
        var product_id = $(this).data('product_id');
        var document_status = $('#document_status').val();
        $.ajax({
            url: '../../products/' + product_id,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                $('#product_code_no').val('');
                $('#product_code_no').val(response.product_code_no);
                $('#product_name').val('');
                $('#product_name').val(response.product_name);
                $('#product_unit').val('');
                $('#product_unit').val(response.product_unit);
                $('#stock_quantity').val('');
                $('#stock_quantity').val(response.stock_quantity);
                $('#return_quantity').val('');
                $('#return_quantity').val(response.return_quantity);
                $('#operation_actual_quantity').val('');
                $('#product_attach_file').show();
                if(response.operation_actual_quantity){
                    $('#operation_actual_quantity').val(response.operation_actual_quantity);
                }
                else{
                    if($('#operation_actual_quantity').prop('readonly') == false){
                        $('#operation_actual_quantity').val(response.return_quantity);
                    }
                };
                $('#merchandising_actual_quantity').val('');
                if(response.merchandising_actual_quantity){
                    $('#merchandising_actual_quantity').val(response.merchandising_actual_quantity);
                }
                else{
                    if($('#merchandising_actual_quantity').prop('readonly') == false){
                        $('#merchandising_actual_quantity').val(response.operation_actual_quantity);
                    }
                };
                $('#operation_rg_out_actual_quantity').val('');
                if(response.operation_rg_out_actual_quantity){
                    $('#operation_rg_out_actual_quantity').val(response.operation_rg_out_actual_quantity);
                }
                else{
                    if($('#operation_rg_out_actual_quantity').prop('readonly') == false){
                        $('#operation_rg_out_actual_quantity').val(response.merchandising_actual_quantity);
                    }
                };
                $('#operation_rg_in_actual_quantity').val('');
                if(response.operation_rg_in_actual_quantity){
                    $('#operation_rg_in_actual_quantity').val(response.operation_rg_in_actual_quantity);
                }
                else{
                    if($('#operation_rg_in_actual_quantity').prop('readonly') == false){
                        $('#operation_rg_in_actual_quantity').val(response.operation_rg_out_actual_quantity);
                    }
                };
                $('#rg_out_doc_no').val('');
                $('#rg_out_doc_no').val(response.rg_out_doc_no);
                $('#operation_remark').val('');
                $('#operation_remark').val(response.operation_remark);
                if (response.product_attach_file) {
                    $('#product_attach_file_name').html(response.product_attach_file);
                    var product_id = response.id;
                    var link = "/products/attach_file/" + product_id;
                    var view_product_file = document.getElementById("view_product_file");
                    view_product_file.setAttribute("href", link);
                    $('#view_product_file').show();
                } else {
                    $('#product_attach_file_name').html('');
                    $('#view_product_file').hide();
                }
                $('#operation_remark').prop('readonly', false);
                $('#product_id').val(response.id);

                $('#product_modal_submit_button').text('Update');
                $('#product_modal_submit_button').show();
                $('#product_modal_title').text('Edit Product');
                $('.add_product').modal('show');
            },
            error: function() {
                $('#product_code_no').addClass('is-invalid');
                $('#product_name').val("");
                $('#product_unit').val("");
                $('#stock_quantity').val("");
                $('#operation_remark').val("");
            }
        });
    })

    table.on('click', '#delete', function(e) {
        e.preventDefault();
        Swal.fire({  
            icon: 'warning',
            title: "{{ __('message.warning') }}",  
            text: "{{ __('message.product_delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result)=> {
            if (result.isConfirmed){
                var product_id = $(this).data('product_id');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../products/' + product_id,
                    type: 'DELETE',
                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
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
                    error: function() {
                        $('#product_code_no').addClass('is-invalid');
                        $('#product_name').val("");
                        $('#product_unit').val("");
                        $('#stock_quantity').val("");
                        $('#operation_remark').val("");
                    }
                });
            }
            else{
                return false;
            }
        });
    })

    $(document).on("click", "#product_modal_submit_button", function() {
        var status = validateProductForm();
        var product_code_no = $('#product_code_no').val();
        if (status != false) {
            $('#product_form').submit();
        }
    });

    function validateForm() {
        if ($('#document_type').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_type') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
    }

    function checkAddedProductCount() {
        var tableCount = table.rows().data().length;
        if (tableCount >= 20 ) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_check_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        return true;
    }

    function validateProductForm() {
        if ($('#product_code_no').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_product_code') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#product_name').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_product_name') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }

        if ($('#return_quantity').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_product_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#return_quantity').val()) < parseInt(1)) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_qty_and_zero') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#return_quantity').val()) > parseInt($('#stock_quantity').val())) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_qty_and_stock_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#operation_actual_quantity').val() == "" && $('#operation_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_bm_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#operation_actual_quantity').val()) < 1 && $('#operation_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_bm_qty_and_zero') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ((parseInt($('#operation_actual_quantity').val()) > parseInt($('#return_quantity').val())) && $('#operation_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_bm_qty_and_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (!$('#merchandising_actual_quantity').prop('readonly')) {
            if ($('#merchandising_actual_quantity').val() == "") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.need_mer_qty') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
        }
        if (parseInt($('#merchandising_actual_quantity').val()) < 1 && $('#merchandising_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_mer_qty_and_zero') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#merchandising_actual_quantity').val()) > parseInt($('#operation_actual_quantity').val()) && $('#merchandising_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_mer_qty_and_bm_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#merchandising_actual_quantity').val()) > parseInt($('#stock_quantity').val()) && $('#merchandising_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_mer_qty_and_stock_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#operation_rg_out_actual_quantity').val()) < 1 && $('#operation_rg_out_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_rg_out_qty_and_zero') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#operation_rg_out_actual_quantity').val()) > parseInt($('#merchandising_actual_quantity').val()) && $('#operation_rg_out_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_rg_out_qty_and_mer_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#operation_rg_out_actual_quantity').val()) > parseInt($('#stock_quantity').val()) && $('#operation_rg_out_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_rg_out_qty_and_stock_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }

        if (parseInt($('#operation_rg_in_actual_quantity').val()) < 1 && $('#operation_rg_in_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_rg_in_qty_and_zero') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#operation_rg_in_actual_quantity').val()) > parseInt($('#operation_rg_out_actual_quantity').val()) && $('#operation_rg_in_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_rg_in_qty_and_rg_out_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (parseInt($('#operation_rg_in_actual_quantity').val()) > parseInt($('#stock_quantity').val()) && $('#operation_rg_in_actual_quantity').prop('readonly') == false) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_rg_in_qty_and_stock_qty') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }

        if ($('#product_code_no').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_product_code') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
    }
</script>
@endsection