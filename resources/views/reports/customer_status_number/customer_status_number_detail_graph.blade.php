@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.customer_status_number_detail_graph')}} for {{$promotion->name}} </h4>
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
            <div class="col-sm-12 col-lg-12">
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Customer campaign portion</h4>
                     </div>
                  </div>
                  <div class="card-body">
                    <div id="customer-campain-portion-chart" style="height: 500px;"></div>
                  </div>
               </div>
            </div>

            <div class="col-sm-12 col-lg-12 row">
                <div class="col-lg-6">    
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Customer campaign portion (All Branch)</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="all-branch-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Lanthit</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="lanthit-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Satsan</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="satsan-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Eastdagon</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="eastdagon-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Hlaingthaya</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="hlaingthaya-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Terminal M</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="terminal-m-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Theikpan</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="theikpen-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Tampawady</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="tampawady-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Aye Thayar</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="ayethayar-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Mawlamyine</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="mawlamyine-pie-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">       
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">South Dagon</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="southdagon-pie-chart"></div>
                        </div>
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


        //Customer campaign portion
        am4core.ready(function() {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("customer-campain-portion-chart", am4charts.XYChart);
            chart.colors.list = [
                am4core.color("#ff4b4b"),
                am4core.color("#37e6b0"),
                am4core.color("#fe721c")
            ];

            // Add data
            chart.data = [
                {
                    "branch": "Lanthit",
                    "member": {{$lanthit_member_total}},
                    "old_customer": {{$lanthit_old_customer_total}},
                    "new_customer": {{$lanthit_new_customer_total}},
                }, 
                {
                    "branch": "Satsan",
                    "member": {{$satsan_member_total}},
                    "old_customer":{{$satsan_old_customer_total}},
                    "new_customer": {{$satsan_new_customer_total}},
                }, 
                {
                    "branch": "Eastdagon",
                    "member": {{$eastdagon_member_total}},
                    "old_customer":{{$eastdagon_old_customer_total}},
                    "new_customer": {{$eastdagon_new_customer_total}},
                },
                {
                    "branch": "Hlaingthaya",
                    "member": {{$hlaingthaya_member_total}},
                    "old_customer":{{$hlaingthaya_old_customer_total}},
                    "new_customer": {{$hlaingthaya_new_customer_total}},
                },
                {
                    "branch": "Terminal M",
                    "member": {{$terminal_m_member_total}},
                    "old_customer":{{$terminal_m_old_customer_total}},
                    "new_customer": {{$terminal_m_new_customer_total}},
                },
                {
                    "branch": "Theikpan",
                    "member": {{$theikpan_member_total}},
                    "old_customer":{{$theikpan_old_customer_total}},
                    "new_customer": {{$theikpan_new_customer_total}},
                },
                {
                    "branch": "Tampawady",
                    "member": {{$tampawady_member_total}},
                    "old_customer":{{$tampawady_old_customer_total}},
                    "new_customer": {{$tampawady_new_customer_total}},
                },
                {
                    "branch": "Aye Thayar",
                    "member": {{$aye_thayar_member_total}},
                    "old_customer":{{$aye_thayar_old_customer_total}},
                    "new_customer": {{$aye_thayar_new_customer_total}},
                },
                {
                    "branch": "Mawlamyine",
                    "member": {{$mawlamyine_member_total}},
                    "old_customer":{{$mawlamyine_old_customer_total}},
                    "new_customer": {{$mawlamyine_new_customer_total}},
                },
                {
                    "branch": "South Dagon",
                    "member": {{$southdagon_member_total}},
                    "old_customer":{{$southdagon_old_customer_total}},
                    "new_customer": {{$southdagon_new_customer_total}},
                }
            ];

            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "branch";
            categoryAxis.renderer.grid.template.location = 0;


            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inside = true;
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.min = 0;

            // Create series
            function createSeries(field, name) {
                // Set up series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.name = name;
                series.dataFields.valueY = field;
                series.dataFields.categoryX = "branch";
                series.sequencedInterpolation = true;

                // Make it stacked
                series.stacked = true;

                // Configure columns
                series.columns.template.width = am4core.percent(60);
                series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";

                // Add label
                var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "{valueY}";
                labelBullet.locationY = 0.5;

                return series;
            }

            createSeries("member", "Member");
            createSeries("old_customer", "Old Customer");
            createSeries("new_customer", "New Customer");
            // Legend
            chart.legend = new am4charts.Legend();

            const body = document.querySelector('body')
            if (body.classList.contains('dark')) {
                amChartUpdate(chart, {
                dark: true
                })
            }

            document.addEventListener('ChangeColorMode', function (e) {
                amChartUpdate(chart, e.detail)
            })

        }); // end am4core.ready()

        //All Customer campaign portion 
        options = {
            chart: {
            width: 380,
            type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$total_member}}, {{$total_old_customer}}, {{$total_new_customer}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart = new ApexCharts(document.querySelector("#all-branch-pie-chart"), options)).render()
       
         //Lanthit campaign portion 
         options1 = {
            chart: {
            width: 380,
            type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$lanthit_member_total}}, {{$lanthit_old_customer_total}}, {{$lanthit_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart1 = new ApexCharts(document.querySelector("#lanthit-pie-chart"), options1)).render()
    

        //Satsan campaign portion 
        options2 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$satsan_member_total}}, {{$satsan_old_customer_total}}, {{$satsan_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart2 = new ApexCharts(document.querySelector("#satsan-pie-chart"), options2)).render()
       

        //East Dagon campaign portion 
        options3 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$eastdagon_member_total}}, {{$eastdagon_old_customer_total}}, {{$eastdagon_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart3 = new ApexCharts(document.querySelector("#eastdagon-pie-chart"), options3)).render()
    
        //Hlaingthaya campaign portion 
        options4 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$hlaingthaya_member_total}}, {{$hlaingthaya_old_customer_total}}, {{$hlaingthaya_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart4 = new ApexCharts(document.querySelector("#hlaingthaya-pie-chart"), options4)).render()
    
        //Terminal M campaign portion 
        options5 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$terminal_m_member_total}}, {{$terminal_m_old_customer_total}}, {{$terminal_m_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart5 = new ApexCharts(document.querySelector("#terminal-m-pie-chart"), options5)).render()
       
        //Theikpan campaign portion 
        options6 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$theikpan_member_total}}, {{$theikpan_old_customer_total}}, {{$theikpan_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart6 = new ApexCharts(document.querySelector("#theikpen-pie-chart"), options6)).render()

        //Tampawady campaign portion 
        options7 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$tampawady_member_total}}, {{$tampawady_old_customer_total}}, {{$tampawady_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart7 = new ApexCharts(document.querySelector("#tampawady-pie-chart"), options7)).render()

        //Aye Thayar campaign portion 
        options8 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$aye_thayar_member_total}}, {{$aye_thayar_old_customer_total}}, {{$aye_thayar_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart8 = new ApexCharts(document.querySelector("#ayethayar-pie-chart"), options8)).render()

        //Mawlamyine campaign portion 
        options9 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$mawlamyine_member_total}}, {{$mawlamyine_old_customer_total}}, {{$mawlamyine_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart9 = new ApexCharts(document.querySelector("#mawlamyine-pie-chart"), options9)).render()

        //South Dagon campaign portion 
        options10 = {
            chart: {
                width: 380,
                type: "pie"
            },
            labels: ["Member", "Old Customer", "New Customer"],
            series: [{{$southdagon_member_total}}, {{$southdagon_old_customer_total}}, {{$southdagon_new_customer_total}}],
            colors: ["#ff4b4b", "#37e6b0", "#fe721c"],
            responsive: [{
            breakpoint: 480,
            options1: {
                chart: {
                width: 200
                },
                legend: {
                position: "bottom"
                }
            }
            }]
        };
        (chart10 = new ApexCharts(document.querySelector("#southdagon-pie-chart"), options10)).render()
    })

    $('#search').on('click', function(e) {
        $('#lucky_draw_list').DataTable().draw(true);
    })

    
</script>
@stop