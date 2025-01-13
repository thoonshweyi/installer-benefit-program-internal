@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.customer_number_detail')}} for {{$promotion->name}}</h4>
                    </div>
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
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="customer_number_detail_list">
                        <thead class=" ">
                            <tr class="ligth">
                                <th>{{__('report.branch_name')}}</th>
                                <th>{{__('report.member_count')}}</th>
                                <th>{{__('report.old_customer_count')}}</th>
                                <th>{{__('report.new_customer_count')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($result as $r)
                            <tr class="ligth-data">
                                <th scope="col">{{$r[0]}}</th>
                                <th scope="col">{{$r[1]}}</th>
                                <th scope="col">{{$r[2]}}</th>
                                <th scope="col">{{$r[3]}}</th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="pull-right">
                    <a class="btn btn-light" href="{{ route('report.customer_status_number') }}"> Back</a>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')

@stop