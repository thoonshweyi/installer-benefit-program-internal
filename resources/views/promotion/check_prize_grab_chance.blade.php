@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Prize Check Grab The Chance for
                                    {{ $promotion_sub_promotion->sub_promotions->name }}</h4>
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
                            <form
                                action="{{ route('store_prize_check', [$promotion_uuid, $promotion_sub_promotion->sub_promotion_uuid]) }}"
                                method="POST" enctype="multipart/form-data" id="invoice_check_amount">
                                @csrf
                                <input type="hidden" name="promotion_uuid" id="promotion_uuid"
                                    value="{{ $promotion_uuid }}">
                                <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid"
                                    value="{{ $promotion_sub_promotion->sub_promotion_uuid }}">
                                <input type="hidden" name="prize_check_type" value="2">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if (isset($prizeCCCheck))
                                            <input type="radio" name="grab_the_chance_type" id="chance_type1"
                                                value="1" @if ($prizeCCCheck->prizeItem->type == 1) checked @endif>
                                            <label>Cash Coupon</label>
                                            <input type="radio" name="grab_the_chance_type" id="chance_type2"
                                                value="2" @if ($prizeCCCheck->prizeItem->type == 2) checked @endif>
                                            <label>Present</label>
                                        @else
                                            <input type="radio" name="grab_the_chance_type" id="chance_type1"
                                                value="1" checked="">
                                            <label>Cash Coupon</label>
                                            <input type="radio" name="grab_the_chance_type" id="chance_type2"
                                                value="2">
                                            <label>Present</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3" id="cash_coupons"
                                        @if (isset($prizeCCCheck)) @if ($prizeCCCheck->prizeItem->type == 2)  style="display: none;" @endif
                                        @endif>
                                        <label>Name <span class="require_field" style="color:red">*</sapn>
                                        </label>
                                        <select id="cash_coupon_name" name="cash_coupon_name" class="form-control">
                                            <option value="">Select Cash Coupon</option>
                                            @foreach ($cash_coupons as $cash_coupon)
                                                <option value="{{ $cash_coupon->uuid }}"
                                                    @if (isset($prizeCCCheck))
                                                    @if ($prizeCCCheck->prize_item_uuid == $cash_coupon->uuid)
                                                        selected @endif
                                                    @endif
                                                    {{ $cash_coupon->name }}</option> {{ $cash_coupon->name }}
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" id="presents"
                                        @if (isset($prizeCCCheck)) @if ($prizeCCCheck->prizeItem->type == 1)  style="display: none;" @endif
                                        @endif>
                                        <label>Name <span class="require_field" style="color:red">*</sapn>
                                        </label>
                                        <select id="present_name" name="present_name" class="form-control">
                                            <option value="">Select Present</option>
                                            @foreach ($presents as $present)
                                                <option value="{{ $present->uuid }}"
                                                    @if (isset($prizeCCCheck)) @if ($prizeCCCheck->prize_item_uuid == $present->uuid)
                                                selected @endif
                                                    @endif
                                                    >{{ $present->name }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>GP Code<span class="require_field" style="color:red">*</sapn>
                                        </label>
                                        <input type="text" name="gp_code" id="gp_code" class="form-control"
                                            @if (isset($prizeCCCheck->prizeItem)) value="{{ $prizeCCCheck->prizeItem->gp_code }}"; @endif
                                            placeholder="" data-errors="Please Enter Unit.">
                                    </div>
                                    <div class="col-md-2">
                                        <label> Image <span class="require_field" style="color:red">*</sapn></label>
                                        <input type="file" name="ticket_image" id="ticket_image" class="form-control"
                                            placeholder="1" data-errors="Please Enter Unit."
                                            @if (!isset($prizeCCCheck)) required @endif>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                                    </sapn> </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                                id="select_all_branch" {{old("select_all_branch") ? 'checked' : ''}}>
                                            <label for="select_all_branch">Select All Branches of This Promotion</label>
                                            <select name="branch_id[]" id="branch_id" class="form-control " multiple>
                                                @foreach($lucky_draw_branches as $lucky_draw_branch)
                                                <option value="{{ $lucky_draw_branch->branch_id }}"
                                                    {{ in_array($lucky_draw_branch->branch_id, old("branch_id") ?: []) ? 'selected' : '' }}>
                                                    {{ $lucky_draw_branch->branches->branch_name_eng}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row m-2">
                                    <button type="submit" class="btn btn-primary mr-2" id="amount_save">Save</button>
                                    <button type="button" class="btn btn-secondary mr-2"
                                        onClick="CheckWinningChanceValidate( @php $message_status @endphp);"> Winning
                                        Chance</button>
                                    <a class="btn btn-light"
                                        href="{{ route('new_promotion.edit', $promotion_sub_promotion->promotion_uuid) }}">
                                        Back</a>
                                </div>
                            </form>
                            <div class="col-md-12" id="add-list">
                                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                                    <div class="mt-2">
                                        <h4>Item List</h4>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive rounded mb-126">
                                        <table class="table mb-0 tbl-server-info" id="prize_check_item_list">
                                            <thead class="bg-white text-uppercase">
                                                <tr class="ligth ligth-data">
                                                    <th>Name</th>
                                                    <th>Type</th>
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
        $('#branch_id').select2({
            width: '100%',
            allowClear: true,
        });
        $(document).on("click", "#select_all_branch", function() {
            makeDisableForBranchSelectAll();
        })
        function makeDisableForBranchSelectAll() {
            if ($("#select_all_branch").is(':checked')) {
                $("#branch_id").val(null).trigger("change");
                $('#branch_id').attr("disabled", true);
            } else {
                $('#branch_id').attr("disabled", false);
            }
        }
        function CheckWinningChanceValidate(status) {
            if (status) {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.can_not_add_prize_item_after_winning_chance') }}",
                    showCancelButton: true,
                    cancelButtonText: "{{ __('message.cancel') }}",
                    confirmButtonText: "{{ __('message.ok') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var promotion_uuid = $('#promotion_uuid').val();
                        var sub_promotion_uuid = $('#sub_promotion_uuid').val();
                        var url = `/view_winning_chance/${promotion_uuid}/${sub_promotion_uuid}`;
                        window.location = url;
                    } else {
                        return false;
                    }
                });
            } else {
                var promotion_uuid = $('#promotion_uuid').val();
                var sub_promotion_uuid = $('#sub_promotion_uuid').val();
                var url = `/view_winning_chance/${promotion_uuid}/${sub_promotion_uuid}`;
                window.location = url;
            }
        }
        $(document).ready(function() {
            $('#presents').hide();
            $(document).on("click", "#chance_type1", function() {
                $('#cash_coupons').show();
                $('#presents').hide();
            })
            $(document).on("click", "#chance_type2", function() {
                $('#cash_coupons').hide();
                $('#presents').show();
            })
            $(document).on("click", "#cash_coupon_name", function() {
                if ((this.value) == 'other') {
                    $(this).replaceWith($('<input/>', {
                        'type': 'text',
                        'name': 'cash_coupon_name',
                        'class': 'form-control'
                    }));
                }
            });
            $(document).on("click", "#present_name", function() {
                if ((this.value) == 'other') {
                    $(this).replaceWith($('<input/>', {
                        'type': 'text',
                        'name': 'present_name',
                        'class': 'form-control'
                    }));
                }
            });

            var check_product_table = $('#prize_check_item_list').DataTable({
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
                    'url': "/prize_check_item_list",
                    'type': 'GET',
                    'data': function(d) {
                        d.uuid = $('uuid').val();
                        d.sub_promotion_uuid = $('#sub_promotion_uuid').val();
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
                        data: 'type',
                        name: 'type',
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
                            return ` <div class="d-flex align-items-center list-action" style="text-align:center">
                                    <a class="badge bg-success mr-2" title="Edit" href="../../edit_prize_check/${row.uuid}/2"><i class="ri-pencil-line mr-0"></i></a>

                                    <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>`
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
                            url: '../../prize_item_destory/' + uuid,
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
