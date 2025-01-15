@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Winning Chance</h4>
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
                        <form action="{{ route('store_winning_chance',[$promotion_uuid,$sub_promotion_uuid]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$promotion_uuid}}">
                        <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid" value="{{$sub_promotion_uuid}}">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Minimum Amount  <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="minimum_amount" id="minimum_amount" class="form-control"
                                    data-errors="Please Enter Code.">
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-4">
                                <label>Calculation Type  <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <select name="calculation_type" class="form-control ">
                                    <option value="1">By Qty of Products</option>
                                    <option value="2">By Product's Qty</option>
                                </select>
                            </div>

                        </div>


                        <div class="row m-2">
                            <button type="submit" class="btn btn-primary mr-2" id="amount_save">Save</button>
                            <a class="btn btn-light" href="{{ route('view_prize_check',[$promotion_uuid,$sub_promotion_uuid]) }}"> Back</a>
                        </div>
                    </form>
                    <div class="col-md-12" id="add-list">
                        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                            <div class="mt-2">
                                <h4>Winning Chance List</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="winning_chance_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>Branch</th>
                                            <th>Minimum Amount</th>
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
<div class="modal fade winning_chance_edit_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title"> Winning Chance For <span id="branch_name"> </span> <span
                        id="winning_chance_amount"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('winning_chance_percentage_store') }}" method="POST" name="winning_chance_form"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row" style="margin-left:5%;">
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Winning Chance (%)</th>
                            </tr>
                            <tr>
                                <tbody id="detail" name="detail"></tbody>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"
                        id="winning_chance_percentage_save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
     $('#branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    function makeDisableForBranchSelectAll(){
        if($("#select_all_branch").is(':checked') ){
            $("#branch_id").val(null).trigger("change");
            $('#branch_id').attr("disabled", true);
        }else{
            $('#branch_id').attr("disabled", false);
        }
    }
    $(document).ready(function() {
        makeDisableForBranchSelectAll();
        $(document).on("click", "#select_all_branch", function() {
            makeDisableForBranchSelectAll();
        })

        var winning_chance_table = $('#winning_chance_list').DataTable({
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
                'url': "/winning_result",
                'type': 'GET',
                'data': function(d) {
                    d.promotion_uuid = $('#promotion_uuid').val();
                    d.sub_promotion_uuid = $('#sub_promotion_uuid').val();
                }
            },
            columns: [{
                    data: 'branch_id',
                    name: 'branch_id',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'minimum_amount',
                    name: 'minimum_amount',
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
                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                            id="edit" href="#"" data-uuid="${row.uuid}"><i class="ri-pencil-line mr-0"></i></a>
                            <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                            </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        winning_chance_table.on('click', '#edit', function(e) {
            $('.edit_form').modal('hide');
            e.preventDefault();
            var id = $(this).data('uuid');

            $.ajax({
                url: '../../winning_chance_edit/' + id,
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
                    $('.winning_chance_edit_form').modal('show');
                    $('#branch_name').html(response.infor.branch_name);
                    $('#winning_chance_amount').html(response.infor.winning_chance_amount);
                    var templateString;
                    var templateStringuuid;
                    var data = response.data;
                    $.each(data, function(i) {
                        var old_value = data[i].winning_percentage ?? 0;
                        templateString += '<tr><th>' + data[i].name + '</th>' +
                            '<td><input type="hidden" name=main_uuid[] value="' + data[i]
                            .main_uuid + '">' +
                            '<input type="text" name=winning_chance[] class="form-control" data-errors="Please Enter Amount." value="' +
                            old_value + '" required></td></tr>'
                    })
                    $('#detail').html("");
                    $('#detail').append(templateString)
                },
                error: function() {

                }
            });
        })

        winning_chance_table.on('click', '#delete', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.winning_chance_delete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result) => {

                if (result.isConfirmed) {
                    var uuid = $(this).data('uuid');
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '../../winning_chance_destory/' + uuid,
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
                            $('#winning_chance_list').DataTable().draw(true);
                        },
                        error: function() {
                            $('#check_product_code').addClass('is-invalid');
                            $('#check_product_name').val("");
                            $('#winning_chance_list').val("");
                        }
                    });
                } else {
                    return false;
                }
            });
        })
    });

    $(document).on("click", "#winning_chance_percentage_save", function(event) {
    var ele = document.winning_chance_form.getElementsByTagName('input');
    var value = 0;
    // LOOP THROUGH EACH ELEMENT.
    for (i = 0; i < ele.length; i++) {
        // CHECK THE ELEMENT TYPE.
        if (ele[i].type == 'text') {
            value += parseFloat(ele[i].value);
        }
    }
    if (value != 100) {
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.need_to_be_100') }}",
            confirmButtonText: "{{ __('message.ok') }}",
        });
        return false;
        event.preventDefault();
    }
    });

</script>
@endsection
