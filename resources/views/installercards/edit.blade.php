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
                                    <button type="button" class="tablinks" onclick="gettab(event,'installer_homeowner')">Home Owner   <img src="{{ asset('images/handshake.png') }}" alt="" width="20" height="20"> Installer</button>
                                </li>

                                <li class="nav-item">
                                    <button type="button" id="autoclick" class="tablinks" onclick="gettab(event,'history')">History</button>
                                </li>

                            </ul>

                            <div class="tab-content">

                                 <div id="content" class="tab-pane">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="mb-3 text-center text-primary">{{ $installercard->card_number }}
                                                {!! $installercard->stage == "pending" ?
                                                "<span class='badge bg-warning'>$installercard->stage</span>" :
                                                ($installercard->stage == "approved" ? "<span class='badge bg-success'>$installercard->stage</span>" :
                                                ($installercard->stage == "rejected"? "<span class='badge bg-danger'>$installercard->stage</span>" :
                                                ($installercard->stage == "exported"? "<span class='badge bg-secondary'>$installercard->stage</span>" : ""
                                                ))) !!}
                                            </h4>
                                            <div class="d-flex justify-content-between font-weight-bold">
                                                <div class="d-flex flex-column">
                                                    <span>Branch - {{ $installercard->branch->branch_name_eng }}</span>
                                                    <span>Date: {{  \Carbon\Carbon::parse($installercard->created_at)->format('d-m-Y h:m:s A') }}</span>
                                                    {{-- <span>Installer Name - {{ $collectiontransaction->installercard->fullname }}</span> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <form action="{{ route('installercards.refresh',$installercard->card_number) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                            <h5>Automatic Fields:</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group poscustomer {{ $installercard->ismembercustomer() ? 'member' : '' }}">
                                                        <label for="edit_fullname">Full Name<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="edit_fullname" id="edit_fullname" class="form-control" value="{{old('fullname',$installercard->fullname)}}" placeholder="Full Name" readonly/>
                                                        <div class="crownicon">
                                                            <img src="{{ asset('./images/crown.png') }}" alt="crownicon" width="44" height="44">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_phone">Phone<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="edit_phone" id="edit_phone"  class="form-control phone" value="{{old('phone',$installercard->phone)}}" placeholder="Mobile" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_address">Address<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="edit_address" id="edit_address"  class="form-control phone" value="{{old('address',$installercard->address)}}" placeholder="Division Townshsip" readonly />
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
                                                        <label for="edit_gender">Gender<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="edit_gender" id="edit_gender"  class="form-control customer_barcode" value="{{old('gender',$installercard->gender)}}" placeholder="Gender" readonly />
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_dob">Birthday<span class="cancel_status">*</sapn> </label>
                                                        <input type="date" name="edit_dob" id="edit_dob"  class="form-control customer_barcode" value="{{old('dob',$installercard->dob)}}" readonly />
                                                    </div>
                                                </div>



                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_nrc">NRC<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="edit_nrc" id="edit_nrc"  class="form-control nrc" value="{{old('nrc',$installercard->nrc)}}" placeholder="National Registration Card" readonly />
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_passport">Passport</label>
                                                        <input type="text" name="edit_passport" id="edit_passport"  class="form-control passport" value="{{old('passport',$installercard->passport)}}" placeholder="Passport" readonly />
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_identification_card">Member Card.No</label>
                                                        <input type="text" name="edit_identification_card" id="edit_identification_card"  class="form-control identification_card" value="{{old('identification_card',$installercard->identification_card)}}" placeholder="xxxxxxxxxx" readonly />
                                                    </div>
                                                </div>
                                                <input type="hidden" id="edit_member_active" name="edit_member_active" value="{{ old('member_active',$installercard->member_active) }}"/>
                                                <input type="hidden" id="edit_customer_active" name="edit_customer_active" value="{{ old('customer_active',$installercard->customer_active) }}"/>
                                                <input type="hidden" id="edit_customer_rank_id" name="edit_customer_rank_id" value="{{ old('customer_rank_id',$installercard->customer_rank_id) }}"/>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="edit_customer_barcode">Customer Bar Code<span class="cancel_status">*</sapn> </label>
                                                        <input type="text" name="edit_customer_barcode" id="edit_customer_barcode"  class="form-control customer_barcode" value="{{old('customer_barcode',$installercard->customer_barcode)}}" placeholder="Customer Bar Code" readonly />
                                                    </div>
                                                </div>

                                                <input type="hidden" id="edit_titlename" name="edit_titlename" value="{{ old('titlename') }}"/>
                                                <input type="hidden" id="edit_firstname" name="edit_firstname" value="{{ old('firstname') }}"/>
                                                <input type="hidden" id="edit_lastname" name="edit_lastname" value="{{ old('lastname') }}"/>
                                                <input type="hidden" id="edit_province_id" name="edit_province_id" value="{{ old('province_id') }}"/>
                                                <input type="hidden" id="edit_amphur_id" name="edit_amphur_id" value="{{ old('amphur_id') }}"/>
                                                <input type="hidden" id="edit_nrc_no" name="edit_nrc_no" value="{{ old('nrc_no') }}"/>
                                                <input type="hidden" id="edit_nrc_name" name="edit_nrc_name" value="{{ old('nrc_name') }}"/>
                                                <input type="hidden" id="edit_nrc_short" name="edit_nrc_short" value="{{ old('nrc_short') }}"/>
                                                <input type="hidden" id="edit_nrc_number" name="edit_nrc_number" value="{{ old('nrc_number') }}"/>
                                                <input type="hidden" id="edit_gbh_customer_id" name="edit_gbh_customer_id" value="{{ old('gbh_customer_id') }}"/>

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


                                    @if($installercard->isApproveAuthUser() && $installercard->stage == 'pending')

                                    <div class="col-lg-12 mb-2">
                                        <form id="bm-form" action="" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <div class="form-group m-0">
                                                        <label for="remark" class="m-0">Remark</label>
                                                        <textarea name="remark" id="remark" class="form-control w-100" rows="2" placeholder="Write Something...."></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-auto p-0">
                                                    <button  type="button" id="bm-approve"class="btn btn-primary mr-2">Approve</button>
                                                </div>
                                                <div class="col-auto p-0">
                                                    <button type="button" id="bm-reject" class="btn btn-danger mr-2">Reject</button>
                                                </div>
                                                <div class="col-auto p-0">
                                                    <button type="button" id="back-btn" class="btn btn-light" onclick="window.history.back();">Back</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @endif

                                    <div class="row my-2">
                                        <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                                            <p class="mb-1">Prepare By</p>
                                            <span>{{ $installercard->user->name }}</span>
                                            {!!

                                                "( ".implode(",", array_map(function($role){
                                                    return "<span class='roles'>$role</span>";
                                                    },$installercard->user->getRoleNames()->toArray())
                                                )." )"

                                            !!}
                                            <span>{{ $installercard->issued_at }}</span>

                                        </div>

                                        <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                                            <p class="mb-1">Approved By</p>
                                            <span class="{{ $installercard->approvedby ? '' : 'text-muted font-weight-normal' }}">{{ $installercard->approvedby ? $installercard->approvedby->name : 'N/A' }}</span>
                                            @if($installercard->approvedby)
                                            {!!

                                                "( ".implode(",", array_map(function($role){
                                                    return "<span class='roles'>$role</span>";
                                                    },$installercard->approvedby->getRoleNames()->toArray())
                                                )." )"

                                            !!}
                                            @else
                                                    {!! "<span class='text-muted font-weight-normal roles'>(Branch Manager)</span>" !!}
                                            @endif
                                            <div class="d-flex flex-wrap ">
                                                <span class="font-weight-bold text-info">"</span> <span class="mx-1 text-info">{{  $installercard->bm_remark }}</span> <span class="font-weight-bold text-info">"</span>

                                            </div>
                                            <span class="{{ $installercard->approved_date ? '' : 'text-muted font-weight-normal' }}">{{  $installercard->approved_date ? $installercard->approved_date : 'MM-DD-YYYY' }}</span>
                                        </div>
                                    </div>

                                 </div>


                                 <div id="installer_homeowner" class="tab-pane">
                                    @can('attach-home-owner')
                                    <form action="{{ route('homeownerinstallers.store') }}" method="POST">
                                        @csrf
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                <input type="hidden" name="card_number" id="card_number" class="card_number" value="{{ $installercard->card_number }}"/>
                                                <div class="form-group">
                                                    <label for="home_owners">Home Owner</label>
                                                    <select name="home_owners[]" id="home_owners" class="form-control" multiple>
                                                        @foreach($homeowners as $homeowner)
                                                        <option value="{{ $homeowner->uuid }}">
                                                            {{ $homeowner->fullname}} ({{ $homeowner->phone }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" id="" class="btn btn-primary mb-2 text-center d-flex justify-content-center align-items-center" data-toggle="modal" data-target="#addhomeownermodal"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary my-1">Add</button>
                                            </div>

                                        </div>
                                    </form>
                                    @endcan

                                    <div>
                                        <a href="javascript:void(0);" id="bulkdelete-btn" class="btn btn-danger">Bulk Delete</a>
                                   </div>
                                    <table class="table mb-0 tbl-server-info" id="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                @can('detach-home-owner')
                                                <th>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="selectalls m-0" id="selectalls" class="form-check-input selectalls"/>
                                                    </div>
                                                </th>
                                                <th class="text-left">
                                                    Action
                                                </th>
                                                @endcan
                                                <th>No</th>
                                                <th>Home Owner</th>
                                                <th>Phone</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabledata" class="ligth-body">
                                            @foreach($homeownerinstallers as $idx=>$homeownerinstaller)
                                                <tr id="tablerole_{{$homeownerinstaller->id}}">
                                                    @can('detach-home-owner')
                                                    <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="singlechecks" class="form-check-input singlechecks" value="{{$homeownerinstaller->id}}"/>
                                                        </div>
                                                   </td>
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
                                                    <td><a href="{{ route('homeowners.edit',$homeownerinstaller->homeowner->uuid) }}"  class="text-underline" style="text-underline-offset: 5px;">{{ $homeownerinstaller->homeowner->fullname }}</a></td>
                                                    <td>{{ $homeownerinstaller->homeowner->phone }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                 </div>

                                 <div id="history" class="tab-pane">
                                    <h1>History</h1>

                                    <table class="table mb-0 tbl-server-info" id="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th class="text-left">No</th>
                                                <th class="text-left">Home Owners</th>
                                                <th class="text-left">By</th>
                                                <th class="text-left">Date Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabledata" class="ligth-body">
                                            @foreach($homeownerinstallerhistories as $idx=>$homeownerinstallerhistory)
                                                <tr >

                                                    <td class="text-left">
                                                        {{ ++$idx }}
                                                        {{ $idx === 1 ? "(Current)" : "" }}
                                                    </td>
                                                    <td class="text-left">
                                                        {{-- @foreach($homeownerinstallerhistory->homeowners() as $homeowner)
                                                            <a href="{{ route('homeowners.edit',$homeowner->uuid) }}" class="mx-1">{{$homeowner->fullname}} ({{ $homeowner->phone }})</a>,
                                                        @endforeach --}}

                                                        @php
                                                            $homeownernamelinks = $homeownerinstallerhistory->homeowners()->map(function($homeowner) {
                                                                return '<a href="' . route('homeowners.edit', $homeowner->uuid) . '" class="">' .
                                                                    $homeowner->fullname . ' (' . $homeowner->phone . ')</a>';
                                                            });

                                                            // dd($homeownernamelinks);

                                                        @endphp
                                                               {!! $homeownernamelinks->join(' / ') !!}

                                                    </td>
                                                    <td class="text-left">
                                                        {{$homeownerinstallerhistory->user->name}}
                                                    </td>
                                                    <td class="text-left">
                                                        {{  \Carbon\Carbon::parse($homeownerinstallerhistory->created_at)->format('d-m-Y h:m:s A') }}
                                                    </td>
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

<div id="addhomeownermodal" class="modal fade">
    <div class="modal-dialog modal-xl modal-dialog-top modal-dialog-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h6 class="modal-title">Add Home Owner Modal</h6>
                <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="col-md-12 my-4">
                        {{-- <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
                          </div> --}}

                        <div class="position-relative" style="top:-5px">
                            <span class="register-steps" style="position: absolute;left: 50%;transform:translateX(-50%)">1</span>
                            <span class="register-steps" style="position: absolute;left: 100%;transform:translateX(-50%)">2</span>
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
                    <form id="register-installer-card-form"  action="{{ route('homeowners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            {{-- <h5>Automatic Fields:</h5> --}}
                            <h1 class="text-primary">Setp 2: Check Home Owner Information</h1>
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
                                <div class="col-md-12">
                                    <button type="button" id="back-btn" class="btn btn-warning mr-2" onclick="window.history.back();">Back</button>
                                    <button type="submit" class="btn btn-primary" id="">Save</button>
                                </div></br>
                            </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
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

    });

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


   // Start Bulk Delete
    $("#selectalls").click(function(){
            $(".singlechecks").prop("checked",$(this).prop("checked"));
    });

    $("#bulkdelete-btn").click(function(){
            let getselectedids = [];

            console.log($("input:checkbox[name=singlechecks]:checked"));
            $("input:checkbox[name='singlechecks']:checked").each(function(){
                getselectedids.push($(this).val());
            });


            // console.log(getselectedids); // (4) ['1', '2', '3', '4']



            Swal.fire({
                title: "Are you sure?",
                text: `You won't be able to revert!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // data remove
                    $.ajax({
                        url:"{{ route('homeownerinstallers.bulkdeletes') }}",
                        type:"DELETE",
                        dataType:"json",
                        data:{
                                selectedids:getselectedids,
                                _token:"{{ csrf_token() }}"
                        },
                        success:function(response){
                                console.log(response);   // 1

                                if(response){
                                    // ui remove
                                    $.each(getselectedids,function(key,val){
                                        $(`#tablerole_${val}`).remove();
                                    });

                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                }
                        },
                        error:function(response){
                                console.log("Error: ",response)
                        }
                    });

                }
            });
    });
    // End Bulk Delete



    $('#bm-reject').click(function(e){
        {{-- console.log('hi'); --}}
        e.preventDefault();

        Swal.fire({
            title: "Are you sure you want to reject redemption request?",
            text: "Redemption Transacation will be rejected",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, reject it!"
          }).then((result) => {
            if (result.isConfirmed) {
                $('#bm-form').attr('action',"{{ route('installercards.rejectCardRequest', ['cardnumber' => $installercard->card_number]) }}");
                $('#bm-form').submit();
            }
          });

    });


    $('#bm-approve').click(function(e){
        {{-- console.log('hi'); --}}
        e.preventDefault();

        $('#bm-form').attr('action',"{{ route('installercards.approveCardRequest',$installercard->card_number) }}");
        $('#bm-form').submit();

    });
</script>
@endsection
