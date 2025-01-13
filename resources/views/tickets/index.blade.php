@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('ticket.header_list')}}</h4>
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
                    <label>{{__('ticket.ticket_header_no')}} </label>
                    <input type="text" class="form-control" id="ticket_header_no" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('ticket.invoice_no')}} </label>
                    <input type="text" class="form-control" id="invoice_no" value="">
                </div>
                <button id="ticket_header_search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
                @can('export-document-admin')
                <button id="document_export" class="btn btn-success">{{__('button.product_excel_export')}}</button>
                @endcan
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="ticket_header_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('ticket.ticket_header_no')}}</th>
                                <th>{{__('ticket.invoice_no')}}</th>
                                <th>{{__('ticket.customer_name')}}</th>
                                <th>{{__('ticket.customer_phone_no')}}</th>
                                <th>{{__('ticket.created_user')}}</th>
                                <th>{{__('ticket.status')}}</th>
                                <th>{{__('ticket.action')}}</th>
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
        var table = $('#ticket_header_list').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "autoWidth": true,
            "responsive": true,
            "pageLength": 10,
            "scrollY": "450px",
            "scrollCollapse": true,
            "ordering": false,
            'ajax': {
                'url': "/tickets_search_result",
                'type': 'GET',
                'data': function(d) {
                    d.ticket_header_no = $('#ticket_header_no').val();
                    d.invoice_no = $('#invoice_no').val();
                }
            },
            columns: [
                {
                    data: 'ticket_header_no',
                    name: 'ticket_header_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return ` <a class="normal_status" title="Edit" href="/ticket_headers/${row.uuid}">${data}</a>`;
                    }
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return ` <a class="normal_status" title="Edit" href="/ticket_headers/${row.uuid}">${data}</a>`;
                    }
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return ` <a class="normal_status" title="Edit" href="/ticket_headers/${row.uuid}">${data}</a>`;
                    }
                },
                {
                    data: 'customer_phone_no',
                    name: 'customer_phone_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return ` <a class="normal_status" title="Edit" href="/ticket_headers/${row.uuid}">${data}</a>`;
                    }
                },
                {
                    data: 'created_user',
                    name: 'created_user',
                    orderable: true,
                    render: function(data, type, row) {
                        return ` <a class="normal_status" title="Edit" href="/ticket_headers/${row.uuid}">${data}</a>`;
                    }
                },
                {
                    data: 'cancel_status',
                    name: 'cancel_status',
                    orderable: true,
                    render: function(data, type, row) {
                        return ` <a class="normal_status" title="Edit" href="/ticket_headers/${row.uuid}">${data}</a>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        if (row.status == 2) {
                            return `<div class="d-flex align-items-center list-action">
                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="View" data-original-title="View"
                                        href="/ticket_headers/${row.uuid}"><i class="ri-edit-line mr-0"></i></a>
                                </div>`
                        }

                        return `<div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="View" data-original-title="View"
                                        href="/ticket_headers/${row.uuid}"><i class="ri-edit-line mr-0"></i></a>

                                        <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="Cancel" data-original-title="Delete"
                                            id="delete" href="#"" data-ticket_header_uuid="${row.uuid}"><i class="ri-delete-bin-line mr-0"></i></a>

                                </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        $('#ticket_header_search').on('click', function(e) {
            $('#ticket_header_list').DataTable().draw(true);
        })
        table.on('click', '#delete', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.cancel_confirm') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    var ticket_header_uuid = $(this).data('ticket_header_uuid');
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '/make_ticket_not_use/' + ticket_header_uuid,
                        type: 'GET',

                        data: {
                            "_token": token,
                            "ticket_header_uuid": ticket_header_uuid,
                        },
                        beforeSend: function() {
                            jQuery("#load").fadeOut();
                            jQuery("#loading").show();
                        },
                        complete: function() {
                            jQuery("#loading").hide();
                        },
                        success: function(response) {
                            if (response.data != null) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "{{ __('message.success') }}",
                                    text: `{{ __('message.successfully_canceled') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                }).then(function(){
                                    $('#ticket_header_list').DataTable().draw(true);
                                })
                            }
                            else{
                                if (response.error =='can_not_cancel_when_promotion_is_claimed') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_cancel_when_promotion_is_claimed') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            }
                            console.log(response);
                            // location.reload();
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
