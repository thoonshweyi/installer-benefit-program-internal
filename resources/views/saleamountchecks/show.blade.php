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
                    <h4 class="mb-3 text-center text-danger"><span>Sale Amount Check</span></h4>
                    <h5 class="text-center">Primary Phone - {{ $saleamountcheck->primary_phone }}</h5>
                </div>
                <div class="d-flex justify-content-between font-weight-bold">
                    <div class="d-flex flex-column">
                        <span>Branch - {{ $saleamountcheck->branch->branch_name_eng }}</span>
                    </div>
                    {{-- <span>Date: {{  \Carbon\Carbon::parse($returnbanner->return_action_date)->format('d-m-Y') }}</span> --}}
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
                                <th>Customer Bar Code</th>
                                <th>Phone</th>
                                <th>Sale Amount</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($cussaleamounts as $idx=>$cussaleamount)
                            <tr class="{{ $cussaleamount->phone == $saleamountcheck->primary_phone ? 'bg-primary text-light' : ''  }}">
                                <td>{{ ++$idx }}</td>
                                <td>{{ $cussaleamount->customer_barcode  }}</td>
                                <td >{{ $cussaleamount->phone  }}</td>
                                <td>{{ number_format($cussaleamount->sale_amount,0,'.',',') }} MMK</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total Sale Amount</td>
                                <td>{{ number_format($saleamountcheck->total_sale_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                            </tr>

                            {{-- <tr class="coupon-receive-footer2">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Points Redeemed</td>
                                <td>{{ $collectiontransaction->total_points_redeemed }}</td>
                            </tr> --}}

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12 mb-2">
                <button type="button" id="back-btn" class="btn btn-light mr-2" onclick="window.history.back();">Back</button>
            </div>


            <div class="col-md-3 mb-4 mb-md-0 transactionfooters">
                <p class="mb-1">Check By</p>
                <span>{{ $saleamountcheck->user->name }}</span>
                {{-- {{ dd($redemptiontransaction->prepareby->getRoleNames()) }} --}}
                {!!

                    "( ".implode(",", array_map(function($role){
                        return "<span class='roles'>$role</span>";
                        },$saleamountcheck->user->getRoleNames()->toArray())
                    )." )"

                !!}
                <span>{{ $saleamountcheck->created_at }}</span>
            </div>

        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
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
                    console.log($(this).closest('form'));
                    $('#irerejectform').submit();
                }
              });

        });




        {{-- Start Return Part --}}
        $('#open_return_btn').click(function () {
            $('#return_parts').toggleClass('active');
            $('#return_product_docno').focus();
        });

        $(document).keypress(function(event) {
            console.log(event.target);
            if(event.target.name == 'return_product_docno' && $('#return_parts').hasClass('active')){
                // Check if the input is readonly and prevent manual typing
                var inputField = $('#return_product_docno');
                if (inputField.prop('readonly')) {
                    // Append the scanned character to the input field value
                    if (event.key !== 'Enter') {
                        inputField.val(inputField.val() + event.key);
                    }

                    // Prevent form submission when 'Enter' key is pressed by the scanner
                    if (event.key === 'Enter') {
                        event.preventDefault();  // Prevent form submission

                        console.log('Scanned QR Code:', inputField.val());

                        $('#return-product-form').submit();
                    }
                }
            }else{
                event.preventDefault();  // Prevent form submission
                Swal.fire({
                    icon: "warning",
                    title: "Return Box is not yet opened",
                    text: "Please open return box first.",
                });
            }
        });


        {{-- End Return Part --}}
    });
</script>
@stop
