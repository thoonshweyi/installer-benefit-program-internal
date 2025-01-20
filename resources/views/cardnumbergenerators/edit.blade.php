@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Card Number Generator</h4>
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

                        {{-- <form action="{{ route('cardnumbergenerators.store') }}" method="POST" enctype="multipart/form-data"> --}}
                            {{-- @csrf --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mb-3 text-center text-primary">{{ $cardnumbergenerator->document_no }}
                                        {!! $cardnumbergenerator->status == "pending" ?
                                        "<span class='badge bg-warning'>$cardnumbergenerator->status</span>" :
                                        ($cardnumbergenerator->status == "approved" ? "<span class='badge bg-success'>$cardnumbergenerator->status</span>" :
                                        ($cardnumbergenerator->status == "rejected"? "<span class='badge bg-danger'>$cardnumbergenerator->status</span>" :
                                        ($cardnumbergenerator->status == "exported"? "<span class='badge bg-primary'>$cardnumbergenerator->status</span>" : ""
                                        ))) !!}
                                    </h4>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <div class="d-flex flex-column">
                                            <span>Branch - {{ $cardnumbergenerator->branch->branch_name_eng }}</span>
                                            <span>Date: {{  \Carbon\Carbon::parse($cardnumbergenerator->created_at)->format('d-m-Y h:m:s A') }}</span>
                                            {{-- <span>Installer Name - {{ $collectiontransaction->installercard->fullname }}</span> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="to_branch_id">To Branch</label>
                                        <select name="to_branch_id" id="to_branch_id" class="form-control @error('to_branch_id') is-invalid @enderror" readonly disabled>
                                            <option selected disabled>Choose Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->branch_id }}" {{ $cardnumbergenerator->to_branch_id == $branch->branch_id ? "selected" : "" }}>{{ $branch->branch_name_eng }}</option>
                                            @endforeach
                                        </select>
                                        @error("to_branch_id")
                                            <span class="text-danger">{{ $message }}<span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label>Quantity<span class="cancel_status">*</sapn> </label>
                                        <input type="number" id="quantity" name="quantity" class="form-control quantity" value="{{old('name',$cardnumbergenerator->quantity)}}" placeholder="Enter Quantity to generate" readonly/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mr-2 d-block">Random <span class="cancel_status">*
                                                </sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="random" id="radio1" value='1' @if($cardnumbergenerator->random == 1) checked @endif >
                                            <label for="radio1">Yes</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="random" id="radio2" value='2' @if($cardnumbergenerator->random == 2) checked @endif >
                                            <label for="radio2">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="4" placeholder="Write Something...." readonly>{{ $cardnumbergenerator->remark }}
                                    </textarea>
                                </div>

                                {{-- <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" id="">Save</button>
                                </div></br> --}}
                            </div>
                        {{-- </form> --}}

                        {{-- <img src="data:image/png;base64,{{ $qrviews[0] }}" alt="QR Code"/> --}}





                        {{-- {{ dd(count($cardnumbergenerator->cardnumbers)) }} --}}
                        @if(count($cardnumbergenerator->cardnumbers) > 0)
                        <div class="col-lg-12 my-4">
                            <div class="table-responsive rounded">
                                <h5>Card Number</h5>

                                @if($cardnumbergenerator->isFinishedAuthUser() && $cardnumbergenerator->status === "approved")
                                <a href=" {{ route('cardnumbergenerators.export',$cardnumbergenerator->uuid) }}" id="export-btn" class="btn btn-primary my-2">Export</a>
                                {{-- <a href="javascript:void(0);" id="export-btn" class="btn btn-primary my-2">Export</a> --}}


                                @endif


                                <table class="table mb-0 tbl-server-info" id="lucky_draw_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>No</th>
                                            <th>Card Number</th>
                                            <th>QR</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                        @foreach ($cardnumbergenerator->cardnumbers as $idx=>$cardnumber)
                                        <tr>
                                            <td>{{ ++$idx }}</td>
                                            <td>{{ $cardnumber->card_number }}</td>
                                            <td><img src="{{ asset($cardnumber->image) }}" alt=""></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            @if($cardnumbergenerator->isApproveAuthUser() && $cardnumbergenerator->status == 'pending')
                            <div class="col-lg-12 mb-2">
                                <form id="mkt-mgr-form" action="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <div class="form-group m-0">
                                                <label for="remark" class="m-0">Marketing Manager Remark</label>
                                                <textarea name="remark" id="remark" class="form-control w-100" rows="2" placeholder="Write Something...."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-auto p-0">
                                            <button  type="button" id="mkt-mgr-approve"class="btn btn-primary mr-2">Approve</button>
                                        </div>
                                        <div class="col-auto p-0">
                                            <button type="button" id="mkt-mgr-reject" class="btn btn-danger mr-2">Reject</button>
                                        </div>
                                        <div class="col-auto p-0">
                                            <button type="button" id="back-btn" class="btn btn-light" onclick="window.history.back();">Back</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                                <p class="mb-1">Prepare By</p>
                                <span>{{ $cardnumbergenerator->prepareby->name }}</span>
                                {{-- {{ dd($cardnumbergenerator->prepareby->getRoleNames()) }} --}}
                                {!!

                                    "( ".implode(",", array_map(function($role){
                                        return "<span class='roles'>$role</span>";
                                        },$cardnumbergenerator->prepareby->getRoleNames()->toArray())
                                    )." )"

                                !!}
                                <span>{{ $cardnumbergenerator->created_at }}</span>

                            </div>

                            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                                <p class="mb-1">Approved By</p>
                                <span class="{{ $cardnumbergenerator->approvedby ? '' : 'text-muted font-weight-normal' }}">{{ $cardnumbergenerator->approvedby ? $cardnumbergenerator->approvedby->name : 'N/A' }}</span>
                                @if($cardnumbergenerator->approvedby)
                                {!!

                                    "( ".implode(",", array_map(function($role){
                                        return "<span class='roles'>$role</span>";
                                        },$cardnumbergenerator->approvedby->getRoleNames()->toArray())
                                    )." )"

                                !!}
                                @else
                                        {!! "<span class='text-muted font-weight-normal roles'>(Marketing Manager)</span>" !!}
                                @endif
                                <div class="d-flex flex-wrap ">
                                    <span class="font-weight-bold text-info">"</span> <span class="mx-1 text-info">{{  $cardnumbergenerator->mkt_mgr_remark }}</span> <span class="font-weight-bold text-info">"</span>

                                </div>
                                <span class="{{ $cardnumbergenerator->approved_date ? '' : 'text-muted font-weight-normal' }}">{{  $cardnumbergenerator->approved_date ? $cardnumbergenerator->approved_date : 'MM-DD-YYYY' }}</span>
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
@endsection
@section('js')
<script type="text/javascript">

    $(document).ready(function(){
        $('#mkt-mgr-reject').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to reject card number generation request?",
                text: "Card Numbers will be rejected",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, reject it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    $('#mkt-mgr-form').attr('action',"{{ route('cardnumbergenerators.rejectCardNumberGenerator', ['cardnumbergenerator' => $cardnumbergenerator->uuid, 'step' => 'mkt-mgr']) }}");
                    $('#mkt-mgr-form').submit();
                }
              });

        });


        $('#mkt-mgr-approve').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            $('#mkt-mgr-form').attr('action',"{{ route('cardnumbergenerators.approveCardNumberGenerator',$cardnumbergenerator->uuid) }}");
            $('#mkt-mgr-form').submit();

        });
{{--
        $('#export-btn').click(function(e){
            e.preventDefault();
            $.ajax({
                url: '{{ route('cardnumbergenerators.export',$cardnumbergenerator->uuid) }}',
                type: "GET",
                dataType:"json",
                success:function(response){

                        console.log(response);

                },
                error:function(response){
                        console.log("Error:",response);
                }
            });
        });
 --}}


    });




</script>
@endsection
