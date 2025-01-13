@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Create Installer Card</h4>
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


                        <div class="col-md-12 my-4">
                            {{-- <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
                              </div> --}}

                            <div class="position-relative" style="top:-5px">
                                <span class="register-steps" style="position: absolute;left: 25%;transform:translateX(-50%)">1</span>
                                <span class="register-steps" style="position: absolute;left: 50%;transform:translateX(-50%)">2</span>
                                <span class="register-steps" style="position: absolute;left: 75%;transform:translateX(-50%)">3</span>
                                <span class="register-steps" style="position: absolute;left: 100%;transform:translateX(-50%)">4</span>
                            </div>
                            <div class="progress mb-3" style="height: 20px">
                                <div id="register-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <h1 class="text-primary">Setp 1: Verify Phone Number</h1>
                        <form id="verify-form" action="" method="">
                            <div class="row align-items-center">
                                <div class="col-md-3 form-group mb-3">
                                    <label for="ver_phone">Customer Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" name="ver_phone" id="ver_phone" class="form-control rounded-0" value="{{ old('hide_ver_phone') }}" placeholder="09xxxxxxxxx"/>
                                </div>

                                <div class="col-auto">
                                    <button type="button" id="verify-btn" class="btn btn-primary px-3 py-1">Verify</button>
                                </div>

                                {{-- <div class="col-auto text-end">
                                    <button type="button" id="verify-btn" class="btn btn-primary btn-sm px-3 py-1" >Verify</button>
                                </div> --}}
                            </div>
                        </form>
                    <form id="register-installer-card-form"  action="{{ route('installercards.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            {{-- <h5>Automatic Fields:</h5> --}}
                            <h1 class="text-primary">Setp 2: Check Installer Information</h1>
                            <input type="hidden" id="hide_ver_phone" name="hide_ver_phone" value="{{old('hide_ver_phone')}}"/>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group poscustomer {{ (old('identification_card') != null && old('member_active') && old('customer_active') && old('customer_rank_id') == 1013) ? 'member' : '' }}">
                                        <label for="fullname">Full Name<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="fullname" id="fullname" class="form-control" value="{{ old('fullname') }}" placeholder="Full Name" readonly/>

                                        <div class="crownicon">
                                            <img src="{{ asset('./images/crown.png') }}" alt="crownicon" width="44" height="44">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone">Phone<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="phone" id="phone"  class="form-control phone" value="{{ old('phone') }}" placeholder="Mobile" readonly />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Address<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="address" id="address"  class="form-control phone" value="{{ old('address') }}" placeholder="Division Townshsip" readonly />
                                    </div>
                                </div>


                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mr-2">State / Division<span class="cancel_status">*</sapn> </label>
                                        <select name="province_id" id="province_id" class="form-control readonly-select">
                                            <option value="" selected disabled>Choose a state / division name</option>
                                            @foreach($provinces as $province)
                                            <option value="{{ $province->province_id }}">
                                                {{ $province->province_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mr-2">Township<span class="cancel_status">*</sapn> </label>
                                        <select name="amphur_id" id="amphur_id" class="form-control readonly-select">
                                            <option value="" selected disabled>Choose a township</option>
                                            @foreach($provinces as $province)
                                            <option value="{{ $province->province_id }}">
                                                {{ $province->province_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender">Gender<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="gender" id="gender"  class="form-control customer_barcode" value="{{ old('gender') }}" placeholder="Gender" readonly />
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dob">Birthday<span class="cancel_status">*</sapn> </label>
                                        <input type="date" name="dob" id="dob"  class="form-control customer_barcode" value="{{ old('dob') }}" readonly />
                                    </div>
                                </div>



                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nrc">NRC<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="nrc" id="nrc"  class="form-control nrc" value="{{ old('nrc') }}" placeholder="National Registration Card" readonly />
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="passport">Passport</label>
                                        <input type="text" name="passport" id="passport"  class="form-control passport" value="{{ old('passport') }}" placeholder="Passport" readonly />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="identification_card">Member Card.No</label>
                                        <input type="text" name="identification_card" id="identification_card"  class="form-control identification_card" value="{{ old('identification_card') }}" placeholder="xxxxxxxxxx" readonly />
                                    </div>
                                </div>
                                <input type="hidden" id="member_active" name="member_active" value="{{ old('member_active') }}"/>
                                <input type="hidden" id="customer_active" name="customer_active" value="{{ old('customer_active') }}"/>
                                <input type="hidden" id="customer_rank_id" name="customer_rank_id" value="{{ old('customer_rank_id') }}"/>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="customer_barcode">Customer Bar Code<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="customer_barcode" id="customer_barcode"  class="form-control customer_barcode" value="{{ old('customer_barcode') }}" placeholder="Customer Bar Code" readonly />
                                    </div>
                                </div>

                                <input type="hidden" id="titlename" name="titlename" value="{{ old('titlename') }}"/>
                                <input type="hidden" id="firstname" name="firstname" value="{{ old('firstname') }}"/>
                                <input type="hidden" id="lastname" name="lastname" value="{{ old('lastname') }}"/>
                                <input type="hidden" id="province_id" name="province_id" value="{{ old('province_id') }}"/>
                                <input type="hidden" id="amphur_id" name="amphur_id" value="{{ old('amphur_id') }}"/>
                                <input type="hidden" id="nrc_no" name="nrc_no" value="{{ old('nrc_no') }}"/>
                                <input type="hidden" id="nrc_name" name="nrc_name" value="{{ old('nrc_name') }}"/>
                                <input type="hidden" id="nrc_short" name="nrc_short" value="{{ old('nrc_short') }}"/>
                                <input type="hidden" id="nrc_number" name="nrc_number" value="{{ old('nrc_number') }}"/>
                                <input type="hidden" id="gbh_customer_id" name="gbh_customer_id" value="{{ old('gbh_customer_id') }}"/>
                            </div>

                            <div class="row">
                                {{-- <form id="match-form" action="" method="POST"> --}}
                                    <div class="col-md-12">
                                        <h1 class="text-primary">Setp 3: Match Installer Criteria</h1>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="text-center">Attach Installer Background File</h5>
                                            </div>
                                            {{-- <img src="{{ asset('images/documentillustration.jpg') }}" alt="document" style="width: 100%; height:200px; object-fit:cover"/> --}}
                                            <div class="card-body">
                                                <div class="">
                                                    <label for="images" class="gallery @error('images') is-invalid @enderror"><span>Choose Images</span></label>
                                                    <input type="file" name="images[]" id="images" class="form-control form-control-sm rounded-0" value="" multiple hidden/>
                                                    @error("images")
                                                        <b class="text-danger">{{ $message }}</b>
                                                    @enderror
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h1 class="text-primary">Setp 4: Associate with physical card</h1>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="card_number">Installer Card Number:</label>
                                        <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Scan Installer Card" readonly value="{{old('card_number')}}"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" id="back-btn" class="btn btn-warning mr-2" onclick="window.history.back();">Back</button>
                                    <button type="submit" class="btn btn-primary" id="">Save</button>
                                </div></br>
                            </div>
                    </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Page end  -->
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">

    $(document).ready(function(){
        $('#verify-btn').click(verifyinstaller);

        $('#verify-form').submit(function(e){
            e.preventDefault();
            verifyinstaller();
        });

        function verifyinstaller(){
            $("#register-installer-card-form")[0].reset();

            const verifyphone = $("#ver_phone").val();

            currentpercent = 1/4 * 100
            $('.progress-bar').css('width', `${currentpercent}%`);
            {{-- $('#register-progress-bar').text(`${currentpercent.toFixed(2)}%`); --}}

            $.ajax({
                url:"{{ route('installercards.verifycustomer') }}",
                type:"GET",
                dataType: "json",
                data: {
                    ver_phone:verifyphone
                },
                success:function(response){
                    console.log(response);

                    if(response.customer){
                        $('#fullname').val(response.customer.fullname);
                        $('#phone').val(response.customer.mobile);
                        $('#address').val(response.customer.full_address);
                        $('#gender').val(response.customer.sex);
                        $('#dob').val(response.customer.date_birthday);
                        $('#nrc').val(response.customer.nrc_array_id);
                        $('#passport').val(response.customer.passport);
                        $('#identification_card').val(response.customer.identification_card);
                        $('#member_active').val(response.customer.member_active);
                        $('#customer_active').val(response.customer.customer_active);
                        $('#customer_rank_id').val(response.customer.customer_rank_id);
                        $('#customer_barcode').val(response.customer.customer_barcode);


                        $('#titlename').val(response.customer.titlename);
                        $('#firstname').val(response.customer.firstname);
                        $('#lastnanme').val(response.customer.lastnanme);
                        $('#province_id').val(response.customer.province_id);
                        $('#amphur_id').val(response.customer.amphur_id);
                        $('#nrc_no').val(response.customer.nrc_no);
                        $('#nrc_name').val(response.customer.nrc_name);
                        $('#nrc_short').val(response.customer.nrc_short);
                        $('#nrc_number').val(response.customer.nrc_number);
                        $("#gbh_customer_id").val(response.customer.gbh_customer_id)

                        $('#card_number').focus();

                        currentpercent = 2/4 * 100
                        $('.progress-bar').css('width', `${currentpercent}%`)

                        if(response.ismembercustomer){
                            $('.poscustomer').addClass('member');
                        }else{
                            $('.poscustomer').removeClass('member');

                        }
                    }else{
                        {{-- console.log("No, Customer Found"); --}}
                        Swal.fire({
                            icon: "error",
                            title: "Customer Not Found!!",
                            text: "There is no customer with this phone number",
                        });
                    }


                },
                error:function(response){
                    console.log("Error: ",response);
                    Swal.fire({
                        icon: "error",
                        title: "Customer Not Found!!",
                        text: "There is no customer with this phone number",
                    });
                }
            });
        }

        $(document).on('keyup past','#ver_phone',function(){
            const verphone = $("#ver_phone").val();
            console.log(verphone);
            $('#hide_ver_phone').val(verphone);
        });


        var lastKeyTime = 0;
        $(document).keypress(function(event) {
            {{-- console.log(event.target); --}}
            if(event.target.name == 'card_number'){
                var inputField = $('#card_number');

                // Check if the input is readonly and prevent manual typing
                if (inputField.prop('readonly')) {
                    // Append the scanned character to the input field value
                    if (event.key !== 'Enter') {
                        var currentTime = new Date().getTime();

                        if(inputField.val() != '' && !(currentTime - lastKeyTime <= 50)){
                            inputField.val('');
                        }

                        if (currentTime - lastKeyTime <= 50 || inputField.val() === '') {
                            inputField.val(inputField.val() + event.key);
                        } else {
                            inputField.val('');
                        }
                        lastKeyTime = currentTime;
                    }

                    // Prevent form submission when 'Enter' key is pressed by the scanner
                    if (event.key === 'Enter') {
                        event.preventDefault();  // Prevent form submission

                        console.log('Scanned QR Code:', inputField.val());
                        {{-- $( "#check-btn" ).trigger( "click" ); --}}

                        {{-- $('#collectpointsform').submit(); --}}

                        currentpercent = 4/4 * 100
                        $('.progress-bar').css('width', `${currentpercent}%`)
                    }
                }
            }

        });


        {{-- Start Preview Image --}}

        var previewimages = function(input,output){

            // console.log(input.files);

            if(input.files){
                    var totalfiles = input.files.length;
                    // console.log(totalfiles);
                    if(totalfiles > 0){
                        $('.gallery').addClass('removetxt');
                    }else{
                        $('.gallery').removeClass('removetxt');
                    }
                    console.log(input.files);

                    for(let i = 0 ; i < totalfiles ; i++){
                        var filereader = new FileReader();


                        filereader.onload = function(e){
                            // $(output).html("");
                            {{-- console.log(input.files[i].type) --}}
                            if(input.files[i].type == 'application/pdf'){
                                $($.parseHTML('<img>')).attr({
                                    'src':'{{ asset('images/pdf-icon.png') }}',
                                    'title': `${input.files[i].name}`
                                }).appendTo(output);
                            }else{
                                $($.parseHTML('<img>')).attr({
                                    'src':e.target.result,
                                    'title': `${input.files[i].name}`
                                }).appendTo(output);
                            }
                        }

                        filereader.readAsDataURL(input.files[i]);

                    }
            }

        };

        $('#images').change(function(){
            let fullname = $('#fullname').val();
            console.log(fullname);
            if(fullname == ''){
                Swal.fire({
                    icon: "error",
                    title: "Installer is not verify yet",
                    text: "Please enter phone number to vefiry nstaller",
                });
                $(this).val('')
            }else{
                previewimages(this,'.gallery');
                $('#prevmonths_sale_amount').removeClass('is-invalid')
                currentpercent = 3/4 * 100
                $('.progress-bar').css('width', `${currentpercent}%`)
            }
        });
        {{-- End Preview Image --}}
    });

    function adjustProgressBar() {
        @php
            $currentpercent = 0;
            if ($errors->has('fullname')) {
                $currentpercent = 1 / 4 * 100;
            } elseif ($errors->has('prevmonths_sale_amount')) {
                $currentpercent = 2 / 4 * 100;
            } elseif ($errors->has('card_number')) {
                $currentpercent = 2 / 4 * 100;
            }
        @endphp

        // Pass the calculated percentage to JavaScript
        let currentpercent = {{ $currentpercent }};
        $('.progress-bar').css('width', `${currentpercent}%`);
    }
    adjustProgressBar();


</script>
@endsection
