@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit Installer Card</h4>
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

                        <div class="card border-0 rounded-0 shadow mb-4">
                            <ul class="nav tab-navs">
                                 <li class="nav-item">
                                      <button type="button" id="autoclick" class="tablinks" onclick="gettab(event,'content')">Content</button>
                                 </li>

                                 <li class="nav-item">
                                    <button type="button" class="tablinks" onclick="gettab(event,'installer_homeowner')">Installer   <img src="{{ asset('images/handshake.png') }}" alt="" width="20" height="20"> Home Owner</button>
                               </li>
                            </ul>

                            <div class="tab-content">

                                 <div id="content" class="tab-pane">
                                    <form action="{{ route('installercards.refresh',$installercard->card_number) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                            <h5>Automatic Fields:</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group poscustomer {{ $installercard->ismembercustomer() ? 'member' : '' }}">
                                                        <label for="fullname">Full Name<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="fullname" id="fullname" class="form-control" value="{{old('fullname',$installercard->fullname)}}" placeholder="Full Name" readonly/>
                                                        <div class="crownicon">
                                                            <img src="{{ asset('./images/crown.png') }}" alt="crownicon" width="44" height="44">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="phone">Phone<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="phone" id="phone"  class="form-control phone" value="{{old('phone',$installercard->phone)}}" placeholder="Mobile" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="address">Address<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="address" id="address"  class="form-control phone" value="{{old('address',$installercard->address)}}" placeholder="Division Townshsip" readonly />
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
                                                        <input type="text" name="gender" id="gender"  class="form-control customer_barcode" value="{{old('gender',$installercard->gender)}}" placeholder="Gender" readonly />
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="dob">Birthday<span class="cancel_status">*</sapn> </label>
                                                        <input type="date" name="dob" id="dob"  class="form-control customer_barcode" value="{{old('dob',$installercard->dob)}}" readonly />
                                                    </div>
                                                </div>



                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="nrc">NRC<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="nrc" id="nrc"  class="form-control nrc" value="{{old('nrc',$installercard->nrc)}}" placeholder="National Registration Card" readonly />
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="passport">Passport</label>
                                                        <input type="text" name="passport" id="passport"  class="form-control passport" value="{{old('passport',$installercard->passport)}}" placeholder="Passport" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="identification_card">Member Card.No</label>
                                                        <input type="text" name="identification_card" id="identification_card"  class="form-control identification_card" value="{{old('identification_card',$installercard->identification_card)}}" placeholder="xxxxxxxxxx" readonly />
                                                    </div>
                                                </div>
                                                <input type="hidden" id="member_active" name="member_active" value="{{ old('member_active',$installercard->member_active) }}"/>
                                                <input type="hidden" id="customer_active" name="customer_active" value="{{ old('customer_active',$installercard->customer_active) }}"/>
                                                <input type="hidden" id="customer_rank_id" name="customer_rank_id" value="{{ old('customer_rank_id',$installercard->customer_rank_id) }}"/>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="customer_barcode">Customer Bar Code<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="customer_barcode" id="customer_barcode"  class="form-control customer_barcode" value="{{old('customer_barcode',$installercard->customer_barcode)}}" placeholder="Customer Bar Code" readonly />
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

                                                <div class="col-md-12">
                                                    <label for="">Installer Background Files</label>
                                                    <div class="installercardfiles">
                                                        <div class="d-flex">
                                                            @foreach ($installercard->installercardfiles as $installercardfile)
                                                                    {{-- {{ dd(explode('.',$installercardfile->image)[1]) }} --}}

                                                                    @php

                                                                        $filearr = explode('.',$installercardfile->image);
                                                                        $fileformat = $filearr[count($filearr)-1];
                                                                        // dd($fileformat);

                                                                        $filepatharr = explode('/',$installercardfile->image);
                                                                        // dd($filepatharr);
                                                                        $filename = $filepatharr[count($filepatharr)-1];
                                                                    @endphp
                                                                    @if($fileformat == 'jpg' || $fileformat == 'jpeg' ||  $fileformat == 'png')
                                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                                            <img src="{{ asset($installercardfile->image) }}" class="redemptiontransactionimages" alt="{{$installercardfile->installer_card_card_number}}" title="{{ $filename }}"/>
                                                                            <a href="{{ asset($installercardfile->image) }}" download title="download"><i class="fas fa-download"></i></a>
                                                                        </div>
                                                                    @else
                                                                        @if ($fileformat == 'pdf')
                                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                                            <img src="{{ asset('images/pdf-icon.png') }}" class="redemptiontransactionimages" alt="{{$installercardfile->installer_card_card_number}}" title="{{ $filename }}"/>
                                                                            <a href="{{ asset($installercardfile->image) }}" download title="download"><i class="fas fa-download"></i></a>
                                                                        </div>
                                                                        @endif
                                                                    @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="card_number">Installer Card Number:</label>
                                                        <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Scan Installer Card" value="{{old('card_number',$installercard->card_number)}}" readonly/>
                                                    </div>
                                                    @if($installercardcount >= 1)
                                                    <h6 class="text-center mt-2"><i class="fas fa-2x fa-info-circle text-info"></i> <a href="{{ route('installercards.track',$installercard->card_number) }}">Installer has <span class="">{{ $installercardcount }}</span> more cards.</a></h6>
                                                    @endif
                                                </div>

                                                <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="card_number">Related Card Numbers:</label>
                                                        </div>
                                                        {{-- <h6>{{ join(', ',$card_numbers->toArray()) }}</h6> --}}
                                                        <ul class="list-unstyled">
                                                            @foreach ($card_numbers as $card_number)
                                                            <li><a href="{{ route('installercardpoints.index', ['installer_card_card_number' => $card_number]) }}" class="underline mr-2 cardnumbers" data-card_number="{{ $card_number }}">{{ $card_number }}</a></li>
                                                            @endforeach
                                                        </ul>

                                                </div>


                                                <div class="col-md-12">
                                                    <button type="button" id="back-btn" class="btn btn-warning mr-2" onclick="window.history.back();">Back</button>
                                                    <button type="submit" class="btn btn-primary" id="">Refresh</button>
                                                </div></br>
                                            </div>
                                    </form>
                                 </div>


                                 <div id="installer_homeowner" class="tab-pane">
                                    @can('attach-home-owner')
                                    <form action="{{ route('homeownerinstallers.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="hidden" name="card_number" id="card_number" class="card_number" value="{{ $installercard->card_number }}"/>
                                                <div class="form-group">
                                                    <label for="home_owners">Home Owner</label>
                                                    <select name="home_owners[]" id="home_owners" class="form-control" multiple>
                                                        @foreach($homeowners as $homeowner)
                                                        <option value="{{ $homeowner->uuid }}">
                                                            {{ $homeowner->fullname}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary my-1">Add</button>

                                            </div>
                                        </div>
                                    </form>
                                    @endcan


                                    <table class="table mb-0 tbl-server-info" id="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                @can('detach-home-owner')
                                                <th class="text-left">Action</th>
                                                @endcan
                                                <th>No</th>
                                                <th>Home Owner</th>
                                                <th>Phone</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabledata" class="ligth-body">
                                            @foreach($homeownerinstallers as $idx=>$homeownerinstaller)
                                                <tr>
                                                    @can('detach-home-owner')
                                                    <td>
                                                        <form action="{{ route('homeownerinstallers.destroy',$homeownerinstaller->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a class="text-danger delete-btns" title="Delete"><i class="fas fa-trash"></i></a>
                                                        </form>
                                                    </td>
                                                    @endcan

                                                    <td>
                                                        {{ ++$idx }}
                                                    </td>

                                                    <td>{{ $homeownerinstaller->homeowner->fullname }}</td>
                                                    <td>{{ $homeownerinstaller->homeowner->phone }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
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

<!-- Page end  -->
</div>
</div>

{{-- Start Modal Area --}}
<div class="modal fade show_image" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="show_image_div">
        </div>
    </div>
</div>
{{-- End Modal Area--}}


<!-- START MODAL AREA -->
    <!-- start create modal -->
    {{-- <div id="showmodal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h6 class="modal-title">Installer Card Modal</h6>
                        <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                            <h1 class="text-center" id="foundinscardnumber"></h1>
                              <h5 class="card-title">Installer Information</h5>
                              <ul class="list-group list-group-flush">

                              </ul>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">

                    </div>
                </div>
        </div>
    </div> --}}
    <!-- end create modal -->

<!-- END MODAL AREA -->
@endsection

@section('css')
<style type="text/css">
   /* Start Tag Box */
   .tab-navs{
        display: flex;
        background: #f3f3f3;

        padding: 0;
        margin: 0;
    }
    .nav .nav-item{
        list-style-type: none;

        position: relative;
    }
    .nav .nav-item::after{
        content: '';
        width: 2px;
        height: 50%;
        border-radius: 40px;
        background: var(--primary);

        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%)
    }
    .nav .tablinks{
        border: none;
        font-size: 16px;
        padding: 15px 20px;
        cursor: pointer;

        transition: all 0.3s ease-in;
    }
    .nav .tablinks:hover{
        color: white;
        background-color: var(--primary);
    }

    .nav .tablinks.active{
        /* background: white; */
        color: var(--primary) !important;
    }

    .tab-pane{

    padding: 5px 15px;

    display: none;
    }
    /* End Tag Box */
</style>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-btns').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to remove an installer card?",
                text: "Installer Card will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    {{-- console.log($(this).closest('form')); --}}
                    $(this).closest('form').submit();
                }
              });

        });
    });

    $('#ver_phone').blur(function(){
        const verifyphone = $("#ver_phone").val();

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
    });


    $('.redemptiontransactionimages').click(function(){
        var src = $(this).attr("src");
        {{-- console.log(src) --}}

        $('#show_image_div').html('');
        $('#show_image_div').append(`
            <img class="rounded img-fluid" id="table_image" src="" alt="profile" width="" height="" style="text-align:center;">
        `);

        // $("#table_image").prop('src', src);
        $("#table_image").attr('src', src);

        $('.show_image').modal('show');
    });


    $('#home_owners').select2({
        width: '100%',
        placeholder: "Select a home owner", // Placeholder text
        allowClear: true,                  // Allow clearing the selection
        closeOnSelect: false               // Optional for multi-select dropdown
    });
    $('#home_owners').val(null).trigger('change');



    // Start Tag Box
    var gettablinks = document.getElementsByClassName('tablinks');  //HTMLCollection
        var gettabpanes = document.getElementsByClassName('tab-pane');
        // console.log(gettabpanes);

        var tabpanes = Array.from(gettabpanes);

        function gettab(evn,linkid){

            tabpanes.forEach(function(tabpane){
                tabpane.style.display = 'none';
            });

            for(var x = 0 ; x < gettablinks.length ; x++){
                gettablinks[x].className = gettablinks[x].className.replace(' active','');
            }


            document.getElementById(linkid).style.display = 'block';


            // evn.target.className += ' active';
            // evn.target.className = evn.target.className.replace('tablinks','tablinks active');
            // evn.target.classList.add('active');

            // evn.target = evn.currentTarget
            evn.currentTarget.className += ' active';

        }

        document.getElementById('autoclick').click();
   // End Tag Box


</script>
@endsection
