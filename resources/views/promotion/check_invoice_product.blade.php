@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Invoice Check Product for {{$promotion_sub_promotion->sub_promotions->name}}</h4>
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
                        <form action="{{ route('store_invoice_check',[$promotion_uuid,$promotion_sub_promotion->sub_promotion_uuid]) }}" method="POST" enctype="multipart/form-data" id="invoice_check_amount"
                        >
                        @csrf
                        <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$promotion_uuid}}">
                        <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid" value="{{$promotion_sub_promotion->sub_promotion_uuid}}">
                        <input type="hidden" name="invoice_check_type" value="2">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Product Code<span class="require_field" style="color:red">*</sapn></label>
                                <input type="text" name="product_code" id="product_code" class="form-control"
                                    data-errors="Please Enter Code." placeholder="" >
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-4">
                                <label>Product Name <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="product_name" id="product_name" class="form-control"
                                    data-errors="Please Enter Name." placeholder="" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-1">
                                <label>Qty
                                </label>
                                <input type="text" name="product_qty" id="product_qty" class="form-control"
                                    placeholder="0" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Amount
                                </label>
                                <input type="text" name="product_amount" id="product_amount" class="form-control"
                                    placeholder="0" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>

                        <div class="row m-2">
                            <button type="submit" class="btn btn-primary mr-2" id="amount_save" onClick="return InvoiceCheckProductValidateForm()">Save</button>
                            <a class="btn btn-light" href="{{ route('new_promotion.edit',$promotion_sub_promotion->promotion_uuid) }}"> Back</a>
                        </div>
                    </form>
                    <div class="col-md-12" id="add-list">
                        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                            <div class="mt-2">
                                <h4>Product List</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive rounded mb-126">
                                <table class="table mb-0 tbl-server-info" id="check_product_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
@section('js')
<script>
     function InvoiceCheckProductValidateForm(){
        sub_promotion_uuid = $('#sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        $.ajax({
            url: '../../../check_invoice_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/2',
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
                if(response.data){

                    if(response.data == 'different_type'){
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.this_subpromotion_is_used_by_amount') }}",
                            showCancelButton: true,
                            cancelButtonText: "{{ __('message.cancel') }}",
                            confirmButtonText: "{{ __('message.ok') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.add').modal('show');
                            } else {
                                return false;
                            }
                        });
                    }
                    return true;
                }
            },
            error: function() {

            }
        });
    }
    $(document).ready(function() {

        var check_product_table = $('#check_product_list').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "autoWidth": true,
            "responsive": true,
            "pageLength": 10,
            "scrollY": "450px",
            "scrollCollapse": true,
            'ajax': {
                'url': "/product_result",
                'type': 'GET',
                'data': function(d) {
                    d.sub_promotion_uuid = $('#sub_promotion_uuid').val();
                    d.promotion_uuid = $('#promotion_uuid').val();
                }
            },
            columns: [{
                    data: 'check_product_code',
                    name: 'check_product_code',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'check_product_name',
                    name: 'check_product_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'check_product_qty',
                    name: 'check_product_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center list-action" style="text-align:center">
                                <a class="badge bg-success mr-2" title="Edit" href="../../edit_invoice_check/${row.uuid}"><i class="ri-pencil-line mr-0"></i></a>

                                <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                            </div>
                            `
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        });
        check_product_table.on('click', '#delete', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.check_product_delete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    var uuid = $(this).data('uuid');
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '../../check_products_destory/' + uuid,
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
                            $('#check_product_code').val('');
                            $('#check_product_name').val('');
                            $('#check_product_qty').val('');
                            $('#check_product_list').DataTable().draw(true);
                        },
                        error: function() {
                            $('#check_product_code').addClass('is-invalid');
                            $('#promotion_uuid').val('');
                            $('#sub_promotion_uuid').val('');
                            $('#check_product_name').val('');
                            $('#check_product_qty').val('');
                        }
                    });
                } else {
                    return false;
                }
            });
        })
    });
</script>
@endsection
