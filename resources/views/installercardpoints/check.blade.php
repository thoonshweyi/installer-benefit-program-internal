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
                <form action="{{ route('installercardpoints.search',$installercard->card_number) }}" method="GET">
                    <div class="row justify-content-end align-items-end">
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
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>

                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light"><i class="fas fa-sync-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-12">
                {{-- <div class="rounded mb-3 table-container" style="min-height: 80vh;"> --}}
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
                            @foreach ($installercardpoints as $idx => $installercardpoint)
                                <tr class="installercardpoint {{ $installercardpoint->is_redeemed == 1 ? 'redeemed' : '' }}">
                                    <td>{{$idx + $installercardpoints->firstItem()}}</td>
                                    <td>{{ $installercardpoint->collectiontransaction->invoice_number }}</td>
                                    <td>{{ $installercardpoint->category_remark }}</td>
                                    <td>{{ $installercardpoint->group_name }}</td>
                                    <td>{{ number_format($installercardpoint->saleamount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                    <td>{{ $installercardpoint->points_earned }}</td>
                                    <td>{{ $installercardpoint->points_redeemed }}</td>
                                    <td>{{ $installercardpoint->points_balance }}</td>
                                    <td>{{ $installercardpoint->points_earned }} x {{ intval($installercardpoint->point_based) }}</td>
                                    <td>{{ number_format($installercardpoint->amount_earned,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                    <td>{{ intval($installercardpoint->amount_redeemed) }}</td>
                                    <td>{{ intval($installercardpoint->amount_balance) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $installercardpoints->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
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






    });

    // Start Clear btn
    document.getElementById("btn-clear").addEventListener("click",function(){
        window.location.href = window.location.href.split("?")[0];
   });
   // End Clear btn
</script>
@stop
