<section data-id="information" class="animated-section">

    <div class="section-content">
        <div class="page-title">
            <h2>{{ __('new_promotion.infomation') }}</h2>
        </div>
        <p> {{ __('new_promotion.infomation_description') }}</p>
        {{-- <form > --}}
        <div class="col-12 row">
            <div class="col-12 col-md-6 mb-3">
                <div class="timeline-item clearfix">
                    <label class="form-label">{{ __('new_layout.phone_no') }} <span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <div class="d-flex">
                            <input class="keyboardType1 jQKeyboard" type="text" id="phone_no" name="phone_no" required
                            value="{{ isset($customer) ? $customer->phone_no : '' }}" style="width: 280px;">
                            <button type="button" class="btn btn-info m-2 phone_no"><i class="fas fa-search"></i></button>
                            {{-- <button class="btn btn-sm"></button> --}}
                        </div>

                        <small id="phone_no_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="timeline-item clearfix">
                    <label class="form-label">{{ __('new_layout.name') }} <span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <input class="keyboardType2 jQKeyboard" type="text" id="firstname" name="firstname"
                            required="required" value="{{ isset($customer) ? $customer->firstname : '' }}"
                            style="width: 320px;">
                        <small id="firstname_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="timeline-item clearfix">
                    <label class="form-label">{{ __('new_layout.customer_no') }} <span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <input type="text" id="customer_no" name="customer_no" required="required"
                            value="{{ isset($customer) ? $customer->customer_no : '' }}" style="width: 320px;" readonly>
                            <small id="customer_no_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="timeline-item clearfix">
                    <label class="form-label">{{ __('new_layout.customer_type') }} <span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <input type="text" id="customer_type" name="customer_type" required="required"
                            value="{{ isset($customer) ? $customer->customer_type : '' }}" style="width: 320px;"
                            readonly>
                        <small id="customer_type_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="timeline-item clearfix">
                    <label class="form-label">{{ __('new_layout.division') }} <span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <select name="province_id" id="province_id" class="custom-form-select shadow select-width">
                            @foreach ($provinces as $province)
                                <option value="{{ $province->province_id }}"
                                    @if (isset($customer)) {{ $province->province_id == $customer->province_id ? 'selected' : '' }} @endif>
                                    {{ $province->province_name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="province_id_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="timeline-item clearfix">
                    <label class="form-label">{{ __('new_layout.township') }} <span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <select name="amphur_id" id="amphur_id" class="custom-form-select shadow select-width">
                            @foreach ($amphurs as $amphur)
                                <option value="{{ $amphur->amphur_id }}"
                                    @if (isset($customer)) {{ $amphur->amphur_id == $customer->amphur_id ? 'selected' : '' }} @endif>
                                    {{ $amphur->amphur_name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="amphur_id_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 d-flex">
            <div class="col-12 col-md-6 form-group form-check">
                <input type="checkbox" name="check_foreigner" class="form-check-input" id="foreigner_id">
                <label class="form-label" for="foreigner_id">Foreigner</label>
            </div>
            <div class="col-12 col-md-6 form-group">
                <label class="form-label" for="foreigner_id">Passport No</label>
                <input type="text" value="{{ $customer->nrc_number }}" id="number" name="number" disabled>

            </div>

        </div>
        

        <div class="col-12 d-flex">
            <label class="form-label">NRC <span class="text-red-500 text-bold h4">*</span></label>
        </div>
        <div class="col-12 row mb-3">

            <div class="col-md-6">

                <div class="row">

                    <div class="col-md-3">

                        <select name="state_id" id="state_id" class="custom-form-select shadow">
                            @for ($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}" @if (isset($customer)) {{ $i == $customer->nrc_no ? 'selected' : '' }} @endif>
                                    {{ $i }}/</option>
                            @endfor
                        </select>
                        <small id="state_id_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                    <div class="col-md-8">
                        <select name="region" id="region" class="custom-form-select" style="width:230px;">
                            <option value="{{ $customer->nrc_name }}">{{ $customer->nrc_name }}</option>
                            {{-- <option value="">Select Region</option> --}}
                            {{-- @foreach ($data as $item) --}}
                            {{-- <option value="{{ $item->district }}"  @if (isset($customer)) {{ $item->district == $customer->nrc_name ? 'selected' : '' }} @endif>{{ $customer->nrc_name }} </option> --}}
                            {{-- @endforeach --}}
                        </select>
                        <small id="region_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 ">
                <div class="row">
                    <div class="col-md-3">
                        <select name="nrc_type" id="nrc_type" class="custom-form-select shadow">
                            <option value="(N)">(N)</option>
                            <option value="(E)">(E)</option>
                            <option value="(P)">(P)</option>
                            <option value="(A)">(A)</option>
                            <option value="(F)">(F)</option>
                            <option value="(TH)">(TH)</option>
                            <option value="(G)">(G)</option>
                        </select>
                        <small id="nrc_type_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>

                    <div class="col-md-8">
                        <input type="text" value="{{ $customer->nrc_number }}" id="number" name="number" >
                        {{-- <input type="text" id="number" name="number" required="required" value="{{ $customer->nrc_number }}"
                            class="form-control" /> --}}
                            <small id="number_div" class="form-text text-red-500 font-weight-500"></small>
                    </div>
                </div>
            </div>
        </div>



    </div>
</section>
<script>

    $(document).ready(function() {

        $(document).on("click", ".customer_update", function()
        {

            var token                   = $("meta[name='csrf-token']").attr("content");
            var ticket_header_uuid      = $('#ticket_header_uuid').val();
            var phone_no                = $('#phone_no').val();
            var firstname               = $('#firstname').val();
            var customer_no             = $('#customer_no').val();
            var customer_type           = $('#customer_type').val();
            var province_id             = $('#province_id').val();
            var amphur_id               = $('#amphur_id').val();
            var state_id                = $('#state_id').val();
            var region                  = $('#region').val();
            var nrc_type                = $('#nrc_type').val();
            var number                  = $('#number').val();
            if($('#foreigner_id').is(":checked"))
            {
                var check_foreigner     ='on';
            }
            else{
                {
                var check_foreigner     ='off';
            }
            }




            if (phone_no != null & firstname != null) {
            $.ajax({
                url: '../../update_customer_info',
                type: 'post',
                data: {
                    "_token": token,
                    "ticket_header_uuid": ticket_header_uuid,
                    "phone_no": phone_no,
                    "firstname": firstname,
                    "customer_no": customer_no,
                    "customer_type": customer_type,
                    "province_id": province_id,
                    "amphur_id": amphur_id,
                    "state_id"  : state_id,
                    "region"    : region,
                    "nrc_type"  : nrc_type,
                    "number"    : number,
                    "check_foreigner"   : check_foreigner
                },
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {
                    jQuery("#loading").hide();
                },
                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: "{{ __('message.success') }}",
                        text: `{{ __('message.updated') }}`,
                        timer: 1000
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: "{{ __('message.warning') }}",
                        text: "{{ __('message.customer_validation') }}",
                        confirmButtonText: "{{ __('message.ok') }}"
                    }).then((result) => {
                if (result.isConfirmed) {
                    console.log('hi info');
                    var goToInfo = `/create_ticket/${ticket_header_uuid}#information`;
                    window.location.href = goToInfo;
                }});
                }
            });
            }

        })
        var time = 0;
        var ntime = 0;
        $(document).on("click", "#phone_no", function() {
            $('.onScreen').hide();
            $('#firstname').removeClass("focus");
            $('input.keyboardType1').initKeypad({
                'keyboardType': '1'
            });
            if (time == 1) {
                $('.onScreen').show();
                $('#phone_no').addClass("focus");
                time = 0;
            } else {
                $('#phone_no').removeClass("focus");
                time++;
            }
        })
        $(document).on("click", "#firstname", function() {
            $('.onScreen').hide();
            $('#phone_no').removeClass("focus");
            $('input.keyboardType2').initKeypad({
                'keyboardType': '2'
            });
            if (ntime == 1) {
                $('.onScreen').show();
                $('#firstname').addClass("focus");
                ntime = 0;
            } else {
                ntime++;
                $('#firstname').removeClass("focus");
            }
        })
        $(document).on("click", ".phone_no", function() {

            var phone_no = $('#phone_no').val();
            var branch_id = $('#branch_id').val();
            var ticket_header_printed_at = $('#ticket_header_printed_at').val();
            if (phone_no && !ticket_header_printed_at) {
                $.ajax({
                    url: '../../get_customer_by_phone_no/' + branch_id + '/' + phone_no,
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
                        console.log(response.data);
                        if (response.data != null) {
                            $('#firstname').val('');
                            $('#firstname').val(response.data.firstname);

                            $('#customer_type').val('');
                            $('#customer_type').val(response.data.customer_type);
                            $('#customer_no').val('');
                            $('#customer_no').val(response.data.customer_no);
                            $('#state_id').val('');
                            $('#state_id').val(response.data.nrc_no);
                            $('#region').val('');
                            $('#region').val(response.data.nrc_name);
                            $('#nrc_type').val('');
                            $('#nrc_type').val(response.data.nrc_short);
                            $('#number').val('');
                            $('#number').val(response.data.nrc_number);

                            $('#province_id').val('');
                            $('#province_id').val(response.data.province_id);
                            $('#amphur_id').val('');
                            $('#amphur_id').val(response.data.amphur_id).trigger("change");

                        } else {
                            $('#firstname').val('');
                            $('#customer_type').val('');
                            $('#customer_no').val('9999');
                            $('#province_id').val('');
                            $('#amphur_id').val('');
                            $('#customer_type').val('New');
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: "{{ __('message.customer_not_found') }}",
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                        }
                        $(".onScreen").html("");
                        $('#phone_no').removeClass("focus");
                    },
                    error: function() {
                        $('#product_code_no').addClass('is-invalid');
                        $('#product_code_noFeedback').removeClass("d-none");
                        $('#product_name').val("");
                        $('#product_unit').val("");
                        $('#stock_quantity').val("");
                        $('#operation_remark').val("");
                        $(".onScreen").html("");
                        $('#phone_no').removeClass("focus");
                    }
                });

            }
        });

        $('#firstname').focusout(function() {

        });

        $(document).on('change', "#province_id", function(e) {
            e.preventDefault();
            $("#customer_township option").remove();
            var province_id = $('#province_id').val();
            var token = $("meta[name='csrf-token']").attr("content");
            if (province_id) {
                $.ajax({
                    url: '../../customer_township_by_customer_division',
                    type: 'get',
                    data: {
                        "_token": token,
                        "province_id": province_id,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {


                        $("#amphur_id").empty();
                        $.each(response, function(k, v) {
                            $('#amphur_id').append($('<option>', {
                                value: k,
                                text: v,
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
    })
</script>

<script>
    $(document).ready(function() {

        var firstname           = $('#firstname').val();
        var customer_no         = $('#customer_no').val();
        var customer_type       = $('#customer_type').val();
        var province_id         = $('#province_id').val();
        var amphur_id           = $('#amphur_id').val();
        var phone_no            = $('#phone_no').val();
        var state_id            = $('#state_id').val();
        var region              = $('#region').val();
        var nrc_type            = $('#nrc_type').val();
        var number              = $('#number').val();


        function validateInput(inputField,div,msg)
        {
            $(inputField).blur(function(e) {
            e.preventDefault();
            var ph = $(this).val();
                console.log(ph);
            if ($(this).val() == '' || $(this).val() == 0) {
                validate_msg(div,msg);
                $('#next_button').hide();
            }
            else
            {
                $('#next_button').show();
                validate_msg(div,'');
            }
        });
        }

        if(!$('#foreigner_id').is(":checked"))
        {
            console.log('true');
            if ($('#region').val() == 0) {
                validate_msg('#region_div','Please choose region');
                $('#next_button').hide();
            }
            else
            {
                $('#next_button').show();
                validate_msg('#region_div','');
            }
            if ($('#number').val() == 0) {
                validate_msg('#number_div','Please enter nrc number');
                $('#next_button').hide();
            }
            else
            {
                $('#next_button').show();
                validate_msg('#number_div','');
            }
        }

        $('#foreigner_id').change(function (e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $("#state_id").prop("disabled", true);
                $("#region").prop("disabled", true);
                $("#nrc_type").prop("disabled", true);
                $("#number").prop("disabled", true);
                $('#state_div').html('');
                $('#region_div').html('');
                $('#number_div').html('');

            } else {
                $("#state_id").prop("disabled", false);
                $("#region").prop("disabled", false);
                $("#nrc_type").prop("disabled", false);
                $("#number").prop("disabled", false);

            }
        });

        validateInput('#phone_no','#phone_no_div','Please Enter Phone No');
        validateInput('#firstname','#firstname_div','Please Enter First Name');
        validateInput('#customer_no','#customer_no_div','Please Enter Customer No');
        validateInput('#customer_type','#customer_type_div','Please Enter Customer Type');

        validateInput('#province_id','#province_id_div','Please Enter Province');
        validateInput('#amphur_id','#amphur_id_div','Please Enter Amphur');
        validateInput('#state_id','#state_id_div','Please Enter state');
        validateInput('#region','#region_div','Please Enter region');
        validateInput('#nrc_type','#nrc_type_div','Please Enter NRC Type');
        validateInput('#number','#number_div','Please Enter NRC Numbers');

        if(firstname=='')
        {
            validate_msg('#firstname_div','Please Enter First Name');
                $('#next_button').hide();
        }
        else
        {
            $('#next_button').show();
            validate_msg('#firstname_div','');
        }

        if(phone_no=='')
        {
            validate_msg('#phone_no_div','Please Enter Phone No');
                $('#next_button').hide();
        }else
        {
            $('#next_button').show();
            validate_msg('#phone_no_div','');
        }

        if(customer_no=='')
        {
            validate_msg('#customer_no_div','Please Enter Customer No');
                $('#next_button').hide();
        }else
        {
            $('#next_button').show();
            validate_msg('#customer_no_div','');
        }

        if(customer_type=='')
        {
            validate_msg('#customer_type_div','Please Enter Customer Type');
                $('#next_button').hide();
        }else
        {
            $('#next_button').show();
            validate_msg('#customer_type_div','');
        }

        if(province_id=='')
        {
            validate_msg('#province_id_div','Please Select State or Division');
                $('#next_button').hide();
        }else
        {
            $('#next_button').show();
            validate_msg('#province_id_div','');
        }

        if(amphur_id=='')
        {
            validate_msg('#amphur_id_div','Please Select Township');
                $('#next_button').hide();
        }else
        {
            $('#next_button').show();
            validate_msg('#amphur_id_div','');
        }


        function validate_msg(div,message)
        {
            $(div).html('');
            $(div).append(message);
        }

        function getNRCInfo(province_id, amphur_id)
        {
            $.ajax({
                type: "GET",
                url: "/get_nrc_info",
                data: {
                    'province_id': province_id,
                    'amphur_id': amphur_id
                },
                dataType: "JSON",
                success: function(response) {
                    $("#region").empty();
                    console.log(response);
                    $.each(response, function(key, value) {

                        $option = `<option value="` + value.district + `">` + value.district + `</option>`;
                        $('#region').append($option);
                    });
                }
            });
        }

        $('#amphur_id').on('change',function() {

            $province_id = $('#province_id').val();
            $amphur_id = $('#amphur_id').val();

            getNRCInfo($province_id,$amphur_id);

        });

        $('#state_id').on('change',function() {

            $province_id = $(this).val();
            console.log($province_id);
            $amphur_id = $('#amphur_id').val();

            getNRCInfo($province_id,$amphur_id);

        });



    });
</script>
