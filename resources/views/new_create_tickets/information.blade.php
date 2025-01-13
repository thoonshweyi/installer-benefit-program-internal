<section data-id="information" class="animated-section">

    <div class="section-content">
        <div class="page-title">
            <h2>{{ __('new_promotion.infomation') }}</h2>
        </div>
        <p> {{ __('new_promotion.infomation_description') }}</p>

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
                            <option value="0">Select State or Division</option>
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
                            <option value=""></option>
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
            @if($customer->foreigner==false)
            <div class="col-12 col-md-6 mb-3 full_nrc_div" >
                <div class="timeline-item clearfix">
                    <label class="form-label">NRC<span
                            class="text-red-500 text-bold h4">*</span></label>
                    <div class="">
                        <input class="keyboardType2 jQKeyboard" type="text" id="full_nrc" name="full_nrc"
                            required="required" value="{{ isset($customer) ? $customer->nrc : '' }}"
                            style="width: 320px;" disabled>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-12 row">
            {{-- <div class="col-md-6"> --}}

                <div class="col-md-6">
                    <div class="pl-4">
                        <input type="checkbox" name="check_foreigner" class="form-check-input" id="foreigner_id" {{$customer->foreigner==true?'checked':''}} @if($customer->customer_type!=='New') onclick="return false;" @endif>
                    <label class="form-label" for="foreigner_id">Foreigner</label>
                    </div>

                </div>

                <div class="col-md-6 passport_div" @if($customer->foreigner==true) style="display:none" @endif>
                    <label class="form-label" for="passport">Passport No</label>
                    <input type="text" name="passport" class="form-input col-11" id="passport" value="{{$customer->passport}}" disabled>

                </div>

        </div>


        <div class="nrc_div" @if($customer->foreigner==false && $customer->customer_type!='New') style="display:none;" @endif >

            <div class="col-12 d-flex">
                <label class="form-label">NRC <span class="text-red-500 text-bold h4">*</span></label>
            </div>
            <div class="col-12 row mb-3">

                <div class="col-md-6">

                    <div class="row">

                        <div class="col-md-3">

                            <select name="state_id" id="state_id" class="custom-form-select shadow">
                                @for ($i = 1; $i <= 14; $i++)                                   
                                        <option value="{{$i}}" {{ ( $gbh_customer !=null && $i==$gbh_customer->nrc_no) ? 'selected' : ''}}>{{$i}}/</option>
                                @endfor
                            </select>
                            <small id="state_id_div" class="form-text text-red-500 font-weight-500"></small>
                        </div>
                        <div class="col-md-8">
                            <select name="region" id="region" class="custom-form-select" style="width:230px;">
                                @foreach ($data as $item)
                                <option value="{{ $item->id }}" {{ ( $gbh_customer !=null && $item->id==$gbh_customer->nrc_name) ? 'selected' : ''}}>{{ $item->district }}</option>
                                @endforeach                              
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
                            <input type="text" value="{{ $customer->nrc_number? $customer->nrc_numer:($gbh_customer ? $gbh_customer->nrc_number :'') }}" id="number" name="number" >                           
                            <small id="number_div" class="form-text text-red-500 font-weight-500"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>
<script>
    $(document).ready(function () {
        toggleNrcDivVisibility();
        $(document).on("click", ".customer_update",function ()
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
            var full_nrc                = $('#full_nrc').val();
            var passport                = $('#passport').val();
            // var
            if($('#foreigner_id').is(":checked"))
            {
                var check_foreigner     =true;
            }
            else{
                {
                var check_foreigner     =false;
            }
            }
            // alert(check_foreigner==false);

            if(firstname=='')
            {
                validate_msg('#firstname_div','Please Enter First Name');
            }
            else
            {
                validate_msg('#firstname_div','');
            }

            if(phone_no=='')
            {
                validate_msg('#phone_no_div','Please Enter Phone No');

            }else
            {
                validate_msg('#phone_no_div','');
            }

            function validate_msg(div,message)
            {
                $(div).html('');
                $(div).append(message);
            }
            if (phone_no != null & firstname != null)
            {
                $.ajax({
                    url: '../../update_customer_info',
                    type: 'post',
                    data: {
                        "_token"                : token,
                        "ticket_header_uuid"    : ticket_header_uuid,
                        "phone_no"              : phone_no,
                        "firstname"             : firstname,
                        "customer_no"           : customer_no,
                        "customer_type"         : customer_type,
                        "province_id"           : province_id,
                        "amphur_id"             : amphur_id,
                        "state_id"              : state_id,
                        "region"                : region,
                        "nrc_type"              : nrc_type,
                        "number"                : number,
                        "check_foreigner"       : check_foreigner,
                        "full_nrc"              : full_nrc,
                        "passport"              : passport
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        if(response.nrc!=null)
                        {
                            $('#full_nrc').val(response.nrc);
                        }
                        if(response.passport!=null)
                        {
                            $('#passport').val(response.passport);
                        }

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


        });
        $(document).on('click',".phone_no", function ()
        {
            var phone_no                    = $('#phone_no').val();
            var branch_id                   = $('#branch_id').val();
            var ticket_header_printed_at    = $('#ticket_header_printed_at').val();
            if(phone_no && ticket_header_printed_at==null)
            {
                $.ajax({
                    type: "GET",
                    url: '../../get_customer_by_phone_no/' + branch_id + '/' + phone_no,
                    dataType: "json",
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function (response) {
                        if(response.data !=null)
                        {
                            $('#firstname').val('');
                            $('#firstname').val(response.data.firstname);

                            $('#customer_type').val('');
                            $('#customer_type').val(response.data.customer_type);

                            $('#customer_no').val('');
                            $('#customer_no').val(response.data.customer_no);

                            $('#state_id').val('');
                            $('#state_id').val(response.data.nrc_no);

                            $('#region').val('');
                            $('#region').val(response.data.nrc_name).trigger("change");;

                            $('#nrc_type').val('');
                            $('#nrc_type').val(response.data.nrc_short);

                            $('#number').val('');
                            $('#number').val(response.data.nrc_number);

                            $('#province_id').val('');
                            $('#province_id').val(response.data.province_id);

                            $('#amphur_id').val('');
                            $('#amphur_id').val(response.data.amphur_id).trigger("change");

                            $('#full_nrc').val('');
                            $('#full_nrc').val(response.data.nrc);

                            $('#passport').val('');
                            $('#passport').val(response.data.passport);

                            $('#foreigner_id').val('');
                            $('#foreigner_id').val(response.data.foreigner_id);
                        }
                        else
                        {
                            $('#firstname').val('');
                            $('#customer_type').val('');
                            $('#customer_no').val('9999');
                            $('#province_id').val('');
                            $('#amphur_id').val('');
                            $('#customer_type').val('New');
                            $('#foreigner_id').prop('checked', false);
                            $('#passport').val('');
                            $('.passport_div').addClass('d-none');
                            $('.nrc_div').addClass('d-block');
                            $("#foreigner_id").removeAttr("onclick");

                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: "{{ __('message.customer_not_found') }}",
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                        }
                    }
                });
            }
        });
        $('#foreigner_id').change(function (e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $("#state_id").prop("disabled", true);
                $("#region").prop("disabled", true);
                $("#nrc_type").prop("disabled", true);
                $("#number").prop("disabled", true);

                // $('#state_id').html('');
                // $('#region').html('');
                // $('#nrc_type').html('');
                // $('#number').val('');

                toggleNrcDivVisibility();
                $("#passport").prop("disabled", false);


            } else {
                toggleNrcDivVisibility();
                $("#state_id").prop("disabled", false);
                $("#region").prop("disabled", false);
                $("#nrc_type").prop("disabled", false);
                $("#number").prop("disabled", false);

                $("#passport").prop("disabled", true);

            }
        });
        function toggleNrcDivVisibility()
        {
            var isChecked = $('#foreigner_id').is(':checked');
            var nrcDiv = $('.nrc_div');

            // Toggle visibility of nrc_div based on checkbox state
            if (!isChecked) {
                nrcDiv.hide();
                console.log('true')
                $('.full_nrc_div').show();
                $('.passport_div').hide();
            } else {
                console.log('false')
                nrcDiv.show();
                $('.full_nrc_div').hide();
                $('.passport_div').show();
            }
        }

        $('#state_id').on('change',function()
        {
            $province_id = $(this).val();
            console.log($province_id);
            $amphur_id = $('#amphur_id').val();

            getNRCInfo($province_id,$amphur_id);
        });

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
                    console.log(response,'hi');
                    $.each(response, function(key, value) {

                        $option = `<option value="` + value.id + `">` + value.district + `</option>`;
                        console.log($option,'hi');
                        $('#region').append($option);
                    });
                }
            });
        }

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

    });
</script>

