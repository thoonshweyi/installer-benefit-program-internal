@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.ticket_history')}}</h4>
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
            <div class="col-lg-12 d-flex mb-2">
                <div class="form-row col-md-2">
                    <label>{{__('report.promotion_name')}} </label>
                    <input type="text" class="form-control" id="promotion_name" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('report.start_date')}} </label>
                    <input type="date" class="form-control" id="start_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('report.end_date')}} </label>
                    <input type="date" class="form-control" id="end_date" value="">
                </div>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
                @can('export-document-admin')
                <button id="document_export" class="btn btn-success">{{__('button.product_excel_export')}}</button>
                @endcan
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="ticket_history_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('report.promotion_name')}}</th>
                                <th>{{__('lucky_draw.start_date')}}</th>
                                <th>{{__('lucky_draw.end_date')}}</th>
                                <th>{{__('lucky_draw.status')}}</th>
                                <th>{{__('lucky_draw.action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                        </tbody>
                    </table>
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


        var table = $('#ticket_history_list').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "autoWidth": true,
            "responsive": true,
            "pageLength": 10,
            "scrollY": "450px",
            "scrollCollapse": true,
            'ajax': {
                'url': "/reports/ticket_history_search",
                'type': 'GET',
                'data': function(d) {
                    d.promotion_name = $('#promotion_name').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    render: function(data, type, row) {
                        if(data == 1){
                            return 'Active';
                        }else if(data == 2){
                            return 'Inactive';
                        }else if(data == 3){
                            return 'Pending';
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-secondary mr-2" data-toggle="tooltip" data-placement="top" title="Table_Report" data-original-title="Edit"
                                        href="/reports/ticket_history/${row.uuid}"><i class="ri-eye-line mr-0"></i></a>
                                    @can('export-report')
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Excel_Report" data-original-title="Edit"
                                        href="/reports/ticket_detail_export/${row.uuid}"><i class="ri-file-excel-2-line mr-0"></i></a>
                                    @endcan
                                </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        $('#search').on('click', function(e) {
            $('#ticket_history_list').DataTable().draw(true);
        })
    });
</script>
@stop