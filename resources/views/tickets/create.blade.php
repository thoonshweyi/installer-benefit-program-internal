@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('ticket.header_create') }}</h4>
                            </div>
                        </div>
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
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.branch')}} </label>
                                            <select name="branch_id" id="branch_id" class="form-control" required>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->branches->branch_id }}" {{ ($branch->branches->branch_id == old("branch_id")) ? 'selected' : '' }}>
                                                        {{ $branch->branches->branch_name_eng}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.ticket_type')}} </label>
                                            <select name="ticket_type" id="ticket_type" class="form-control" required>
                                                    <option value="1">Normal Ticket</option>
                                                    <option value="2">Special Ticket</option>
                                                    {{-- <option value="3">Deposit Ticket</option> --}}
                                                    <option value="4">Return Ticket</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.lucky_draw')}} </label> <i class="fa fa-info-circle fa-lg exchange_deducted" id="view_promotion_info"></i>
                                            <select name="lucky_draw_uuid" id="lucky_draw_uuid" class="form-control" required>
                                                @foreach($luckydraws as $luckydraw)
                                                    <option value="{{ $luckydraw->uuid }}" {{ ($luckydraw->uuid == old("lucky_draw_uuid")) ? 'selected' : '' }}>
                                                        {{ $luckydraw->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('ticket.invoice_no')}}</label>
                                            <input name="invoice_no" id="invoice_no" type="text" placeholder="Type CA,SA" class="form-control" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary col-md-2" id="add_invoice">{{ __('button.add') }}</button>
                                </div>
                        </div>

                    </div>
                </div>
                <div class="modal fade show_promotion_info" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-l">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="product_modal_title">Promotion Infomation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="product_form">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Name :</label>
                                                <label style="font-weight:bold" id="lucky_draw_name"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Type :</label>
                                                <label style="font-weight:bold" id="lucky_draw_type_name"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Branches :</label>
                                                <label style="font-weight:bold" id="lucky_draw_branches"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Categories :</label>
                                                <label style="font-weight:bold"id="lucky_draw_categories"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Brands :</label>
                                                <label style="font-weight:bold" id="lucky_draw_brands"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Discon Status :</label>
                                                <label style="font-weight:bold" id="discon_status"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Amount For 1 Ticket :</label>
                                                <label style="font-weight:bold" id="lucky_draw_promotion_amount"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Start Date :</label>
                                                <label style="font-weight:bold" id="start_date"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>End Date :</label>
                                                <label style="font-weight:bold" id="end_date"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
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

    $(document).ready(function() {
        var invoice_no = document.getElementById("invoice_no");
        invoice_no.onkeyup = function(e){
            if(e.keyCode == 13){
                var branch_id = $('#branch_id').val();
                var ticket_type = $('#ticket_type').val();
                var lucky_draw_uuid = $('#lucky_draw_uuid').val();
                var invoice_no = $('#invoice_no').val();
                var ticket_header_uuid = $('#ticket_header_uuid').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                var token = $("meta[name='csrf-token']").attr("content");
                if (invoice_no) {
                    $.ajax({
                        url: '../../tickets/new_add_invoice',
                        type: 'post',
                        data: {
                            "_token": token,
                            "branch_id": branch_id,
                            "ticket_type" : ticket_type,
                            "lucky_draw_uuid" : lucky_draw_uuid,
                            "invoice_no": invoice_no,
                            "ticket_header_uuid": ticket_header_uuid,
                        },
                        beforeSend: function() {
                            jQuery("#load").fadeOut();
                            jQuery("#loading").show();
                        },
                        complete: function() {
                            jQuery("#loading").hide();
                        },
                        success: function(response) {
                            if (response.data != null) {
                                if(response.data.old_user_name)
                                {
                                    Swal.fire({
                                            icon: 'warning',
                                            title: `{{ __('message.customer_is_not_same') }}`,
                                            html: "choose_customer" + "<br>" + "<table class='table'><tr><th width='50%'>"+ response.data.old_user_name + "</th><th width='50%'>"+ response.data.new_user_name+"</th></tr><tr><td width='50%'>"+ response.data.old_phone_no +"</td><td width='50%'>"+ response.data.new_phone_no +"</td></tr></table>",
                                            showDenyButton: true,
                                            showCancelButton: false,
                                            confirmButtonText: response.data.old_user_name,
                                            denyButtonText:  response.data.new_user_name,
                                        }).then((result) => {
                                            if (result.isConfirmed) {

                                                Swal.fire(`{{ __('message.choosed_old_customer') }}`, '', 'info').then(function(){
                                                    var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                    window.location = url;
                                                })
                                            } else if (result.isDenied) {
                                                $.ajax({
                                                    url: '../../tickets/update_ticket_header_customer',
                                                    type: 'post',
                                                    data: {
                                                        "_token": token,
                                                        "ticket_header_uuid": ticket_header_uuid,
                                                        "customer_id": response.data.new_customer_id,
                                                        "invoice_id": response.data.invoice_id,
                                                    },
                                                })
                                                Swal.fire(`{{ __('message.choosed_new_customer') }}`, '', 'info').then(function(){
                                                    var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                    window.location = url;
                                                })
                                            }
                                        }
                                    )
                                }else{
                                    message = response.data.message;
                                    Swal.fire({
                                        icon: 'success',
                                        title: "{{ __('message.success') }}",
                                        text: `{{ __('message.successfully_created') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    }).then(function(){
                                        var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                        window.location = url;
                                    });
                                }
                            }
                            else
                            {
                                if(response.error == 'can_not_add_invoice_when_ticket_is_generated')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_add_invoice_when_ticket_is_generated') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_not_found')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_not_found') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_used')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_used') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_expired')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_expired') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'customer_is_not_same')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.customer_is_not_same') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'ticket_header_uuid_error')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.ticket_header_uuid_error') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'can_not_remove_invoice_when_ticket_is_generated')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_remove_invoice_when_ticket_is_generated') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'permission_denied')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.permission_denied') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'promotion_expired')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.promotion_expired') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'promotion_is_not_start')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.promotion_is_not_start') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'accept_adding_only_5_invoices')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.accept_adding_only_5_invoices') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'can_not_use_this_invoice')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_use_this_invoice') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'promotion_image_is_not_uploaded')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.promotion_image_is_not_uploaded') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_used_for_deposit_invoice')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_used_for_deposit_invoice') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'this_invoice_deposit_no_is_used')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.this_invoice_deposit_no_is_used') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'this_invoice_sale_invoice_is_used')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.this_invoice_sale_invoice_is_used') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                            }
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

                }
            }
        }

        $(document).on('click',"#view_promotion_info", function(){
            var lucky_draw_uuid = $('#lucky_draw_uuid').val();
            var token = $("meta[name='csrf-token']").attr("content");
            if(lucky_draw_uuid){
                    $.ajax({
                    url: '../../lucky_draws/'+ lucky_draw_uuid,
                    type: 'get',
                    data: {
                        "_token": token,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#lucky_draw_name').text(response.lucky_draw);
                        $('#lucky_draw_type_name').text(response.lucky_draw_type);
                        $('#lucky_draw_branches').text(response.lucky_draw_branches);
                        $('#lucky_draw_categories').text(response.lucky_draw_categories);
                        $('#lucky_draw_brands').text(response.lucky_draw_brands);
                        $('#discon_status').text(response.lucky_draw_discon);
                        $('#lucky_draw_promotion_amount').text(response.lucky_draw_promotion_amount.toLocaleString());
                        $('#start_date').text(response.lucky_draw_start_date);
                        $('#end_date').text(response.lucky_draw_end_date);
                        $('.show_promotion_info').modal('show');
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
            }
        })

        $(document).on('change',"#ticket_type", function(){
            $("#lucky_draw_uuid option").remove();
            var ticket_type = this.value;
            if(ticket_type == 1){
                $('#invoice_no').attr('placeholder','Type CA,SA');
            }
            else if(ticket_type == 2){
                $('#invoice_no').attr('placeholder','Type CA,SA');
            }else if(ticket_type == 3){
                $('#invoice_no').attr('placeholder','Type RD');
            }else{
                $('#invoice_no').attr('placeholder','Type CA,SA of Return Deposit');
            }
            $("invoice_no").show();
            var token = $("meta[name='csrf-token']").attr("content");
            if (invoice_no) {
                $.ajax({
                    url: '../../lucky_draw_search_by_type',
                    type: 'get',
                    data: {
                        "_token": token,
                        "ticket_type" : ticket_type,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $("#lucky_draw_uuid").empty();
                        $.each( response, function(k, v) {
                            $('#lucky_draw_uuid').append($('<option>', {value:k, text:v}));
                        });
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

            }
        })

        $(document).on("click", "#add_invoice", function() {
            var branch_id = $('#branch_id').val();
            var ticket_type = $('#ticket_type').val();
            var lucky_draw_uuid = $('#lucky_draw_uuid').val();
            var invoice_no = $('#invoice_no').val();
            var ticket_header_uuid = $('#ticket_header_uuid').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            var token = $("meta[name='csrf-token']").attr("content");
            if (invoice_no) {
                $.ajax({
                    url: '../../tickets/new_add_invoice',
                    type: 'post',
                    data: {
                        "_token": token,
                        "branch_id": branch_id,
                        "ticket_type" : ticket_type,
                        "lucky_draw_uuid" : lucky_draw_uuid,
                        "invoice_no": invoice_no,
                        "ticket_header_uuid": ticket_header_uuid,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if(response.data.old_user_name)
                            {
                                Swal.fire({
                                        icon: 'warning',
                                        title: `{{ __('message.customer_is_not_same') }}`,
                                        html: "choose_customer" + "<br>" + "<table class='table'><tr><th width='50%'>"+ response.data.old_user_name + "</th><th width='50%'>"+ response.data.new_user_name+"</th></tr><tr><td width='50%'>"+ response.data.old_phone_no +"</td><td width='50%'>"+ response.data.new_phone_no +"</td></tr></table>",
                                        showDenyButton: true,
                                        showCancelButton: true,
                                        confirmButtonText: response.data.old_user_name,
                                        denyButtonText:  response.data.new_user_name,
                                    }).then((result) => {
                                        if (result.isConfirmed) {

                                            Swal.fire(`{{ __('message.choosed_old_customer') }}`, '', 'info').then(function(){
                                                var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                window.location = url;
                                            })
                                        } else if (result.isDenied) {
                                            $.ajax({
                                                url: '../../tickets/update_ticket_header_customer',
                                                type: 'post',
                                                data: {
                                                    "_token": token,
                                                    "ticket_header_uuid": ticket_header_uuid,
                                                    "customer_id": response.data.new_customer_id,
                                                    "invoice_id": response.data.invoice_id,
                                                },
                                            })
                                            Swal.fire(`{{ __('message.choosed_new_customer') }}`, '', 'info').then(function(){
                                                var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                window.location = url;
                                            })
                                        }
                                    }
                                )
                            }else{
                                message = response.data.message;
                                Swal.fire({
                                    icon: 'success',
                                    title: "{{ __('message.success') }}",
                                    text: `{{ __('message.successfully_created') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                }).then(function(){
                                    var url = `/tickets/collect_invoice/${response.data.ticket_header_uuid}`;
                                    window.location = url;
                                });
                            }
                        }
                        else
                        {
                            if(response.error == 'can_not_add_invoice_when_ticket_is_generated')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_add_invoice_when_ticket_is_generated') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'invoice_is_not_found')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_not_found') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'invoice_is_used')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_used') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'invoice_is_expired')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_expired') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'customer_is_not_same')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.customer_is_not_same') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'ticket_header_uuid_error')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.ticket_header_uuid_error') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'can_not_remove_invoice_when_ticket_is_generated')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_remove_invoice_when_ticket_is_generated') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'permission_denied')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.permission_denied') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'promotion_expired')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_expired') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'promotion_is_not_start')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_is_not_start') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'can_not_use_this_invoice')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_use_this_invoice') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                            if(response.error == 'promotion_image_is_not_uploaded')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_image_is_not_uploaded') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'invoice_is_used_for_deposit_invoice')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_used_for_deposit_invoice') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                        }
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

            }
        })
    })
</script>
@endsection
