@extends('create_tickets.layout')

@section('content')
    <div class="main-area row col-md-9">
        <form action="{{ route('tickets.update_customer_info') }}" method="POST" enctype="multipart/form-data" id="customer_info_form">
            @csrf
            <div class="column col-md-12">
                <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{ isset($ticket_header->uuid) ? $ticket_header->uuid : '' }}">
                <input type="hidden" name="branch_id" id="branch_id" value="{{ isset($ticket_header->branch_id) ? $ticket_header->branch_id : '' }}">
                <input type="hidden" id="customer_uuid" name="customer_uuid" value="{{ isset($customer_info->uuid) ? $customer_info->uuid : '' }}" />
                <button type="button" class="previous" id="previous_to_collect_invoice">{{__('new_promotion.previous')}}</button>
                <button type="button" id="go_to_choose_promotion">{{__('new_promotion.next')}}</button>
                <h4 class="card-second-title">{{__('new_promotion.check_customer_info')}} :</h4>

                <div class="row">
                    <div class="column">
                        <h4 class="card-label card-label-small">{{__('new_promotion.customer_phone_no')}} *</h4>
                        <input name="phone_no" id="phone_no" type="text" class="card-input card-input-phone" value="{{ $customer_info->phone_no }}" required>
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.phone_no_2')}}</h4>
                        <input class="card-input card-input-phone" style="margin-left: 20px;" type="text"
                            id="phone_no_2" name="phone_no_2" value="{{ $customer_info->phone_no_2 }}" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="column">
                        <h4 class="card-label card-label-small">{{__('new_promotion.title')}}</h4>
                        <input class="card-input card-input-one" id="titlename" name="titlename"
                            value="{{ $customer_info->titlename }}" />
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.first_name')}} *</h4>
                        <input class="card-input card-input-two" style="margin-left: 20px;" id="firstname"
                            name="firstname" value="{{ $customer_info->firstname }}" />
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.last_name')}} *</h4>
                        <input class="card-input card-input-two" style="margin-left: 20px;" id="lastname"
                            name="lastname" value="{{ $customer_info->lastname }}" />
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <h4 class="card-label card-label-small">{{__('new_promotion.nrc_no')}}</h4>
                        <select class="card-input card-input-one" name="nrc_no" id="nrc_no"
                            class="form-control">
                            <option value = ""> Select No </option>
                            @foreach($nrc_nos as $nrc_no)
                            <option class="card-input card-input-one" value="{{ $nrc_no->id }}"
                                @if(isset($customer_info)) {{$nrc_no->id == $customer_info->nrc_no ?
                                'selected' : ''}} @endif>
                                {{ $nrc_no->nrc_number_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="column">
                        <select style="margin-top: 34px !important;margin-left: 20px;" class="card-input card-input-one" name="nrc_name"
                            id="nrc_name" class="form-control"
                            >
                            <option value = ""> Select Name </option>
                            @foreach($nrc_names as $nrc_name)
                            <option value="{{ $nrc_name->id}}" @if(isset($customer_info)) {{$nrc_name->id == $customer_info->nrc_name ?
                                'selected' : '' }} @endif>
                                {{ $nrc_name->district }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="column">

                        <select class="card-input card-input-one" style="margin-top: 34px !important;margin-left: 20px;"
                            name="nrc_short" id="nrc_short" class="form-control">
                            <option value = ""> Select Short </option>
                            @foreach($nrc_naings as $nrc_naing)
                            <option value="{{ $nrc_naing->id}}" @if(isset($customer)) {{$nrc_naing->id == $customer->nrc_short ?
                                'selected' : '' }} @endif>
                                {{ $nrc_naing->shortname }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="column">

                        <input class="card-input card-input-two" style="margin-top: 34px !important;margin-left: 20px;" id="nrc_number"
                            name="nrc_number" value="{{ $customer_info->nrc_number }}" />
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <h4 class="card-label card-label-small">{{__('new_promotion.customer_type')}}</h4>
                        <input class="card-input card-input-one" id="customer_type" name="customer_type"
                            value="{{ $customer_info->customer_type }}" />
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.customer_no')}}</h4>
                        <input class="card-input card-input-two" style="margin-left: 20px;" id="customer_no"
                            name="customer_no" value="{{ $customer_info->customer_no }}" />
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <h4 class="card-label card-label-small">{{__('new_promotion.customer_passport')}}</h4>
                        <input id="passport" name="passport" class="card-input card-input-two" />
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.customer_email')}}</h4>
                        <input class="card-input card-input-two" style="margin-left: 20px;" id="email"
                            name="email" value="{{ $customer_info->email }}" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="column">
                        <h4 class="card-label card-label-small">{{__('new_promotion.division')}}</h4>
                        <select class="card-input card-input-oneplus" name="customer_division"
                            id="customer_division" class="form-control">
                            <option value = ""> Select Division </option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->province_id}}" @if(isset($customer_info))
                                {{$province->province_id == $customer_info->province_id ? 'selected' : '' }}
                                @endif>
                                {{ $province->province_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.township')}}</h4>
                        <select style="margin-left: 20px;" class="card-input card-input-one"
                            name="customer_township" id="customer_township" class="form-control">
                            <option value = ""> Select Township </option>
                            @foreach($amphurs as $amphur)
                            <option value="{{ $amphur->amphur_id}}" @if(isset($customer)) {{$amphur->amphur_id == $customer->amphur_id ?
                                'selected' : '' }} @endif>
                                {{ $amphur->amphur_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="column">
                        <h4 class="card-label card-label-small" style="margin-left: 25px;">{{__('new_promotion.address')}}</h4>
                        <input class="card-input card-input-two" style="margin-left: 20px;"
                            value="{{ $customer_info->address }}" />
                    </div>
                </div>
            </div>

            </div>
        </form>
    </div>
@endsection
@section('js')
    <script>
        // $("#customer_township").select2({
        //     width: '100%',
        //     allowClear: true,
        // });
        $('#previous_to_collect_invoice').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.edit_collect_invoice', $ticket_header->uuid ?? '') }}";
            document.location.href = url;
        })
        $('#go_to_choose_promotion').on('click', function(e) {
            jQuery("#loading").show();
            $status = validateForm();
            if($status){
                $('#customer_info_form').submit();
            }
        })
        $('#choose_promotion').on('click', function(e) {
            $status = validateForm()
            if($status){
                $('#customer_info_form').submit();
            }
        })
        function validateForm() {
            if ($('#phone_no').val() == "") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.need_customer_phone_no') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
            if ($('#phone_no').val() == "09777777777") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.use_correct_phone_no') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
            if ($('#firstname').val() == "Cash Customer") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.use_correct_first_name') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
            if ($('#firstname').val() == "") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.need_customer_first_name') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
            return true;
        }
        $('#phone_no').focusout(function() {
            var phone_no = $(this).val();
            var branch_id = $('#branch_id').val();
            var ticket_header_printed_at = $('#ticket_header_printed_at').val();
            if (phone_no && !ticket_header_printed_at) {
                $.ajax({
                    url: '../../../customers/get_customer_by_phone_no/' + branch_id + '/' + phone_no,
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
                                $('#customer_id').val('');
                                $('#customer_id').val(response.data.customer_id);
                                $('#phone_no2').val('');
                                $('#phone_no2').val(response.data.phone_no_2);
                                $('#titlename').val('');
                                $('#titlename').val(response.data.titlename);
                                $('#firstname').val('');
                                $('#firstname').val(response.data.firstname);
                                $('#lastname').val('');
                                $('#lastname').val(response.data.lastname);
                                $('#customer_type').val('');
                                $('#customer_type').val(response.data.customer_type);
                                $('#customer_no').val('');
                                $('#customer_no').val(response.data.customer_no);
                                $('#nrc_no').val('');
                                $('#nrc_no').val(response.data.nrc_no);
                                $('#nrc_name').val('');
                                $('#nrc_name').val(response.data.nrc_name).trigger("change");
                                $('#nrc_short').val('');
                                $('#nrc_short').val(response.data.nrc_short);
                                $('#nrc_number').val('');
                                $('#nrc_number').val(response.data.nrc_number);
                                $('#email').val('');
                                $('#email').val(response.data.email);
                                $('#passport').val('');
                                $('#passport').val(response.data.passport);
                                $('#customer_division').val('');
                                $('#customer_division').val(response.data.province_id);
                                $('#customer_township').val('');
                                $('#customer_township').val(response.data.amphur_id).trigger("change");
                                $('#customer_address').val('');
                                $('#customer_address').val(response.data.full_address);


                        } else {
                            $('#customer_id').val('');
                            $('#phone_no2').val('');
                            $('#titlename').val('Mr.');
                            $('#firstname').val('');
                            $('#lastname').val('');
                            $('#customer_type').val('');
                            $('#customer_no').val('');
                            $('#nrc_no').val('');
                            $('#nrc_name').val('');
                            $('#nrc_short').val('');
                            $('#nrc_number').val('');
                            $('#email').val('');
                            $('#passport').val('');
                            $('#customer_division').val('');
                            $('#customer_township').val('');
                            $('#customer_address').val('');
                            $('#customer_type').val('New');
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: "{{ __('message.customer_not_found') }}",
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
        });
        ///NRC N
$(document).on('change', "#nrc_no", function() {
    $("#nrc_name option").remove();
    var nrc_no = $('#nrc_no').val();
    var token = $("meta[name='csrf-token']").attr("content");
    if (nrc_no) {
        $.ajax({
            url: '../../nrc_name_by_nrc_no',
            type: 'get',
            data: {
                "_token": token,
                "nrc_no": nrc_no,
            },
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                $("#nrc_name").empty();
                $.each(response, function(k, v) {
                    $('#nrc_name').append($('<option>', {
                        value: k,
                        text: v
                    }));
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
////City///
        $(document).on('change', "#customer_division", function() {
            $("#customer_township option").remove();
            var customer_division = $('#customer_division').val();
            var token = $("meta[name='csrf-token']").attr("content");
            if (customer_division) {
                $.ajax({
                    url: '../../ticket/customer_township_by_customer_division',
                    type: 'get',
                    data: {
                        "_token": token,
                        "customer_division": customer_division,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $("#customer_township").empty();
                        $.each(response, function(k, v) {
                            $('#customer_township').append($('<option>', {
                                value: k,
                                text: v
                            }));
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

    </script>
@endsection
