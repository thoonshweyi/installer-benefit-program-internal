@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Installer Point Checking</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-2">
                <form action="{{ route('installercardpoints.find') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="installer_card_card_number" class="form-control form-control-sm" name="installer_card_card_number" placeholder="Enter Card Number" value="{{ request()->installer_card_card_number }}"/>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" id="findbtn" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-12">
                <form action="" method="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="card_number">Card Number:</label>
                                </div>

                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <input type="text" id="card_number" name="card_number" class="form-control form-control-sm rounded-0" readonly/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="name">Installer Name:</label>
                                </div>

                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <input type="text" id="name" name="name" class="form-control form-control-sm rounded-0" readonly/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="phone">Phone No:</label>
                                </div>

                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <input type="text" id="phone" name="phone" class="form-control form-control-sm rounded-0" readonly/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="nrc">NRC:</label>
                                </div>

                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <input type="text" id="nrc" name="nrc" class="form-control form-control-sm rounded-0" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="name" class="text-warning font-weight-bold">Total Points:</label>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="totalpoints" name="totalpoints" class="form-control form-control-sm rounded-0 font-weight-bold" readonly style="font-size: 18px"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-4">
                                    <label for="name" class="text-warning font-weight-bold">Total Amount:</label>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="totalamount" name="totalamount" class="form-control form-control-sm rounded-0 font-weight-bold" readonly style="font-size: 18px"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>

            <div class="col-lg-12 mb-2">
                <form id="searchform" action="" method="GET">
                    <div class="row justify-content-end align-items-end">
                        <input type="hidden" id="search_card_number" name="search_card_number" class="" value=""/>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="" class="form-control form-control-sm" name="invoice_number" placeholder="Enter Invoice Number" value="{{ request()->get('invoice_number') }}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="{{ request()->get('from_date') ? 'date' : 'text'  }}" name="from_date" id="from_date" class="from_date form-control form-control-sm" placeholder="From Date: mm/dd/yyyy" onfocus="(this.type='date')" onchange='changeHandler(this)' value="{{ request()->get('from_date')}}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="{{ request()->get('to_date') ? 'date' : 'text'  }}" name="to_date" id="to_date" class="to_date form-control form-control-sm" placeholder="To Date: mm/dd/yyyy" onfocus="(this.type='date')" onchange="changeHandler(this)" value="{{ request()->get('to_date') }}">
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="button" id="search_btn" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-12">
                <div class="rounded mb-3 table-container">
                    <table class="table mb-0 tbl-server-info" id="">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Invoice Number</th>
                                <th>Category</th>
                                <th>Group</th>
                                <th>Sale Amount</th>
                                <th>Point Earned</th>
                                <th>Point Redeemed</th>
                                <th>Point Balance</th>
                                <th>Coupon Name</th>
                                <th>Amount Earned</th>
                                <th>Amount Redeemed</th>
                                <th>Amount Balance</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">


                        </tbody>
                    </table>
                </div>
                <div class="myloader">
                    <div class="loader-item"></div>
                    <div class="loader-item"></div>
                    <div class="loader-item"></div>
                </div>
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

        $('#findbtn').click(function(){
            const card_number = $("#installer_card_card_number").val();
            {{-- console.log(card_number); --}}

            $.ajax({
                url:"{{route('installercardpoints.find')}}",
                method:"GET",
                data:{"card_number":card_number},
                beforeSend:function(){
                    $(".myloader").addClass("show");
                },
                success:function(response){
                     {{-- console.log(response); --}}

                     if(response.installercard){
                        $('#card_number').val(response.installercard.card_number);
                        $('#name').val(response.installercard.fullname);
                        $('#phone').val(response.installercard.phone);
                        $('#nrc').val(response.installercard.nrc);
                        $('#totalpoints').val(response.installercard.totalpoints);
                        $('#totalamount').val(parseInt(response.installercard.totalamount));

                        let htmlview="";

                        installercardpoints = response.installercardpoints;

                        if(installercardpoints.length > 0){
                            installercardpoints.forEach((installercardpoint,idx)=>{
                                htmlview += `
                                    <tr class="installercardpoint ${installercardpoint.is_redeemed == 1 ? 'redeemed' : ''}">
                                        <td>${++idx}</td>
                                        <td>${installercardpoint.collectiontransaction.invoice_number}</td>
                                        <td>${installercardpoint.category_remark}</td>
                                        <td>${installercardpoint.group_name}</td>
                                        <td>${parseInt(installercardpoint.saleamount)}</td>
                                        <td>${installercardpoint.points_earned}</td>
                                        <td>${installercardpoint.points_redeemed}</td>
                                        <td>${installercardpoint.points_balance}</td>
                                        <td>${installercardpoint.points_earned} x ${ parseInt(installercardpoint.point_based)}</td>
                                        <td>${parseInt(installercardpoint.amount_earned)} <span class="ms-4">MMK</span></td>
                                        <td>${parseInt(installercardpoint.amount_redeemed)}</td>
                                        <td>${parseInt(installercardpoint.amount_balance)}</td>
                                    </tr>

                                `;
                            });
                        }else{
                            htmlview += `
                            <tr>
                                <td class="text-danger" colspan="10">
                                    No Data
                                </td>
                            </tr>

                            `;
                        }

                        $("#search_card_number").val(response.installercard.card_number);


                        $("#tabledata").html(htmlview);
                     }else{
                        {{-- console.log('Not found'); --}}
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: "error"
                        });
                     }

                },
                error:function(response){
                    console.log(response);
                },
                complete:function(){
                    {{-- console.log("complete:"); --}}
                    $(".myloader").removeClass("show");
                }
           });
        });
        @if(request()->installer_card_card_number)
            $('#findbtn').trigger('click');
        @endif

        $('#search_btn').click(function(){
            const search_card_number = $("#search_card_number").val();
            {{-- console.log(card_number); --}}

            if(search_card_number){
                $.ajax({
                    url:`{{route('installercardpoints.search',':card_number')}}`.replace(':card_number', search_card_number),
                    method:"GET",
                    data: $('#searchform').serialize(),
                    beforeSend:function(){
                        $(".myloader").addClass("show");
                    },
                    success:function(response){
                         {{-- console.log(response); --}}

                         if(response.installercard){

                            let htmlview="";

                            installercardpoints = response.installercardpoints;

                            if(installercardpoints.length > 0){
                                installercardpoints.forEach((installercardpoint,idx)=>{
                                    htmlview += `
                                        <tr>
                                            <td>${++idx}</td>
                                            <td>${installercardpoint.collectiontransaction.invoice_number}</td>
                                            <td>${installercardpoint.category_remark}</td>
                                            <td>${installercardpoint.group_name}</td>
                                            <td>${parseInt(installercardpoint.saleamount)}</td>
                                            <td>${installercardpoint.points_earned}</td>
                                            <td>${installercardpoint.points_redeemed}</td>
                                            <td>${installercardpoint.points_balance}</td>
                                            <td>${installercardpoint.points_earned} x ${ parseInt(installercardpoint.point_based)}</td>
                                            <td>${parseInt(installercardpoint.amount_earned)} <span class="ms-4">MMK</span></td>
                                            <td>${parseInt(installercardpoint.amount_redeemed)}</td>
                                            <td>${parseInt(installercardpoint.amount_balance)}</td>
                                        </tr>

                                    `;
                                });
                            }else{
                                htmlview += `
                                <tr>
                                    <td class="text-danger" colspan="10">
                                        No Data
                                    </td>
                                </tr>

                                `;
                            }


                            $("#tabledata").html(htmlview);
                         }else{
                            {{-- console.log('Not found'); --}}
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                icon: "error"
                            });
                         }

                    },
                    error:function(response){
                        console.log(response);
                    },
                    complete:function(){
                        {{-- console.log("complete:"); --}}
                        $(".myloader").removeClass("show");
                    }
                });
            }else{
                Swal.fire({
                    title: "Oops, Installer Card Not Found",
                    text: "Installer Card Number Incorrect!!",
                    icon: "error"
                });
            }


        });


    });
</script>
@stop
