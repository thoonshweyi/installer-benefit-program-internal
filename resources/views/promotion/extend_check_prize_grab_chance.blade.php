@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Extend Item for Grab The Chance</h4>
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
                        <form action="{{ route('update_extend_items',$prizeCCCheck->uuid) }}" method="POST" enctype="multipart/form-data" id="invoice_check_amount">
                        @csrf
                        <input type="hidden" name="prizeCCCheckUUid" id="prizeCCCheckUUid" value="{{$prizeCCCheck->uuid}}">
                        <input type="hidden" name="branch_id" id="branch_id" value="{{$branch->branch_id}}">
                        <input type="hidden" name="history_uuid" id="history_uuid" value="">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-4" id="cash_coupons"   @if(isset($prizeCCCheck)) @if($prizeCCCheck->prizeItem->type == 2)  style="display: none;"@endif @endif>
                                <label>Name <span class="require_field" style="color:red">*</sapn>
                                </label></br>
                                <label>{{$prizeCCCheck->prizeItem->name}}
                                </label>
                            </div>
                            <div class="col-md-4" id="cash_coupons"   @if(isset($prizeCCCheck)) @if($prizeCCCheck->prizeItem->type == 2)  style="display: none;"@endif @endif>
                                <label>Branch <span class="require_field" style="color:red">*</sapn>
                                </label></br>
                                <label>{{$branch->branch_name_eng}}
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label>Extended Qty<span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="qty" id="qty" class="form-control"
                                    placeholder="" data-errors="" required>

                            </div>

                        </div>

                        <div class="row m-2">
                            <button type="submit" class="btn btn-primary mr-2" id="amount_save" onClick="return InvoiceCheckProductValidateForm()">Save</button>
                            <a class="btn btn-light" href="{{ route('edit_prize_check',[$prizeCCCheck->uuid,2])}}"> Back</a>
                        </div>
                    </form>
                    <div class="col-md-12" id="add-list">
                        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                            <div class="mt-2">
                                <h4>Extended History</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive rounded mb-126">
                                <table class="table mb-0 tbl-server-info" id="extended_prize_check_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th style="text-align:left;">Created At</th>
                                            <th style="text-align:left;">User Name</th>
                                            <th style="text-align:left;">Extend Qty</th>
                                            <th style="text-align:left;">Action</th>
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
    $(document).ready(function() {

        var check_product_table = $('#extended_prize_check_list').DataTable({
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
                'url': "/extended_prize_check_list",
                'type': 'GET',
                'data': function(d) {
                    d.prizeCCCheckUUid = $('#prizeCCCheckUUid').val();
                    d.branch_id = $('#branch_id').val();
                }
            },
            columns: [
                {
                    data: 'created_at',
                    name: 'created_at',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'extended_qty',
                    name: 'extended_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
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
                            $('#check_product_name').val("");
                            $('#check_product_qty').val("");
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
