@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="mb-3">{{__('report.customer_number_detail_on_branch')}} : {{$branch_name}}, {{__('report.total')}} :{{array_sum($customer_total)}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-lg-8">
                            <div class="table-responsive rounded mb-3">
                                <table class="table table-light">
                                    <thead>
                                        @php $i = 0; @endphp
                                        @foreach ($customer_total as $c_total)
                                        <tr>
                                            <th>{{$date_array[$i]}}</th>
                                            <th>{{ $c_total}}</th>
                                        </tr>
                                        @php $i++ @endphp

                                        @endforeach
                                    </thead>
                                    <tbody class="ligth-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="pull-right">
                                    <a class="btn btn-light" href="{{ route('report.customer_number') }}"> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
