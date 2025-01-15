@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.coupon_number_detail')}} for {{$promotion->name}}, {{__('report.total')}} : {{$total}}</h4>
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
            <!-- <div class="col-lg-12 d-flex mb-2">
                <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$promotion->uuid}}" />

                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.start_date')}} </label>
                    <input type="date" class="form-control" id="start_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.end_date')}} </label>
                    <input type="date" class="form-control" id="end_date" value="">
                </div>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
            </div> -->
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="customer_number_detail_list">
                        <thead class=" ">
                            <tr class="ligth">
                                <th>{{__('report.branch_name')}}</th>
                                <th>{{__('report.customer_count')}}</th>
                                <th>{{__('lucky_draw.action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                        <tr class="ligth-data">
                                <th scope="col">Lanthit</th>
                                <th scope="col">{{$lanthit_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/1"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Satsan</th>
                                <th scope="col">{{$satsan_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/3"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Eastdagon</th>
                                <th scope="col">{{$eastdagon_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/9"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Hlaingthaya</th>
                                <th scope="col">{{$hlaingthaya_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/19"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Terminal M</th>
                                <th scope="col">{{$terminal_m_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/27"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Theikpan</th>
                                <th scope="col">{{$theikpan_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/2"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Tampawady</th>
                                <th scope="col">{{$tampawady_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/11"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Aye Thayar</th>
                                <th scope="col">{{$aye_thayar_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/21"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">Mawlamyine</th>
                                <th scope="col">{{$mawlamyine_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/10"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                            <tr class="ligth-data">
                                <th scope="col">South Dagon</th>
                                <th scope="col">{{$southdagon_coupon_total}}</th>
                                <th scope="col">
                                    <a class="badge bg-success mr-2"  title="Detail" href="../../reports/one_coupon_number_detail/{{$promotion->uuid}}/28"><i class="ri-eye-line mr-0"></i></a>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="pull-right">
                        <a class="btn btn-light" href="{{ route('report.coupon_number') }}">Back</a>
                    </div>
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
    
</script>
@stop