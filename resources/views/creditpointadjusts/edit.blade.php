@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Credit Point Adjust Detail</h4>
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

                            <div class="row">
                                <div class="col-md-12 logo-container">
                                    <h4 class="mb-3 text-center text-primary">{{ $creditpointadjust->document_no }}
                                        {!! $creditpointadjust->status == "pending" ?
                                        "<span class='badge bg-warning'>$creditpointadjust->status</span>" :
                                        ($creditpointadjust->status == "approved" ? "<span class='badge bg-success'>$creditpointadjust->status</span>" :
                                        ($creditpointadjust->status == "rejected"? "<span class='badge bg-danger'>$creditpointadjust->status</span>" : ""
                                        )) !!}
                                    </h4>
                                    <h5 class="text-center">Installer Card - {{ $creditpointadjust->installer_card_card_number }}</h5>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <div class="d-flex flex-column">
                                            <span>Branch - {{ $creditpointadjust->branch->branch_name_eng }}</span>
                                            <span>Date: {{  \Carbon\Carbon::parse($creditpointadjust->created_at)->format('d-m-Y h:i:s A') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Reason</label>
                                        <select name="reason" id="reason" class="form-control" readonly>
                                            <option value="" selected disabled>Choose Reason</option>
                                            <option value="Mobile Banking Transfer">Mobile Banking Transfer</option>
                                            <option value="Cash Receive" >Cash Receive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="total_points_adjusted">Total Point Adjusted</label>
                                        <input type="text" name="total_points_adjusted" id="total_points_adjusted" class="form-control" readonly value="{{ $creditpointadjust->total_points_adjusted }}"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="total_adjust_value_formatted">Total Amount Adjusted</label>
                                        <input type="text" name="total_adjust_value_formatted" id="total_adjust_value_formatted" class="form-control" readonly value="{{ number_format(abs($creditpointadjust->total_adjust_value),0,'.',',') }} MMK"/>
                                        <input type="hidden" name="total_adjust_value" id="total_adjust_value" class="form-control" readonly value="{{ $creditpointadjust->total_adjust_value }}"/>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="4" placeholder="Write Something...." readonly>{{ $creditpointadjust->remark }}

                                    </textarea>
                                </div>
                            </div>



                        <div class="row">
                            @if($creditpointadjust->isApproveAuthUser() && $creditpointadjust->status == 'pending')
                            <div class="col-lg-12 mb-2">
                                <form id="bm-form" action="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <div class="form-group m-0">
                                                <label for="remark" class="m-0">Branch Manager Remark</label>
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
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                                <p class="mb-1">Prepare By</p>
                                <span>{{ $creditpointadjust->prepareby->name }}</span>
                                {{-- {{ dd($creditpointadjust->prepareby->getRoleNames()) }} --}}
                                {!!

                                    "( ".implode(",", array_map(function($role){
                                        return "<span class='roles'>$role</span>";
                                        },$creditpointadjust->prepareby->getRoleNames()->toArray())
                                    )." )"

                                !!}
                                <span>{{ $creditpointadjust->created_at }}</span>

                            </div>

                            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                                <p class="mb-1">Approved By</p>
                                <span class="{{ $creditpointadjust->approvedby ? '' : 'text-muted font-weight-normal' }}">{{ $creditpointadjust->approvedby ? $creditpointadjust->approvedby->name : '' }}</span>
                                @if($creditpointadjust->approvedby)
                                {!!

                                    "( ".implode(",", array_map(function($role){
                                        return "<span class='roles'>$role</span>";
                                        },$creditpointadjust->approvedby->getRoleNames()->toArray())
                                    )." )"

                                !!}
                                @else
                                        {!! "<span class='text-muted font-weight-normal roles'>(Branch Manager)</span>" !!}
                                @endif
                                <div class="d-flex flex-wrap ">
                                    @if($creditpointadjust->bm_remark)
                                        <span class="font-weight-bold text-info">"</span> <span class="mx-1 text-info">{{  $creditpointadjust->bm_remark }}</span> <span class="font-weight-bold text-info">"</span>
                                    @else

                                    @endif
                                </div>
                                <span class="{{ $creditpointadjust->approved_date ? '' : 'text-muted font-weight-normal' }}">{{  $creditpointadjust->approved_date ? $creditpointadjust->approved_date : '' }}</span>
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
        $('#bm-reject').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to reject credit point adjust request",
                text: "Credit point adjust request fill be rejected",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, reject it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    $('#bm-form').attr('action',"{{ route('creditpointadjusts.rejectCreditPointAdjustReq', ['creditpointadjust' => $creditpointadjust->uuid]) }}");
                    $('#bm-form').submit();
                }
              });

        });


        $('#bm-approve').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to approve credit point adjust request?",
                text: "Installer card will reduce credit point after your approval",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, approve it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    $('#bm-form').attr('action',"{{ route('creditpointadjusts.approveCreditPointAdjustReq',$creditpointadjust->uuid) }}");
                    $('#bm-form').submit();
                }
              });


        });
{{--
        $('#export-btn').click(function(e){
            e.preventDefault();
            $.ajax({
                url: '{{ route('creditpointadjusts.export',$creditpointadjust->uuid) }}',
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


        $("#reason").val('{{ $creditpointadjust->reason }}')

    });




</script>
@endsection
