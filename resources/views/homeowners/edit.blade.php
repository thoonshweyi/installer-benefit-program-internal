@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit Home Owner</h4>
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

                    <form action="{{ route('homeowners.refresh',$homeowner->uuid) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                            <h5>Automatic Fields:</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group poscustomer {{ $homeowner->ismembercustomer() ? 'member' : '' }}">
                                        <label for="fullname">Full Name<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="fullname" id="fullname" class="form-control" value="{{old('fullname',$homeowner->fullname)}}" placeholder="Full Name" readonly/>
                                        <div class="crownicon">
                                            <img src="{{ asset('./images/crown.png') }}" alt="crownicon" width="44" height="44">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone">Phone<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="phone" id="phone"  class="form-control phone" value="{{old('phone',$homeowner->phone)}}" placeholder="Mobile" readonly />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="address">Address<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="address" id="address"  class="form-control phone" value="{{old('address',$homeowner->address)}}" placeholder="Division Townshsip" readonly />
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
                                        <input type="text" name="gender" id="gender"  class="form-control customer_barcode" value="{{old('gender',$homeowner->gender)}}" placeholder="Gender" readonly />
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dob">Birthday<span class="cancel_status">*</sapn> </label>
                                        <input type="date" name="dob" id="dob"  class="form-control customer_barcode" value="{{old('dob',$homeowner->dob)}}" readonly />
                                    </div>
                                </div>



                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nrc">NRC<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="nrc" id="nrc"  class="form-control nrc" value="{{old('nrc',$homeowner->nrc)}}" placeholder="National Registration Card" readonly />
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="passport">Passport</label>
                                        <input type="text" name="passport" id="passport"  class="form-control passport" value="{{old('passport',$homeowner->passport)}}" placeholder="Passport" readonly />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="identification_card">Member Card.No</label>
                                        <input type="text" name="identification_card" id="identification_card"  class="form-control identification_card" value="{{old('identification_card',$homeowner->identification_card)}}" placeholder="xxxxxxxxxx" readonly />
                                    </div>
                                </div>
                                <input type="hidden" id="member_active" name="member_active" value="{{ old('member_active',$homeowner->member_active) }}"/>
                                <input type="hidden" id="customer_active" name="customer_active" value="{{ old('customer_active',$homeowner->customer_active) }}"/>
                                <input type="hidden" id="customer_rank_id" name="customer_rank_id" value="{{ old('customer_rank_id',$homeowner->customer_rank_id) }}"/>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="customer_barcode">Customer Bar Code<span class="cancel_status">*</sapn> </label>
                                        <input type="text" name="customer_barcode" id="customer_barcode"  class="form-control customer_barcode" value="{{old('customer_barcode',$homeowner->customer_barcode)}}" placeholder="Customer Bar Code" readonly />
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
                                    <button type="button" id="back-btn" class="btn btn-warning mr-2" onclick="window.history.back();">Back</button>
                                    <button type="submit" class="btn btn-primary" id="">Refresh</button>
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
@section('js')
<script type="text/javascript">
    

</script>
@endsection
