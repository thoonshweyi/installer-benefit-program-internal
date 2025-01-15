@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Installer Card Points</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-4">
                <div class="row">
                    <div class="col-md-4  mb-3 md-mb-0">
                        <div class="installercards">
                            <h5 class="text-center">Installer Card <span class="float-right"><i class="fas fa-check-circle text-info"></i></span></h5>
                            <p><strong>Card Number:</strong> {{ $installercard->card_number }}</p>
                            <p><strong>Installer Name:</strong> {{ $installercard->fullname }}</p>
                            <p><strong>Phone Number:</strong> {{ $installercard->phone }}</p>
                            <p><strong>NRC:</strong> {{ $installercard->nrc }}</p>
                            <p><strong>Points Expiring Soon:</strong> {{ $expiringsoonpoints }} points by {{ \Carbon\Carbon::now()->endOfMonth()->format('d-m-Y') }}</p>
                        </div>
                        @if($installercardcount >= 1)
                        <h6 class="text-center mt-2"><i class="fas fa-2x fa-info-circle text-info"></i> <a href="{{ route('installercards.track',$installercard->card_number) }}">Installer has <span class="">{{ $installercardcount }}</span> more cards.</a></h6>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="card shadow p-3" data-toggle="modal" data-target="#balancemodel" style="cursor: pointer">
                                    <h5 class="text-underline" style="text-underline-offset: 5px;">Balance Point</h5>
                                    <div class="flex">
                                        <h2>{{ intval($installercard->totalpoints) }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3" >
                                    <h5>Balance Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ number_format($installercard->totalamount,0,'.',',') }} </h2>
                                        {{-- <small class="mr-2">. {{ substr($installercard->totalamount, -2) }}</small> --}}
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Used Point</h5>
                                    <h2>{{ $usedpoints ? $usedpoints : '0' }}</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Used Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ $usedamount ? number_format($usedamount,0,'.',',') : '0'}} </h2>
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Expired Point</h5>
                                    <h2>{{ $expiredpoints ? $expiredpoints : 0 }}</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Expired Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ $expiredamounts ? number_format($expiredamounts,0,'.',',') : 0 }}</h2>
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="card shadow p-3 bg-danger">
                                    <h5>Credit Points</h5>
                                    <div class="flex">
                                        <h2>{{ intval($installercard->credit_points) }} </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3 bg-danger">
                                    <h5>Credit Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ number_format($installercard->credit_amount,0,'.',',') }} </h2>
                                        {{-- <small class="mr-2">. {{ substr($installercard->totalamount, -2) }}</small> --}}
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- {{dd(number_format(1728600,0,'.',','))}} --}}

                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('installercardpoints.check',$installercard->card_number) }}" title="Installer Point Checking" class="btn btn-warning"><img src="{{ asset('/images/Common-File-Search--Streamline-Ultimate.png')}}" width="38px" alt=""></a>
                            </div>
                        </div>
                    </div>
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


            <div class="col-lg-12">
                <div class="row my-0 py-0">
                  <!-- Collect Point Section -->
                  <div class="col-md-6">
                    <div class="card p-4 shadow-lg collectpointcard">
                        <h4 class="text-center mb-4">Collect Point</h4>
                            <form id="collectpointsform" action="{{ route('installercardpoints.collectpoints',$installercard->card_number) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="Scan Sale Invoice" autofocus readonly>
                                </div>
                                <!-- bootstrap loader -->
                                <div class="d-flex justify-content-center mt-3">
                                        <div id="iclloader" class="spinner-border spinner-border-sm d-none" role="status"></div>
                                </div>
                                {{-- <div class="d-flex justify-content-start">
                                    <button type="submit" id="collectpoints" class="btn btn-secondary" type="button">Collect Points</button>
                                </div> --}}
                            </form>

                        <h4 class="mt-2">Earnings Points:</h4>
                        <form method="GET" action="{{ route('installercardpoints.detail',$installercard->card_number) }}">
                            <div class="row border-0 my-2">
                                <div class="col-md-6 border-0">
                                    <div class="form-group border-0">
                                        <input type="text" name="collection_search" value="{{ $collectionSearch }}" placeholder="Search Collection Transactions" class="form-control form-control-sm">
                                    </div>
                                    <input type="hidden" name="redemption_search" value="{{ request('redemption_search') }}">
                                </div>
                                <div class="col">
                                    @if(count(request()->query()) > 0)
                                        <button type="button" id="btn-clear" class="btn btn-light btn-clear" title="Refresh"><i class="fas fa-sync-alt"></i></button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <ul class="list-group list-group-flush earningpointlists">

                            @foreach ($collectiontransactions as $collectiontransaction)
                                <li class="list-group-item {{ $collectiontransaction->checkfullyredeemed() ? 'fullyredeemed' : '' }}"  onclick="window.location.href='{{ route('collectiontransactions.show',$collectiontransaction->uuid) }}'">
                                    <div class="row">
                                        <div class="col">
                                            <h6>{{ $collectiontransaction->document_no }}</h6>
                                            <p class="pb-0 mb-0">{{ $collectiontransaction->invoice_number }}</p>
                                            <small>{{ $collectiontransaction->collection_date }}</small>
                                            <small class="text-danger d-block">Expire at: {{ $collectiontransaction->getExpireDate() }}</small>
                                        </div>
                                        <div class="col-auto text-right">
                                            <h5 class="text-warning">+ {{$collectiontransaction->total_points_collected  }} pts</h5>
                                            <h5 class="text-success">+ {{ number_format($collectiontransaction->total_save_value,0,'.',',') }} MMK</h5>
                                        </div>
                                        @can('delete-collection-transaction')
                                            @if($collectiontransaction->isDeleteAuthUser() && $collectiontransaction->allowDelete())
                                            <div class="col-auto align-self-center">
                                                <form action="{{ url("/collectiontransactions/$collectiontransaction->uuid") }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="close bg-danger delete-btns" title="Delete">
                                                        <span class="">&times;</span>
                                                    </button>
                                                </form>

                                            </div>
                                            @endif
                                        @endcan
                                    </div>
                                    {{-- {{dd($collectiontransaction->returnbanner)}} --}}
                                    <div class="row">
                                        <div class="col-md-12" >
                                            @if(count($collectiontransaction->returnbanners) > 0)
                                            <div style="border-top: 2px dashed silver">
                                                @foreach($collectiontransaction->returnbanners as $returnbanner)
                                                <div class="d-flex justify-content-between py-2" >
                                                    <a href="{{ route('returnbanners.show',$returnbanner->uuid) }}" class="text-underline">{{ $returnbanner->return_product_docno  }}</a>
                                                    <span>{{ number_format($returnbanner->total_return_value,0,'.',',') }} MMK</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            <div class="d-flex justify-content-center">
                                {{ $collectiontransactions->appends(['redemption_page' => $redemptiontransactions->currentPage(),'collection_search' => $collectionSearch, 'redemption_search' => $redemptionSearch])->links() }}
                            </div>
                        </ul>
                    </div>
                  </div>

                  <!-- Redeem Point Section -->
                  <div class="col-md-6">
                    <div class="card p-4 shadow-lg redeemcashcards">
                        <h4 class="text-center mb-4">Redeem Points</h4>


                        <form id="" action="" method="">


                            <div class="form-group">
                                <input type="number" name="redeempoints" id="redeempoints" class="form-control" value="{{ old('redeempoints') }}" placeholder="Enter Redeem Points" max="{{ $installercard->totalpoints }}"/>
                                <span id="redeempoints_error" style="display:none;color:red;"></span>

                                @error("reqredeempoints")
                                       <span class="text-danger">{{ $message }}<span>
                                @enderror
                            </div>



                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success" type="button" id="reqredemption-btn">Request Redemption</button>
                            </div>
                        </form>


                        <h4 class="mt-2">Redemption Transaction:</h4>
                        <form method="GET" action="{{ route('installercardpoints.detail',$installercard->card_number) }}">
                            <div class="row border-0 align-items-center my-2">
                                <div class="col-md-6 border-0">
                                    <div class="form-group border-0">
                                        <input type="text" name="redemption_search" value="{{ $redemptionSearch }}" placeholder="Search Redemption Transactions" class="form-control form-control-sm">
                                    </div>
                                    <input type="hidden" name="collection_search" value="{{ request('collection_search') }}">
                                </div>
                                <div class="col">
                                    @if(count(request()->query()) > 0)
                                        <button type="button" id="btn-clear" class="btn btn-light btn-clear" title="Refresh"><i class="fas fa-sync-alt"></i></button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <ul class="list-group list-group-flush earningpointlists">
                            @foreach ($redemptiontransactions as $redemptiontransaction)
                                <li class="list-group-item"  onclick="window.location.href='{{ route('redemptiontransactions.show',$redemptiontransaction->uuid) }}'">
                                    <div class="row">
                                        <div class="col">
                                            {{-- <h6>{{ $collectiontransaction->pointpromotion->name }}</h6> --}}
                                            <p class="px-2 pb-0 mb-0 d-flex justify-content-between {{ ($redemptiontransaction->nature == 'normal') ? 'bg-success' :  (($redemptiontransaction->nature == 'return deduct') ? 'bg-danger' : ($redemptiontransaction->nature == 'double profit deduct' ? 'bg-warning' : '' )) }}">
                                                <span>{{ $redemptiontransaction->document_no }}</span>
                                                <span>({{ ucwords($redemptiontransaction->nature) }})</span>
                                            </p>
                                            {!! $redemptiontransaction->status == "pending" ? "<span class='badge bg-warning'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "approved" ? "<span class='badge bg-success'>$redemptiontransaction->status</span>" :($redemptiontransaction->status == "rejected"? "<span class='badge bg-danger'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "paid"? "<span class='badge bg-primary'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "finished"? "<span class='badge bg-secondary'>$redemptiontransaction->status</span>" : '')))) !!}
                                            <br>
                                            <small>{{ $redemptiontransaction->redemption_date }}</small>
                                            <small class="text-danger d-block m-0 p-0">Prepare By: {{ $redemptiontransaction->prepareby->name }}</small>
                                            <small class="text-danger m-0 p-0">Prepare At: {{  $redemptiontransaction->created_at }}</small>
                                            {{-- <small class="text-danger d-block">Expire at: {{ \Carbon\Carbon::parse($collectiontransaction->created_at)->endOfYear() }}</small> --}}
                                        </div>
                                        <div class="col-auto text-right">
                                            <h5 class="text-warning">- {{$redemptiontransaction->total_points_redeemed  }} pts</h5>
                                            <h5 class="text-danger">- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',')  }} MMK</h5>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            <div class="d-flex justify-content-center">
                                {{ $redemptiontransactions->appends(['collection_page' => $collectiontransactions->currentPage(),'collection_search' => $collectionSearch, 'redemption_search' => $redemptionSearch])->links() }}
                            </div>
                        </ul>
                    </div>
                  </div>

                  {{-- <div class="col-md-6">
                    <div class="card p-4 shadow-lg returnproductscards">
                        <h4 class="text-center mb-4">Return Product</h4>


                        <h4 class="mt-2">Return Deduction Records:</h4>
                        <ul class="list-group list-group-flush earningpointlists">
                            @foreach ($redemptiontransactions as $redemptiontransaction)
                                <li class="list-group-item"  onclick="window.location.href='{{ route('redemptiontransactions.show',$redemptiontransaction->uuid) }}'">
                                    <div class="row">
                                        <div class="col">
                                            <p class="pb-0 mb-0">{{ $redemptiontransaction->document_no }}</p>
                                            <small>{{ $redemptiontransaction->redemption_date }}</small>
                                            <small class="text-danger d-block m-0 p-0">Prepare By: {{ $redemptiontransaction->prepareby->name }}</small>
                                            <small class="text-danger m-0 p-0">Prepare At: {{  $redemptiontransaction->created_at }}</small>
                                        </div>
                                        <div class="col-auto text-right">
                                            <h5 class="text-warning">- {{$redemptiontransaction->total_points_redeemed  }} pts</h5>
                                            <h5 class="text-danger">- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',') }} MMK</h5>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                      </div>
                    </div>
                  </div> --}}
                </div>
            </div>


        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->


</div>


 <!-- START MODAL AREA -->
    <!-- start create modal -->
    <div id="requestmodal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h6 class="modal-title">Request Form</h6>
                    <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">
                    <form id="redeemrequestform" action="{{ route('installercardpoints.requestredeem',$installercard->card_number) }}" method="POST">
                        @csrf

                        <div class="row align-items-end mb-2">
                                <div class="col-md-5">
                                    <label for="name">Redeem Point <span class="text-danger">*</span></label>
                                    <input type="number" id="reqredeempoints" name="reqredeempoints" class="form-control form-control-sm rounded-0" autocomplete="off">
                                </div>

                                <div class="col-md-2">
                                    <span class="w-full text-center d-block">=</span>
                                </div>

                                <div class="col-md-5">
                                    <label for="name">Equivalent Amount<span class="text-danger">*</span></label>
                                    <input type="text" name="equivalentamount" id="equivalentamount" class="form-control form-control-sm rounded-0" value="{{ old('equivalentamount') }}" readonly/>
                                </div>
                        </div>

                        {{-- <div class="form-group my-2">
                            <label for="branch_id">Current Branch</label>
                            <select name="branch_id" id="branch_id" class="form-control @error('branch_id') is-invalid @enderror">
                                <option selected disabled>Choose Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->branch_id }}">{{ $branch->branch_name_eng }}</option>
                                @endforeach
                            </select>
                            @error("branch_id")
                                   <span class="text-danger">{{ $message }}<span>
                            @enderror
                        </div> --}}

                        <!-- bootstrap loader -->
                        <div class="d-flex justify-content-center mt-3">
                            <div id="ireloader" class="spinner-border spinner-border-sm d-none" role="status"></div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success" id="send2BM">Send To BM</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <div id="balancemodel" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h6 class="modal-title">Balance Modal</h6>
                    <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="balanceform" action="" method="POST">
                        @csrf

                        <div class="row align-items-center px-4">
                                <div class="col-md-12">
                                    <h4 class="text-center" >Balance Summary</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group d-flex justify-content-between">
                                        <div>
                                            <label for="name" class="d-block mb-0">Total Earned Points<span class="text-danger">*</span></label>
                                            <input type="hidden" id="total_earned_points" name="total_earned_points" class="form-control form-control-sm rounded-0" readonly value="1000">
                                            <span class="text-danger">(including before return points)</span>
                                        </div>

                                        <span>{{ $earnedpoints ? $earnedpoints : '0' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group d-flex justify-content-between">
                                        <label for="name">Total Used Points<span class="text-danger">*</span></label>
                                        <input type="hidden" id="total_redeemed_points" name="total_redeemed_points" class="form-control form-control-sm rounded-0" readonly value="1000">
                                        <span>{{ $usedpoints ? $usedpoints : '0' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center" style="border-top: 1px dashed #ddd;">
                                    <div class="form-group d-flex justify-content-between">
                                        <label for="name" class="text-center w-full d-block">Balance Points<span class="text-danger">*</span></label>
                                        <input type="hidden" name="balance_points" id="balance_points" class="form-control form-control-sm rounded-0" value="0" readonly/>
                                        <span>{{ intval($installercard->totalpoints) }}</span>
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- end create modal -->
 <!-- END MODAL AREA -->

@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $(window).on("beforeunload",function(){

        });

        {{-- $("#send2BM").click(function(e){
            $("#redeemrequestform").submit();
        }) --}}

        {{-- Start Send BM sure box, preventing Multi form submit  --}}
        document.getElementById('redeemrequestform').addEventListener("submit",function(e){
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to send redemption request to BM?",
                text: "BM will review your redemption transaction detail.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!"
                }).then((result) => {
                if (result.isConfirmed) {

                    document.getElementById("ireloader").classList.remove('d-none');
                    document.getElementById('send2BM').disabled = true;
                    document.getElementById('send2BM').innerText = "Please wait....";
                    this.submit();
                }
            });


        });
        $('#collectpointsform').submit(function(e){
            e.preventDefault();

            $('#iclloader').removeClass('d-none');
            this.submit();
        });
        {{-- Stop Send BM sure box, Preventing Multi form submit  --}}

        {{-- Start Request Form --}}
        $('#reqredemption-btn').click(function(){
            let getredeempoints = $('#redeempoints').val()

            if(getredeempoints){
                $('#redeempoints').removeClass('is-invalid')
                $('#redeempoints_error').hide();
                $('#redeempoints_error').text('');

                // Set the redeempoint value to totalpoint and then calculate
                totalpoints = {{ $installercard->totalpoints }}
                if(getredeempoints > totalpoints){
                    $('#redeempoints').val(totalpoints);
                    getredeempoints = totalpoints;
                }

                $.ajax({
                    url: '{{ route('installercardpoints.calculateEquivalentAmount',$installercard->card_number) }}',
                    method:'GET',
                    data:{"redeempoints":getredeempoints},
                    success:function(response){
                        console.log(response);
                        $('#reqredeempoints').val($('#redeempoints').val());
                        equivalentamount = Number(response.equivalentamount).toLocaleString();
                        $('#equivalentamount').val(equivalentamount);

                        $('#requestmodal').modal('show');

                    },
                    error:function(response){
                        console.log(response);
                    }
                })
            }else{
                $('#redeempoints').addClass('is-invalid');
                $('#redeempoints_error').show();
                $('#redeempoints_error').text('Redeem Point Field is required');

            }


        });

        $('#reqredeempoints').change(function(){
            // dynamically changing redeem points
            $('#redeempoints').val($('#reqredeempoints').val());
            let getredeempoints = $('#redeempoints').val();

            // reset the redeem point valu to total points and then recalculate
            totalpoints = {{ $installercard->totalpoints }}
            if(getredeempoints > totalpoints){
                $('#reqredeempoints').val(totalpoints);

                $('#redeempoints').val(totalpoints);
                getredeempoints = totalpoints;
            }

            $.ajax({
                url: '{{ route('installercardpoints.calculateEquivalentAmount',$installercard->card_number) }}',
                method:'GET',
                data:{"redeempoints":getredeempoints},
                success:function(response){
                    {{-- console.log(response); --}}
                    equivalentamount = Number(response.equivalentamount).toLocaleString();
                    $('#equivalentamount').val(equivalentamount);

                    $('#requestmodal').modal('show');

                },
                error:function(response){
                    console.log(response);
                }
            })
        });

        {{-- End Request Form --}}

        var lastKeyTime = 0;
        $(document).keypress(function(event) {
            {{-- console.log(event.target); --}}
            if(event.target.name == 'invoice_number'){
                var inputField = $('#invoice_number');

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

                        $('#collectpointsform').submit();
                    }
                }
            }

        });



        {{-- Start Auto Foucs by clicking black space on document --}}
        {{-- $(document).on('click',function(e){
            if(event.target.name == 'reqredeempoint'){
                console.log('hay');
                e.preventDefault();
            }else{
                $('#invoice_number').focus();
                $('#invoice_number').val('');
            }

        }); --}}
        {{-- End Auto Foucs by clicking black space on document --}}

        $('.delete-btns').click(function(e){
            e.stopPropagation();
            {{-- console.log('hay'); --}}

            Swal.fire({
                title: "Are you sure you want to delete collection transaction?",
                text: "All the collected points will be removed recursively.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {

                    {{-- document.getElementById("ireloader").classList.remove('d-none');
                    document.getElementById('send2BM').disabled = true;
                    document.getElementById('send2BM').innerText = "Please wait....";
                    this.submit(); --}}
                    $(this).closest('form').submit();
                }
            });
        });

        // Start Clear btn
        $(".btn-clear").click(function(){
                window.location.href = window.location.href.split("?")[0];
        });
        // End Clear btn


    });
</script>
@endsection
