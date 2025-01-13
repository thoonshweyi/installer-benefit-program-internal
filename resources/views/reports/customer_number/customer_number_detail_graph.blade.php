@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.customer_number_detail_graph')}}</h4>
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
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.start_date')}} </label>
                    <input type="date" class="form-control" id="start_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.end_date')}} </label>
                    <input type="date" class="form-control" id="end_date" value="">
                </div>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.back')}}</button>
            </div> -->
            <div class="col-sm-12 col-lg-12">
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Customer campaign number</h4>
                     </div>
                  </div>
                  <div class="card-body">
                     <div id="customer_campain_number"></div>
                  </div>
               </div>
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Customer campaign number by day</h4>
                     </div>
                  </div>
                  <div class="card-body" style="padding-bottom:200px">
                     <div id="customer_campain_number_by_day"></div>
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
    $(document).ready(function() {
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
            colors: ["#4788ff"],
            series: [
                {
                    name: ['Customer Count'],
                    data: [
                        {{$lanthit_customer_total}}, {{$satsan_customer_total}},{{$eastdagon_customer_total}},
                        {{$hlaingthaya_customer_total}},{{$terminal_m_customer_total}},{{$theikpan_customer_total}},
                        {{$tampawady_customer_total}},{{$aye_thayar_customer_total}},{{$mawlamyine_customer_total}},
                        {{$southdagon_customer_total}}
                        ]
                    }
                ],
                xaxis: {
                    categories:["Lanthit","Satsan","Eastdagon","Hlaingthaya","Terminal M","Theikpan","Tampawady","Aye Thayar","Mawlamyine","South Dagon"],
                },
            fill: {
                opacity: 1
            }
        };
        (chart = new ApexCharts(document.querySelector("#customer_campain_number"), options1)).render()
        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
            apexChartUpdate(chart, {
                dark: true
            })
        }
        
        document.addEventListener('ChangeColorMode', function (e) {
            apexChartUpdate(chart, e.detail)
        })

        //Customer campaign number by day
        options = {
            chart: {
            height: 350,
            type: "line",
            zoom: {
                enabled: !1
            }
            },
            colors: ["#f1948a","#c39bd3","#bb8fce","#7FB3D5","#85C1E9","#76D7C4","#73C6B6","#82E0AA"],
            series: [
                {
                    name: "Lanthit",
                    data: {{json_encode($lanthit_customer_total_detail)}}
                },
                {
                    name: "Satsan",
                    data: {{json_encode($satsan_customer_total_detail)}}
                },
                {
                    name: "Eastdagon",
                    data: {{json_encode($eastdagon_customer_total_detail)}}
                },
                {
                    name: "Hlaingthaya",
                    data: {{json_encode($hlaingthaya_customer_total_detail)}}
                },
                {
                    name: "Terminal M",
                    data: {{json_encode($terminal_m_customer_total_detail)}}
                },
                {
                    name: "Theikpan",
                    data: {{json_encode($theikpan_customer_total_detail)}}
                },
                {
                    name: "Tampawady",
                    data: {{json_encode($tampawady_customer_total_detail)}}
                },
                {
                    name: "Aye Thayar",
                    data: {{json_encode($aye_thayar_customer_total_detail)}}
                },
                {
                    name: "Mawlamyine",
                    data: {{json_encode($mawlamyine_customer_total_detail)}}
                },
                {
                    name: "South Dagon",
                    data: {{json_encode($southdagon_customer_total_detail)}}
                },
            ],
            dataLabels: {
            enabled: !1
            },
            stroke: {
            curve: "straight"
            },
            grid: {
            row: {
                colors: ["#f3f3f3", "transparent"],
                opacity: .5
            }
            },
            xaxis: {
                categories:<?php echo json_encode($days) ?>,
            }
        };
        if(typeof ApexCharts !== typeof undefined){
            (chart = new ApexCharts(document.querySelector("#customer_campain_number_by_day"), options)).render()
            const body = document.querySelector('body')
            if (body.classList.contains('dark')) {
            apexChartUpdate(chart, {
                dark: true
            })
            }

            document.addEventListener('ChangeColorMode', function (e) {
            apexChartUpdate(chart, e.detail)
            })
        }
        
    })

    $('#search').on('click', function(e) {
        $('#lucky_draw_list').DataTable().draw(true);
    })

    
</script>
@stop