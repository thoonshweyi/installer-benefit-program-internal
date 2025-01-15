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
                        <form action="{{ route('new_promotion.update',$lucky_draw->uuid) }}" method="POST"
                            enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('lucky_draw.name')}} <span class="cancel_status">*</sapn> </label>
                                        <input name="name" id="name" type="text" class="form-control"
                                            value="{{$lucky_draw->name}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Promotion Type <span class="cancel_status">*</sapn> </label>
                                        <select id="lucky_draw_type_uuid" name="lucky_draw_type_uuid" class="form-control ">
                                            <option value="">Select Type</option>
                                            @foreach($lucky_draw_types as $lucky_draw_type)
                                            <option value="{{ $lucky_draw_type->uuid }}"
                                            {{ $lucky_draw->lucky_draw_type_uuid == $lucky_draw_type->uuid ? 'selected' : '' }}>
                                                {{ $lucky_draw_type->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{__('lucky_draw.status')}} <span class="cancel_status">*</sapn> </label>
                                        <select id="status" name="status" class="form-control ">
                                            @can('approve-promotion')
                                            <option value="1" {{$lucky_draw->status == 1 ? 'selected': '' }}>Active
                                            </option>
                                            <option value="2" {{$lucky_draw->status == 2 ? 'selected': '' }}>Inactive
                                            </option>

                                            <option value="3" {{$lucky_draw->status == 3 ? 'selected': '' }}>Pending
                                            </option>
                                            @else
                                            <option value="{{ $lucky_draw->status }}">
                                                @if ($lucky_draw->status == 1 )
                                                        Active
                                                @elseif ($lucky_draw->status == 2 )
                                                        Inactive
                                                @elseif ($lucky_draw->status == 3 )
                                                        Pending
                                                @endif
                                            </option>
                                            @endcan
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                                </sapn> </label>
                                        <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                            id="select_all_branch" {{$luckydraw_branches ? '' : 'checked' }}>
                                        <label for="select_all_branch">Select All Branch</label>
                                        <select name="branch_id[]" id="branch_id" class="form-control " multiple
                                            required>
                                            @foreach($branches as $branch)
                                            <option value="{{ $branch->branch_id }}"
                                                {{ in_array($branch->branch_id, $luckydraw_branches ?: []) ? 'selected' : '' }}>
                                                {{ $branch->branch_name_eng}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.category')}} <span class="cancel_status">*
                                                </sapn> </label>
                                        <input type="checkbox" class="checkbox-input" name="select_all_category"
                                            id="select_all_category" {{$luckydraw_categories ? '' : 'checked' }}>
                                        <label for="select_all_branch">Select All Category</label>

                                        <select name="category_id[]" id="category_id" class="form-control " multiple
                                            required>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $luckydraw_categories ?: []) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.brand')}} <span class="cancel_status">*
                                                </sapn> </label>
                                        <input type="checkbox" class="checkbox-input" name="select_all_brand"
                                            id="select_all_brand" {{ $luckydraw_brands ? '' : 'checked' }}>
                                        <label for="select_all_branch">Select All Brand</label>

                                        <select name="brand_id[]" id="brand_id" class="form-control " multiple required>
                                            @foreach($brands as $brand)
                                            <option value="{{ $brand->product_brand_id }}"
                                                {{ in_array($brand->product_brand_id, $luckydraw_brands ?: []) ? 'selected' : '' }}>
                                                {{ $brand->product_brand_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lucky_draw.start_date') }} <span class="cancel_status">*</sapn>
                                        </label>
                                        <input name="start_date" type="date" class="form-control" id="documentDate"
                                            value="{{$lucky_draw->start_date}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lucky_draw.end_date') }} <span class="cancel_status">*</sapn>
                                        </label>
                                        <input name="end_date" type="date" class="form-control" id="documentDate"
                                            value="{{$lucky_draw->end_date}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.discon_status')}} <span
                                                class="cancel_status">*</sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="discon_status" id="radio1" value='1'
                                                @if($lucky_draw->discon_status == 1) checked @endif>
                                            <label for="radio1">Include</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="discon_status" id="radio2" value='2'
                                                @if($lucky_draw->discon_status == 2) checked @endif >
                                            <label for="radio2">Exclude</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.diposit_type')}} <span
                                                class="cancel_status">*</sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="diposit_type_id" id="diposit_type_radio1" value='1'
                                                @if($lucky_draw->diposit_type_id == 1) checked @endif>
                                            <label for="radio1">All</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="diposit_type_id" id="diposit_type_radio2" value='2'
                                                @if($lucky_draw->diposit_type_id == 2) checked @endif >
                                            <label for="radio2">Structure</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="diposit_type_id" id="diposit_type_radio3" value='3'
                                                @if($lucky_draw->diposit_type_id == 3) checked @endif >
                                            <label for="radio3">HIP</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mr-2">{{ __('button.update') }}</button>
                            <a href="#" class="btn btn-secondary mr-2"
                                id="add_sub_promoiton">{{__('button.add_sub_promotion')}}</a>
                            <a class="btn btn-light" href="{{ route('new_promotion.index') }}">{{ __('button.back') }}</a>
                        </form>
                    </div>
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-3">Sub Promotion List</h4>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="sub_promotion_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>{{ __('price.price_name') }}</th>
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
        <div class="modal fade add_sub_promoiton" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="price_modal_title">Add Sub Promotion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="sub_promotion_form" action="{{ route('sub_promotion.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$lucky_draw->uuid}}" />
                        <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid" value="" />
                        <input type="hidden" name="promotion_sub_promotion_uuid" id="promotion_sub_promotion_uuid" value="" />
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name<span class="cancel_status">*</sapn> </label> <label id="back_to_select_box" class="back_to_select_box">Choose Old Name</label>
                                        <select id="sub_promotion_name1" name="sub_promotion_name" class="form-control">
                                            <option value="">Select Sub Promton</option>
                                            @foreach($sub_promotions as $sub_promotion)
                                                <option value="{{$sub_promotion->uuid}}">{{$sub_promotion->name}}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                        <input type="text" id="sub_promotion_name2" name="sub_promotion_name" class="form-control" style="display:none" disabled/>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.check_invoice')}} <span
                                                class="cancel_status">*</sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="invoice_check_type" id="invoice_check_type1" value='1' onClick="return InvoiceCheckAmountValidateForm()">
                                            <label for="invoice_check_type1">By Amount</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="invoice_check_type" id="invoice_check_type2" value='2' onClick="return InvoiceCheckProductValidateForm()">
                                            <label for="invoice_check_type2">By Product</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.check_prize')}} <span
                                                class="cancel_status">*</sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="prize_check_type" id="prize_check_type1" value='1' onClick="PrizeCheckValidateForm(1)">
                                            <label for="prize_check_type1">By Ticket</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="prize_check_type" id="prize_check_type2" value='2' onClick="PrizeCheckValidateForm(2)">
                                            <label for="prize_check_type2">By Grab the Chance</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="prize_check_type" id="prize_check_type3" value='3' onClick="PrizeCheckValidateForm(3)">
                                            <label for="prize_check_type3">By Fix Prize</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <!-- <img class="sub-promotion-image" style="width: 100px;" id="sub_promotion_image_01" />
                                    <img class="sub-promotion-image" style="width: 100px;" id="sub_promotion_image_02" /> -->
                                    <img class="sub-promotion-image" style="width: 100px;" id="sub_promotion_image_03" />
                                </div>
                                <!-- <div class="col-md-12">
                                    <label for="prize_check_type3">Select 3 Images to Display</label>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                                </div> -->
                                <div class="col-md-12">
                                    <label for="prize_check_type3">Select 1 Image to Display</label>
                                    <input type="file" name="images" id="images" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
    function makeDisableForBranchSelectAll(){
        if($("#select_all_branch").is(':checked') ){

            $("#branch_id").val(null).trigger("change");
            $('#branch_id').attr("disabled", true);
        }else{
            $('#branch_id').attr("disabled", false);
        }
    }
    function makeDisableForCategorySelectAll(){
        if($("#select_all_category").is(':checked') ){
            $("#category_id").val(null).trigger("change");
            $('#category_id').attr("disabled", true);
        }else{
            $('#category_id').attr("disabled", false);
        }
    }
    function makeDisableForBrandSelectAll(){
        if($("#select_all_brand").is(':checked') ){
            $("#brand_id").val(null).trigger("change");
            $('#brand_id').attr("disabled", true);
        }else{
            $('#brand_id').attr("disabled", false);
        }
    }
    function InvoiceCheckAmountValidateForm(){
        sub_promotion_uuid = $('#sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        if(sub_promotion_uuid){
            $.ajax({
                url: '../../../check_invoice_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/1',
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
                                text: "{{ __('message.this_subpromotion_is_used_by_product') }}",
                                showCancelButton: true,
                                cancelButtonText: "{{ __('message.cancel') }}",
                                confirmButtonText: "{{ __('message.ok') }}"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#invoice_check_amount').submit();
                                } else {
                                    document.getElementById("invoice_check_type2").checked = true;
                                    return false;
                                }
                            });
                        }
                        if(response.data == 'sub_prmotion_is_used'){
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.sub_prmotion_is_used') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                            return false;
                        }
                    }
                },
                error: function() {

                }
            });
        }else{

        }

    }
    function InvoiceCheckProductValidateForm(){
        sub_promotion_uuid = $('#sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        if(sub_promotion_uuid){
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
                                    document.getElementById("invoice_check_type1").checked = true;
                                    return false;
                                }
                            });
                        }
                        if(response.data == 'sub_prmotion_is_used'){
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.sub_prmotion_is_used') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                            return false;
                        }
                        return true;
                    }
                },
                error: function() {

                }
            });
        }
    }
    function PrizeCheckValidateForm(type){
        sub_promotion_uuid = $('#sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        if(sub_promotion_uuid){
            $.ajax({
                url: '../../../check_prize_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/'+ type,
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
                                text: "{{ __('message.this_subpromotion_is_used_by_other_prize_check_type') }}",
                                showCancelButton: true,
                                cancelButtonText: "{{ __('message.cancel') }}",
                                confirmButtonText: "{{ __('message.ok') }}"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.add').modal('show');
                                } else {
                                    if(type == 1){
                                        document.getElementById("prize_check_type1").checked = true;
                                    }
                                    if(type == 2){
                                        document.getElementById("prize_check_type2").checked = true;
                                    }
                                    if(type == 3){
                                        document.getElementById("prize_check_type3").checked = true;
                                    }
                                    return false;
                                }
                            });
                        }
                        if(response.data == 'sub_prmotion_is_used'){
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.sub_prmotion_is_used') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                            return false;
                        }
                        return true;
                    }
                },
                error: function() {

                }
            });
        }

    }


    $(document).ready(function() {
        makeDisableForBranchSelectAll();
        makeDisableForCategorySelectAll();
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
        $(document).on("click", "#add_sub_promoiton", function() {
            $('#price_modal_submit_button').text('Save');
            $("#invoice_check_type1").prop("checked", true)
            $("#prize_check_type1").prop("checked", true)
            $('#sub_promotion_name1').show();
            $("#sub_promotion_name2").prop("readonly", false);
            $("#sub_promotion_name1").prop("disabled", false);
            $('#sub_promotion_name2').val('');
            $('#sub_promotion_uuid').val('');
            $('#sub_promotion_name2').hide();
            $('#sub_promotion_name1').val('');
            document.getElementById('sub_promotion_image_03').setAttribute('src', '');
            $('#price_modal_title').text('Add Sub Promoiton');
            $('.add_sub_promoiton').modal('show');
        });
        $(document).on("change", "#sub_promotion_name1", function() {
            if ((this.value) == 'other') {
                $('#sub_promotion_name1').hide();
                $("#sub_promotion_name1").prop("disabled", true);
                $('#sub_promotion_name2').show();
                $("#sub_promotion_name2").prop("readonly", false);
                $("#sub_promotion_name2").prop("disabled", false);
            }else{
                document.getElementById("sub_promotion_uuid").value = this.value;
            }
        });
        $(document).on("change", "#sub_promotion_name2", function() {
            document.getElementById("sub_promotion_uuid").value = 'other';
        })
        $(document).on("click", "#back_to_select_box", function() {
            $('#sub_promotion_name1').show();
            $("#sub_promotion_name1").prop("disabled", false);
            $('#sub_promotion_name2').hide();
            $("#sub_promotion_name2").prop("disabled", true);
        });

        $(document).on("click", "#price_modal_submit_button", function() {
            $('#sub_promotion_form').submit();
        });

        var sub_promotion_table = $('#sub_promotion_list').DataTable({
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
                'url': "/search_sub_promotion_result",
                'type': 'GET',
                'data': function(d) {
                    d.promotion_uuid = $('#promotion_uuid').val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name',
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
                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit"
                                id="edit" data-sub_promotion_uuid="${row.sub_promotion_uuid}"><i class="ri-pencil-line"></i></a>

                                <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="Check Invoice"
                                id="check_invoice" href="../../view_invoice_check/${row.promotion_uuid}/${row.sub_promotion_uuid}"><i class="ri-checkbox-line"></i></a>

                                <a class="badge bg-light mr-2" data-toggle="tooltip" data-placement="top" title="Check Prize"
                                id="check_prize" href="../../view_prize_check/${row.promotion_uuid}/${row.sub_promotion_uuid}"" data-sub_promotion_uuid="${row.sub_promotion_uuid}"><i class="ri-star-line mr-0"></i></a>

                                <a class="badge bg-warning mr-2" data-sub_promotion_uuid="${row.sub_promotion_uuid}" title="Delete" id="delete" href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                            </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        sub_promotion_table.on('click', '#edit', function(e) {
            e.preventDefault();
            var sub_promotion_uuid = $(this).data('sub_promotion_uuid');
            var promotion_uuid = $('#promotion_uuid').val();
            $.ajax({
                url: '../../get_sub_promotion/'+ promotion_uuid + '/' +sub_promotion_uuid,
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
                    $('#sub_promotion_name2').val(response.sub_promotion_name);
                    $('#sub_promotion_name1').hide();
                    $('#sub_promotion_name2').show();
                    $("#sub_promotion_name2").prop("disabled", false);
                    $("#sub_promotion_name2").prop("readonly", true);
                    if(response.invoice_check_type == 1 ) {
                        $("#invoice_check_type1").prop("checked", true)
                    }
                    if(response.invoice_check_type == 2 ) {
                        $("#invoice_check_type2").prop("checked", true)
                    }
                    if(response.prize_check_type == 1 ) {
                        $("#prize_check_type1").prop("checked", true)
                    }
                    if(response.prize_check_type == 2 ) {
                        $("#prize_check_type2").prop("checked", true)
                    }
                    if(response.prize_check_type == 3 ) {
                        $("#prize_check_type3").prop("checked", true)
                    }
                    // $("#sub_promotion_image_01").attr("src",`../../../images/promotion_icons/${promotion_uuid}/${response.sub_promotion_uuid}/0.png`);
                    // $("#sub_promotion_image_02").attr("src",`../../../images/promotion_icons/${promotion_uuid}/${response.sub_promotion_uuid}/1.png`);
                    // $("#sub_promotion_image_03").attr("src",`../../../images/promotion_icons/${promotion_uuid}/${response.sub_promotion_uuid}/2.png`);

                    $("#sub_promotion_image_03").attr("src",`../../../images/promotion_images/${promotion_uuid}/${response.sub_promotion_uuid}/show_image/${promotion_uuid}.png`);

                    $("#sub_promotion_uuid").val(response.sub_promotion_uuid)
                    $("#promotion_sub_promotion_uuid").val(response.uuid)

                    $('#price_modal_submit_button').text('Update');
                    $('#price_modal_title').text('Update Sub Promoiton');
                    $('.add_sub_promoiton').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: "{{ __('message.warning') }}",
                        text: `{{ __('message.validation_error') }}`,
                        confirmButtonText: "{{ __('message.ok') }}",
                    });
                }
            });
        })

        sub_promotion_table.on('click', '#delete', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.sub_promotion_delete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    var sub_promotion_uuid = $(this).data('sub_promotion_uuid');
                    var promotion_uuid = $('#promotion_uuid').val();
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '../../sub_promotion_destory/' + promotion_uuid + '/' + sub_promotion_uuid,
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
                            $('#name').val('');
                            $('#sub_promotion_list').DataTable().draw(true);
                            $('#sub_promotion_list').DataTable().reload();
                        },
                        error: function() {
                            $('#name').val("");
                        }
                        //  table.ajax.reload();
                    });
                } else {
                    return false;
                }
            });
        })
    })
</script>
@endsection
