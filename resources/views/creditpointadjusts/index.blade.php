@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Credit Point Adjust</h4>
                    </div>
                </div>
            </div>


            {{-- <div class="col-lg-12 mb-2">
                <form action="{{ route('creditpointadjusts.search') }}" method="GET">
                    <div class="row justify-content-end">
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="querycard_number" id="inscardnumber" class="form-control form-control-sm" placeholder="Enter Installer Card Number" value="{{ request()->get('querycard_number') }}">
                        </div>
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="querynrc" id="querynrc" class="form-control form-control-sm" placeholder="Enter NRC Number" value="{{ request()->get('querynrc') }}"/>
                        </div>
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="queryphone" id="queryphone" class="form-control form-control-sm" placeholder="Enter Phone Number" value="{{ request()->get('queryphone') }}"/>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="search-btn" class="btn btn-primary rounded">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light" onclick="window.location.href = window.location.href.split('?')[0];"><i class="fas fa-sync-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
            </div> --}}
            <hr/>
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

            <div class="col-lg-12 loader-container">
                <div class="rounded mb-3 table-container">
                    <table class="table mb-0 tbl-server-info" id="">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Action</th>
                                <th>Branch</th>
                                <th>Document No.</th>
                                <th>Card Number</th>
                                <th>Status</th>
                                <th>Total Points Adjusted</th>
                                <th>Total Adjust Value</th>
                                <th>By</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach($creditpointadjusts as $idx=>$creditpointadjust)
                                <tr>
                                    <td>
                                        {{ $idx + $creditpointadjusts->firstItem() }}
                                    </td>
                                    <td class="">
                                        <div class="d-flex justify-content-start">
                                            <a href="{{ route('creditpointadjusts.edit',$creditpointadjust->uuid) }}" class="mr-2" title="Edit"><i class="fas fa-edit"></i></a>
                                        </div>
                                    </td>
                                    <td>{{ $creditpointadjust->branch->branch_name_eng }}</td>
                                    <td>{{ $creditpointadjust->document_no }}</td>
                                    <td>{{ $creditpointadjust->installer_card_card_number }}</td>
                                    <td>
                                        {!! $creditpointadjust->status == "pending" ?"<span class='badge bg-warning'>$creditpointadjust->status</span>" :
                                        ($creditpointadjust->status == "approved" ? "<span class='badge bg-success'>$creditpointadjust->status</span>" :
                                        ($creditpointadjust->status == "rejected"? "<span class='badge bg-danger'>$creditpointadjust->status</span>" : ""
                                        )) !!}
                                    </td>
                                    <td>
                                        {{ $creditpointadjust->total_points_adjusted }}
                                    </td>
                                    <td>
                                        {{ $creditpointadjust->total_adjust_value }}
                                    </td>
                                    <td>{{ $creditpointadjust->user->name  }}</td>
                                    <td>{{  \Carbon\Carbon::parse($creditpointadjust->created_at)->format('d-m-Y h:m:s A') }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $creditpointadjusts->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                {{-- {{ dd($creditpointadjusts) }} --}}
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
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endsection
