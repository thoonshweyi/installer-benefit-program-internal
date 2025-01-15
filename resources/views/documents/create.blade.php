@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('document.create_document') }}</h4>
                            </div>
                        </div>
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
                            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
                            @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('document.branch') }} *</label>
                                            <select name="branch_id" id="branch_id" class="form-control" data-style="py-0">
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->branches->branch_id }}" {{ $branch->branches->branch_id == old('branch_id') ? 'selected' : '' }}>
                                                        {{ $branch->branches->branch_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('document.document_type') }} *</label>
                                            <select name="document_type" id="document_type" class="selectpicker form-control" data-style="py-0">
                                                <option value=""> Select Document Type</option>    
                                                <option value='1'{{ old('document_type') == 1 ? 'selected' :''}}>Return Document</option>
                                                <option value='2'{{ old('document_type') == 2 ? 'selected' :''}}>Exchange Document</option>
                                            </select>
                                        </div> 
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('document.document_date') }} * </label>
                                            <input name="document_date" type="date" class="form-control" id="documentDate" value="{{ old('document_date') ?? date('Y-m-d')}}"
                                            @can('edit-document-date') @else readonly @endcan>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('document.supplier') }} </label>
                                            <select name="supplier_id" id="supplier_id" class="selectpicker form-control" data-style="py-0"
                                            @can('edit-document-supplier') @else readonly @endcan>
                                                <option value=""> </option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->vendor_id }}" {{ $supplier->vendor_id == old('supplier_id') ? 'selected' : '' }}>
                                                    {{ $supplier->vendor_name ." | ". $supplier->vendor_code }}
                                                   
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>                                     
                                   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('document.document_date') }}</label>
                                            <select name="document_remark" id="document_remark" class="selectpicker form-control" data-style="py-0" require
                                            @can('edit-document-remark-type') @else readonly @endcan>
                                                <option value="">Select Remark Type </option>
                                            @foreach($document_remark_types as $document_remark_type)
                                                <option value="{{ $document_remark_type->id }}" {{ $document_remark_type->id == old('document_remark') ? 'selected' : '' }}>
                                                    {{ $document_remark_type->document_remark }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('document.category') }}</label>
                                            <select name="category_id" id="category_id" class="selectpicker form-control" data-style="py-0" require
                                            @can('edit-document-category') @else readonly @endcan>
                                                <option value="">Select Category </option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->product_category_id }}" {{ $category->product_category_id == old('category_id') ? 'selected' : '' }}>
                                                    {{ $category->remark }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @can('view-document-operation-attach-file')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operation {{ __('document.attach') }}({{ __('document.only_one_file') }})</label>
                                            <input name="operation_attach_file" type="file" class="form-control image-file"
                                            @can('edit-document-operation-attach-file') @else readonly @endcan>
                                        </div>
                                    </div>
                                    @endcan
                                    @can('view-document-operation-remark')    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Operation {{ __('document.remark') }} </label>
                                            <textarea name="operation_remark" class="form-control" rows="3"
                                            @can('edit-document-operation-remark') @else readonly @endcan>{{old('operation_remark')}}</textarea>
                                        </div>
                                    </div>
                                    @endcan
                                </div>        
                                <button type="submit" class="btn btn-primary mr-2">{{ __('button.save') }}</button>
                               
                                <a class="btn btn-light" href="{{ route('documents.index') }}">{{ __('button.back') }}</a>
                               
                            </form>
                        </div>
                        
                    </div>
                </div>

            </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#supplier_id').select2({
            width: '100%',
            placeholder: "Select an Supplier",
            allowClear: true,
        });
       
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
        if ($('#document_remark').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_remark') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
    }
</script>
@endsection