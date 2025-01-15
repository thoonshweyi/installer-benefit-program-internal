@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 logo-container">
                    <div class="pro1logo">
                        <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" width="160px" style="object-fit: cover;" alt="pro1logo" />
                    </div>
                    <div>
                        <h4 class="mb-3 text-center text-success"><span>Redemption Transaction</span> <span>( {{ $redemptiontransaction->document_no }} )</span> {!! $redemptiontransaction->status == "pending" ? "<span class='badge bg-warning'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "approved" ? "<span class='badge bg-success'>$redemptiontransaction->status</span>" :($redemptiontransaction->status == "rejected"? "<span class='badge bg-danger'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "paid"? "<span class='badge bg-primary'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "finished"? "<span class='badge bg-secondary'>$redemptiontransaction->status</span>" : '')))) !!}</h4>
                        <h5 class="text-center">Installer Card - {{ $redemptiontransaction->installer_card_card_number }}</h5>
                    </div>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <div class="d-flex flex-column">
                            <span>Branch - {{ $redemptiontransaction->branch->branch_name_eng }}</span>
                            <span>Installer Name - {{ $redemptiontransaction->requester }}</span>
                        </div>
                        <span>Date: {{  \Carbon\Carbon::parse($redemptiontransaction->redemption_date)->format('d-m-Y') }}</span>
                    </div>
            </div>

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
                <div class="table-responsive rounded">
                    <table class="table mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Invoice Number</th>
                                <th>Category</th>
                                <th>Group</th>
                                <th>Points Redeemed</th>
                                <th>Amount Rate</th>
                                <th>Redemption Amount</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($pointsredemptions as $idx=>$pointsredemption)
                            <tr>
                                <td>{{ ++$idx }}</td>
                                <td>{{ $pointsredemption->installercardpoint->collectiontransaction->invoice_number  }}</td>
                                <td>{{ $pointsredemption->installercardpoint->category_remark }}</td>
                                <td>{{ $pointsredemption->installercardpoint->group_name }}</td>
                                <td>{{ $pointsredemption->points_redeemed  }}</td>
                                <td>{{ $pointsredemption->points_redeemed  }} x {{ intval($pointsredemption->point_accumulated)  }}</td>
                                <td>{{ number_format($pointsredemption->redemption_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="font-weight-bold">Total Points Redeemed</td>
                                <td>{{ $redemptiontransaction->total_points_redeemed }}</td>
                                <td class="font-weight-bold">Total Amount</td>
                                <td>{{ number_format($redemptiontransaction->total_cash_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                            </tr>

                            {{-- <tr class="coupon-receive-footer2">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Points Redeemed</td>
                                <td>{{ $redemptiontransaction->total_points_redeemed }}</td>
                            </tr> --}}

                        </tbody>
                    </table>
                </div>
            </div>


            @if($redemptiontransaction->isApproveAuthUser() && $redemptiontransaction->status == 'pending')

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


            @if($redemptiontransaction->isPaidAuthUser() && $redemptiontransaction->status == 'approved')
            <div class="col-lg-12 mb-2">
                <form id="ac-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <label for="remark" class="m-0">Remark</label>
                                <textarea name="remark" id="remark" class="form-control w-100" rows="2" placeholder="Write Something...."></textarea>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="images" class="gallery @error('images') is-invalid @enderror"><span>Choose Images</span></label>
                            <input type="file" name="images[]" id="images" class="form-control form-control-sm rounded-0" value="" multiple hidden/>
                            @error("images")
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                       </div>
                        <div class="col-auto p-0">
                            <button  type="button" id="ac-paid"class="btn btn-primary mr-2">Paid</button>
                        </div>
                        <div class="col-auto p-0">
                            <button type="button" id="ac-reject" class="btn btn-danger mr-2">Reject</button>
                        </div>
                        <div class="col-auto p-0">
                            <button type="button" id="back-btn" class="btn btn-light" onclick="window.history.back();">Back</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif

            @if($redemptiontransaction->isFinishedAuthUser() && $redemptiontransaction->status == 'paid')
            <div class="col-lg-12 mb-2">
                <form action="{{ route('redemptiontransactions.finishRedemptionRequest',$redemptiontransaction->uuid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-auto">
                            <label for="images" class="gallery @error('images') is-invalid @enderror"><span>Choose Images</span></label>
                            <input type="file" name="images[]" id="images" class="form-control form-control-sm rounded-0" value="" multiple hidden/>
                            @error("images")
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-auto p-0">
                            <button type="submit" class="btn btn-success mr-2">Finished</button>
                        </div>
                        <div class="col-auto p-0">
                            <button type="button" id="back-btn" class="btn btn-light" onclick="window.history.back();">Back</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif


            {{-- @if($redemptiontransaction->isFinishedAuthUser() && $redemptiontransaction->status == 'paid')
            <div class="col-lg-12 mb-2">
                <div class="row" >
                    <div class="col-auto d-flex p-0">

                        <form action="{{ route('redemptiontransactions.finishRedemptionRequest',$redemptiontransaction->uuid) }}" method="POST">
                            @csrf
                                <button type="submit" class="btn btn-success mr-2">Finished</button>
                        </form>

                    </div>

                    <div class="col-auto p-0">
                        <button type="button" id="back-btn" class="btn btn-light" onclick="window.history.back();">Back</button>
                    </div>

                </div>
            </div>
            @endif --}}

            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                <p class="mb-1">Prepare By</p>
                <span>{{ $redemptiontransaction->prepareby->name }}</span>
                {{-- {{ dd($redemptiontransaction->prepareby->getRoleNames()) }} --}}
                {!!

                    "( ".implode(",", array_map(function($role){
                        return "<span class='roles'>$role</span>";
                        },$redemptiontransaction->prepareby->getRoleNames()->toArray())
                    )." )"

                !!}
                <span>{{ $redemptiontransaction->created_at }}</span>
                <div class="redemptiontransactionfiles">
                    <div class="row">
                        @foreach ($redemptiontransactionfiles as $redemptiontransactionfile)
                            @if($redemptiontransactionfile->user_uuid == $redemptiontransaction->prepare_by)
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset($redemptiontransactionfile->image) }}" class="redemptiontransactionimages" alt="{{$redemptiontransactionfile->redemption_transaction_uuid}}"/>
                                    <a href="{{ asset($redemptiontransactionfile->image) }}" download title="download"><i class="fas fa-download"></i></a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                <p class="mb-1">Approved By</p>
                <span class="{{ $redemptiontransaction->approvedby ? '' : 'text-muted font-weight-normal' }}">{{ $redemptiontransaction->approvedby ? $redemptiontransaction->approvedby->name : 'N/A' }}</span>
                @if($redemptiontransaction->approvedby)
                {!!

                    "( ".implode(",", array_map(function($role){
                        return "<span class='roles'>$role</span>";
                        },$redemptiontransaction->approvedby->getRoleNames()->toArray())
                    )." )"

                !!}
                @else
                        {!! "<span class='text-muted font-weight-normal roles'>(Branch Manager)</span>" !!}
                @endif
                <div class="d-flex flex-wrap ">
                    <span class="font-weight-bold text-info">"</span> <span class="mx-1 text-info">{{  $redemptiontransaction->bm_remark }}</span> <span class="font-weight-bold text-info">"</span>

                </div>
                <span class="{{ $redemptiontransaction->approved_date ? '' : 'text-muted font-weight-normal' }}">{{  $redemptiontransaction->approved_date ? $redemptiontransaction->approved_date : 'MM-DD-YYYY' }}</span>
            </div>


            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                <p class="mb-1">Paid By</p>
                <span class="{{ $redemptiontransaction->paidby ? '' : 'text-muted font-weight-normal' }}">{{ $redemptiontransaction->paidby ? $redemptiontransaction->paidby->name : 'N/A' }}</span>
                @if($redemptiontransaction->paidby)
                {!!

                    "( ".implode(",", array_map(function($role){
                        return "<span class='roles'>$role</span>";
                        },$redemptiontransaction->paidby->getRoleNames()->toArray())
                    )." )"

                !!}
                @else
                        {!! "<span class='text-muted font-weight-normal roles'>(Branch Account)</span>" !!}
                @endif
                <div class="d-flex flex-wrap ">
                    <span class="font-weight-bold text-info">"</span> <span class="mx-1 text-info">{{  $redemptiontransaction->ac_remark }}</span> <span class="font-weight-bold text-info">"</span>
                </div>
                <span class="{{ $redemptiontransaction->paid_date ? '' : 'text-muted font-weight-normal' }}">{{  $redemptiontransaction->paid_date ? $redemptiontransaction->paid_date : 'MM-DD-YYYY' }}</span>


                <div class="redemptiontransactionfiles">
                    <div class="row">

                        @foreach ($redemptiontransactionfiles as $redemptiontransactionfile)
                            @if($redemptiontransactionfile->user_uuid == $redemptiontransaction->paid_by)
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset($redemptiontransactionfile->image) }}" class="redemptiontransactionimages" alt="{{$redemptiontransactionfile->redemption_transaction_uuid}}"/>
                                    <a href="{{ asset($redemptiontransactionfile->image) }}" download title="download"><i class="fas fa-download"></i></a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>

            @if($redemptiontransaction->preusedslip)
            <div class="col-lg-12 mb-4 pb-4">
                <div class="table-responsive rounded">
                    <h5>Preused Slip</h5>
                    <table class="table table-primary mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>Action</th>
                                <th>Branch</th>
                                <th>Before Pay Credit Point</th>
                                <th>Before Pay Credit Amount</th>
                                <th>Total Point Paid</th>
                                <th>Total Accept Value</th>
                                <th>After Pay Credit Point</th>
                                <th>After Pay Credit Amount</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            <tr>
                                <td><a href="{{route('preusedslips.show',$redemptiontransaction->preusedslip->uuid)}}" class="text-primary"><i class="fas fa-eye"></i></a></td>
                                <td>{{ $redemptiontransaction->preusedslip->branch->branch_name_eng  }}</td>
                                <td>{{ $redemptiontransaction->preusedslip->before_pay_credit_points }}</td>
                                <td>{{  number_format($redemptiontransaction->preusedslip->before_pay_credit_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td>{{ $redemptiontransaction->preusedslip->total_points_paid }}</td>
                                <td>{{  number_format($redemptiontransaction->preusedslip->total_accept_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td>{{ $redemptiontransaction->preusedslip->before_pay_credit_points + $redemptiontransaction->preusedslip->total_points_paid }}</td>
                                <td>{{  number_format($redemptiontransaction->preusedslip->before_pay_credit_amount + $redemptiontransaction->preusedslip->total_accept_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif


            @if($redemptiontransaction->doubleprofitslip)
            <div class="col-lg-12 mb-4 pb-4">
                <div class="table-responsive rounded">
                    <h5>Preused Slip</h5>
                    <table class="table table-primary mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>Branch</th>
                                <th>Card Number</th>
                                <th>Collection Document No.</th>
                                <th>Total Removed Point</th>
                                <th>Total Withdraw Amount</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            <tr>
                                <td>{{ $redemptiontransaction->doubleprofitslip->branch->branch_name_eng  }}</td>
                                <td>{{ $redemptiontransaction->doubleprofitslip->installer_card_card_number  }}</td>
                                <td><a href="{{ route('collectiontransactions.show',$redemptiontransaction->doubleprofitslip->collectiontransaction->uuid) }}" class="text-underline" style="text-underline-offset: 5px;">{{$redemptiontransaction->doubleprofitslip->collectiontransaction->document_no }}</a></td>
                                <td>{{ $redemptiontransaction->doubleprofitslip->redemptiontransaction->total_points_redeemed  }}</td>
                                <td>{{  number_format($redemptiontransaction->doubleprofitslip->redemptiontransaction->total_cash_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>

{{-- Start Modal Area --}}
<div class="modal fade show_image" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="show_image_div">
        </div>
    </div>
</div>
{{-- End Modal Area--}}
@endsection
@section('js')
<script>
    $(document).ready(function() {

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
                    $('#bm-form').attr('action',"{{ route('redemptiontransactions.rejectRedemptionRequest', ['redemptiontransaction' => $redemptiontransaction->uuid, 'step' => 'bm']) }}");
                    $('#bm-form').submit();
                }
              });

        });


        $('#bm-approve').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            $('#bm-form').attr('action',"{{ route('redemptiontransactions.approveRedemptionRequest',$redemptiontransaction->uuid) }}");
            $('#bm-form').submit();

        });

        $('#ac-reject').click(function(e){
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
                    $('#ac-form').attr('action',"{{ route('redemptiontransactions.rejectRedemptionRequest', ['redemptiontransaction' => $redemptiontransaction->uuid, 'step' => 'ac']) }}");
                    $('#ac-form').submit();
                }
              });

        });


        $('#ac-paid').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            $('#ac-form').attr('action',"{{ route('redemptiontransactions.paidRedemptionRequest',$redemptiontransaction->uuid) }}");
            $('#ac-form').submit();

        });


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

                 for(var i = 0 ; i < totalfiles ; i++){
                      var filereader = new FileReader();


                      filereader.onload = function(e){
                           // $(output).html("");
                           $($.parseHTML('<img>')).attr('src',e.target.result).appendTo(output);
                      }

                      filereader.readAsDataURL(input.files[i]);

                 }
            }

       };

        $('#images').change(function(){
                previewimages(this,'.gallery');
        });


        function showImageDetail()
        {
            var src = $(this).attr("src");
            console.log(src)
{{--
                $('#show_image_div').html('');
                $('#show_image_div').append(`
                    <img class="rounded img-fluid" id="table_image" src="" alt="profile" width="" height="" style="text-align:center;">
                `);

            // $("#table_image").prop('src', src);
            $("#table_image").attr('src', src);

            $('.show_image').modal('show'); --}}
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

    });
</script>
@stop
