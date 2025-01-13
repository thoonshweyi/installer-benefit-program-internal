@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.ticket_history_detail')}} for {{$promotion->name}}</h4>
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
                <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$promotion->uuid}}" />

                <div class="form-row col-md-2">
                    <label>{{__('report.invoice_no')}} </label>
                    <input type="text" class="form-control" id="invoice_no" value="">
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
                                <th>{{__('report.ticket_no')}}</th>
                                <th>{{__('report.created_date')}}</th>
                                <th>{{__('report.branch_name')}}</th>
                                <th>{{__('report.customer_name')}}</th>
                                <th>{{__('report.action')}}</th>
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
                'url': "/reports/ticket_history_detail_search",
                'type': 'GET',
                'data': function(d) {
                    d.promotion_uuid = $('#promotion_uuid').val();
                    d.invoice_no = $('#invoice_no').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {
                    data: 'ticket_no',
                    name: 'ticket_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                // {
                //     data: 'invoice_no',
                //     name: 'invoice_no',
                //     orderable: true,
                //     render: function(data, type, row) {
                //         return data;
                //     }
                // },
                {
                    data: 'created_date',
                    name: 'created_date',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'branch_name',
                    name: 'branch_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
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
                                    href="/reports/ticket_detail/${row.uuid}"><i class="ri-eye-line mr-0"></i></a>
                                
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        table.on('click', '#delete', function(e) {

            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.document_delete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    var document_id = $(this).data('document_id');
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '/lucky_draws/' + document_id + '/delete',
                        type: 'GET',

                        data: {
                            "_token": token,
                            "document_id": document_id,
                        },
                        beforeSend: function() {
                            jQuery("#load").fadeOut();
                            jQuery("#loading").show();
                        },
                        complete: function() {
                            jQuery("#loading").hide();
                        },
                        success: function(response) {
                            $('#lucky_draw_list').DataTable().draw(true);
                        }
                    });
                }
            });
        });
        $('#document_export').on('click', function(e) {
            var today = new Date().toISOString().slice(0, 10);
            var document_no = $('#document_no').val();
            var document_from_date = $('#document_from_date').val().length === 0 ? today : $('#document_from_date').val();
            var document_to_date = $('#document_to_date').val().length === 0 ? today : $('#document_to_date').val();
            var document_type = $('#document_type').val();
            var document_branch = $('#document_branch').val();
            var document_status = $('#document_status').val();
            var category = $('#category').val();
            var other = document_no + '-' + document_type + '-' + document_branch + '-' + document_status + '-' + category;
            var url = `/documents/document_export/${document_from_date}/${document_to_date}/${other}`;
            window.location = url;
        })
    });
</script>
@stop