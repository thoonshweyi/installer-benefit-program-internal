@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.customer_number_compare')}}</h4>
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
            <div class="card-body">
                <form action="{{route('report.calculate_customer_number_compare')}}" method="get" enctype="multipart/form-data"  onsubmit="return validateForm()">
                <div class="row">
                    @csrf
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('report.compare_name')}} </label>
                                @if(isset($compare_name))
                                <select name="compare_name" id="compare_name" class="form-control" required>
                                        <option value="1" {{$compare_name == 1 ? 'selected' : ''}}>Customer Compare</option>
                                        <option value="2" {{$compare_name == 2 ? 'selected' : ''}}>Coupon Compare</option>
                                </select>
                                @else
                                <select name="compare_name" id="compare_name" class="form-control" required>
                                        <option value="1">Customer Compare</option>
                                        <option value="2">Coupon Compare</option>
                                </select>
                                @endif
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('report.promotion')}} </label>
                                @if(isset($compare_name))
                                <select name="promotion_uuid[]" id="promotion_uuid" class="form-control" multiple required>
                                    @foreach($promotions as $promotion)
                                        <option value="{{ $promotion->uuid }}" {{ in_array($promotion->uuid, $used_promotions->pluck('id')->toarray() ?: []) ? 'selected' : '' }}>
                                            {{ $promotion->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @else
                                <select name="promotion_uuid[]" id="promotion_uuid" class="form-control" multiple required>
                                    @foreach($promotions as $promotion)
                                        <option value="{{ $promotion->uuid }}" {{ ($promotion->uuid == old("promotion_uuid")) ? 'selected' : '' }}>
                                            {{ $promotion->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        <button class="btn btn-primary col-md-1 mr-2" type="submit" id="search_report">{{ __('button.search') }}</button>
                        @can('export-report')
                        <button class="btn btn-success col-md-1 mr-2" id="export_report">{{ __('button.export') }}</button>
                        @endcan
                    </div>  
                </form>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-12">
                @if(isset($title_name))
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="mb-3"> {{isset($title_name) ? $title_name : '' }} Table</h4>
                            </div>
                        </div>
                       
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="table-responsive rounded mb-3">
                                            <table class="table table-light">
                                                <thead>
                                                    <tr>
                                                        @foreach($header as $h)
                                                        <th>{{$h}}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="ligth-body">
                                                    @foreach($result as $r)
                                                    <tr>
                                                        @foreach($r as $re)
                                                        <th>{{$re}}</th>
                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                <h4 class="mb-3"> {{isset($title_name) ? $title_name : '' }} Graph</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="coupon_campain_number_by_day"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>
    function validateForm() {
        if ($('#promotion_uuid :selected').length < 2) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_to_choose_two_promotion') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#document_remark').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_remark') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
    }
    $(document).ready(function() {
        $('#promotion_uuid').select2({
            width: '100%',
            allowClear: true,
        });
        options1 = {
            chart: {
                height: 350,
                type: "bar"
                },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "80%",
                }
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                show: !0,
                width: 2,
                colors: ["transparent"]
            },
            colors: ["#4788ff", "#37e6b0", "#ff4b4b"],
            series: [
                {
                    name: "{{$promotion1_name}}",
                    data: ["{{$promotion1_lanthit}}","{{$promotion1_satsan}}","{{$promotion1_eastdagon}}","{{$promotion1_hlaingthaya}}","{{$promotion1_terminal_m}}","{{$promotion1_theikpan}}","{{$promotion1_tampawady}}","{{$promotion1_aye_thayar}}","{{$promotion1_mawlamyine}}","{{$promotion1_southdagon}}"]
                },
                {
                    name: "{{$promotion2_name}}",
                    data: ["{{$promotion2_lanthit}}","{{$promotion2_satsan}}","{{$promotion2_eastdagon}}","{{$promotion2_hlaingthaya}}","{{$promotion2_terminal_m}}","{{$promotion2_theikpan}}","{{$promotion2_tampawady}}","{{$promotion2_aye_thayar}}","{{$promotion2_mawlamyine}}","{{$promotion2_southdagon}}"]
                }
            ],
            xaxis: {
                categories:["Lanthit","Satsan","Eastdagon","Hlaingthaya","Terminal M","Theikpan","Tampawady","Aye Thayar","Mawlamyine","South Dagon"],
            },
            fill: {
            opacity: 1
            }
        };
        (chart = new ApexCharts(document.querySelector("#coupon_campain_number_by_day"), options1)).render()
        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
            apexChartUpdate(chart, {
            dark: true
            })
        }

        document.addEventListener('ChangeColorMode', function (e) {
            apexChartUpdate(chart, e.detail)
        })

        $('#export_report').on('click', function(e) {
       
            var compare_name = $('#compare_name').val();
            var promotion_uuid = $('#promotion_uuid').val();
           
            if ($('#promotion_uuid').val() == ""  ) {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.need_promotions') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }else{
                var url = `/reports/customer_number_compare_export/${compare_name}/${promotion_uuid}`;
                window.location = url;
            }
            event.preventDefault();
            
        })
    });
</script>
@stop
