@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('lucky_draw.edit') }}</h4>
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
                            <form action="{{ route('lucky_draws.update',$lucky_draw->uuid) }}" method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
                            @csrf
                            @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('lucky_draw.name')}} <span class="cancel_status">*</sapn>  </label>
                                            <input name="name" type="text" class="form-control" value="{{$lucky_draw->name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>{{__('lucky_draw.status')}} <span class="cancel_status">*</sapn> </label>
                                        <select id="status" name="status" class="form-control ">
                                            @can('approve-promotion')
                                            <option value="1" {{$lucky_draw->status == 1 ? 'selected': '' }}>Active</option>
                                            <option value="2" {{$lucky_draw->status == 2 ? 'selected': '' }}>Inactive</option>
                                            @endcan

                                            <option value="3" {{$lucky_draw->status == 3 ? 'selected': '' }}>Pending</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*</sapn>  </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_branch" id="select_all_branch" {{$luckydraw_branches ? '' : 'checked' }}>
                                            <label for="select_all_branch">Select All Branch</label>

                                            <select name="branch_id[]" id="branch_id" class="form-control " multiple required>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->branch_id }}" {{ in_array($branch->branch_id, $luckydraw_branches ?: []) ? 'selected' : '' }}>
                                                        {{ $branch->branch_name_eng}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.category')}} <span class="cancel_status">*</sapn> </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_category" id="select_all_category" {{$luckydraw_categories ? '' : 'checked' }}>
                                            <label for="select_all_branch">Select All Category</label>

                                            <select name="category_id[]" id="category_id" class="form-control " multiple required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ in_array($category->id, $luckydraw_categories ?: []) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.brand')}} <span class="cancel_status">*</sapn> </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_brand" id="select_all_brand"  {{ $luckydraw_brands ? '' : 'checked' }}>
                                            <label for="select_all_branch">Select All Brand</label>

                                            <select name="brand_id[]" id="brand_id" class="form-control " multiple required>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->product_brand_id }}" {{ in_array($brand->product_brand_id, $luckydraw_brands ?: []) ? 'selected' : '' }}>
                                                        {{ $brand->product_brand_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.start_date') }} <span class="cancel_status">*</sapn> </label>
                                            <input name="start_date" type="date" class="form-control" id="documentDate" value="{{$lucky_draw->start_date}}"
                                           >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.end_date') }} <span class="cancel_status">*</sapn> </label>
                                            <input name="end_date" type="date" class="form-control" id="documentDate" value="{{$lucky_draw->end_date}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('lucky_draw.amount_for_one_ticket')}} <span class="cancel_status">*</sapn> </label>
                                            <input id="amount_for_one_ticket" name="amount_for_one_ticket" type="text" class="form-control" value="{{$lucky_draw->amount_for_one_ticket}}"  data-type="currency">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.discon_status')}} <span class="cancel_status">*</sapn>  </label>
                                            <div class="radio d-inline-block mr-2">
                                                <input type="radio" name="discon_status" id="radio1" value='1' @if($lucky_draw->discon_status == 1) checked @endif>
                                                <label for="radio1">Include</label>
                                            </div>
                                            <div class="radio d-inline-block mr-2">
                                                <input type="radio" name="discon_status" id="radio2" value='2' @if($lucky_draw->discon_status == 2) checked @endif >
                                                <label for="radio2">Exclude</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.remark') }}</label>
                                            <textarea name="remark" class="form-control" rows="3">{{$lucky_draw->remark}}
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.image') }} (560px x 140px) <span class="cancel_status">*</sapn></label>
                                            <input name="promotion_image" type="file" class="form-control image-file">
                                        </div>
                                        <div class="form-group">
                                            <img src="{{ asset('images/promotion_image/'.$lucky_draw->uuid .'.png') }}" class="img-fluid rounded-normal" alt="logo">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mr-2">{{ __('button.update') }}</button>
                                <a href="#" class="btn btn-secondary mr-2" id="add_price">{{__('button.add_prize')}}</a>

                                <a class="btn btn-light" href="{{ route('lucky_draws.index') }}">{{ __('button.back') }}</a>

                            </form>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-3">{{ __('lucky_draw.added_prizes') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive rounded mb-3">
                                    <table class="table mb-0 tbl-server-info" id="lucky_draw_price_list">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th>{{ __('price.price_order') }}</th>
                                                <th>{{ __('price.price_name') }}</th>
                                                <th>{{ __('price.price_amount') }}</th>
                                                <th>{{ __('price.action') }}</th>
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

            </div>
            <!-- Page end  -->
            <div class="modal fade add_price" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="price_modal_title">{{ __('price.add_price') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="price_form" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$lucky_draw->uuid}}" />
                            <input type="hidden" name="price_id" id="price_id" value="" />

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('price.price_order') }} <span class="cancel_status">*</sapn></label>
                                            <input type="text" name="order" id="price_order" class="form-control" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('price.price_name') }} <span class="cancel_status">*</sapn></label>
                                            <input type="text" name="name" id="price_name" class="form-control" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('price.price_amount') }} <span class="cancel_status">*</sapn></label>
                                            <input type="text" name="amount" id="price_amount" class="form-control" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('price.price_quantity') }} <span class="cancel_status">*</sapn></label>
                                            <input type="text" name="quantity" id="price_quantity" class="form-control" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>{{ __('price.price_description') }}</label>
                                            <textarea id="price_description" name="description" class="form-control" rows="1"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="price_modal_submit_button">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
    $('#branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    $('#category_id').select2({
        width: '100%',
        allowClear: true,
    });
    $('#brand_id').select2({
        width: '100%',
        allowClear: true,
    });
    $("input[data-type='currency']").on({
        keyup: function() {
        formatCurrency($(this));
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") { return; }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
            right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val =  left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);

            // final formatting
            if (blur === "blur") {
            input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
    $(document).ready(function() {
        $("#amount_for_one_ticket").val() == "" ? '' :formatCurrency($("#amount_for_one_ticket"));
        function makeDisableForBranchSelectAll(){
            if($("#select_all_branch").is(':checked') ){
                $("#branch_id").val(null).trigger("change");
                $('#branch_id').attr("disabled", true);
            }else{
                $('#branch_id').attr("disabled", false);
            }
        }
        makeDisableForBranchSelectAll();
        function makeDisableForCategorySelectAll(){
            if($("#select_all_category").is(':checked') ){
                $("#category_id").val(null).trigger("change");
                $('#category_id').attr("disabled", true);
            }else{
                $('#category_id').attr("disabled", false);
            }
        }
        makeDisableForCategorySelectAll();
        function makeDisableForBrandSelectAll(){
            if($("#select_all_brand").is(':checked') ){
                $("#brand_id").val(null).trigger("change");
                $('#brand_id').attr("disabled", true);
            }else{
                $('#brand_id').attr("disabled", false);
            }
        }
        makeDisableForBrandSelectAll();
        $(document).on("click", "#select_all_branch", function() {
            makeDisableForBranchSelectAll();
        })
        $(document).on("click", "#select_all_category", function() {
            makeDisableForCategorySelectAll();
        })
        $(document).on("click", "#select_all_brand", function() {
            makeDisableForBrandSelectAll();
        })
    })
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

    $(document).on("click", "#add_price", function() {
            $('#price_order').val('');
            $('#price_name').val('');
            $('#price_amount').val('');
            $('#price_quantity').val('');
            $('#price_description').val('');
            $('#price_modal_submit_button').text('Save');
            $('.add_price').modal('show');
    });

    $(document).on("click", "#price_modal_submit_button", function() {
            $('#price_form').submit();
    });

    var table = $('#lucky_draw_price_list1').DataTable({
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
                'url': "/lucky_draws_prizes/search_result",
                'type': 'GET',
                'data': function(d) {
                    d.lucky_draw_uuid = $('#promotion_uuid').val();
                }
            },
            columns: [{
                    data: 'order',
                    name: 'order',
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
                    data: 'amount',
                    name: 'amount',
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
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        id="edit" href="#"" data-lucky_draw_price_id="${row.id}"><i class="ri-pencil-line mr-0"></i></a>

                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete"
                                        id="delete" href="#"" data-lucky_draw_price_id="${row.id}"
                                        ><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })


    table.on('click', '#edit', function(e) {
        e.preventDefault();
        var lucky_draw_price_id = $(this).data('lucky_draw_price_id');
        $.ajax({
            url: '../../lucky_draws_prizes/' + lucky_draw_price_id,
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
                $('#price_order').val('');
                $('#price_order').val(response.order);
                $('#price_name').val('');
                $('#price_name').val(response.name);
                $('#price_amount').val('');
                $('#price_amount').val(response.amount);
                $('#price_quantity').val('');
                $('#price_quantity').val(response.quantity);
                $('#price_description').val('');
                $('#price_description').val(response.description);
                $('#price_id').val(response.id);

                $('#price_modal_submit_button').text('Update');
                $('#price_modal_submit_button').show();
                $('#price_modal_title').text('Edit Product');
                $('.add_price').modal('show');
            },
            error: function() {
                $('#price_order').val('');
                $('#price_name').val('');
                $('#price_amount').val('');
                $('#price_quantity').val('');
                $('#price_description').val('');
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
    function validatePriceForm() {
    }
</script>
@endsection
