@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('ticket.do_not_use_ticket_listing')}}</h4>
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
                <div class="form-row col-md-4">
                    <label>{{__('ticket.lucky_draw')}} </label>
                    <select name="lucky_draw_uuid" id="lucky_draw_uuid" class="form-control" required>
                        @foreach($promotions as $luckydraw)
                            <option value="{{ $luckydraw->promotions->uuid }}" {{ ($luckydraw->promotions->uuid == old("lucky_draw_uuid")) ? 'selected' : '' }}>
                                {{ $luckydraw->promotions->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <button id="do_not_use_ticket_search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
      
                <button id="do_not_use_ticket_export" class="btn btn-success">{{__('button.do_not_use_ticket_excel_export')}}</button>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="do_not_use_ticket_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('ticket.promotion_name')}}</th>
                                <th>{{__('ticket.ticket_header_no')}}</th>
                                <th>{{__('ticket.ticket_no')}}</th>
                                <th>{{__('ticket.customer_name')}}</th>
                                <th>{{__('ticket.customer_phone_no')}}</th>
                                <th>{{__('ticket.cancel_at')}}</th>
                                <th>{{__('ticket.cancel_user')}}</th>
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
        var table = $('#do_not_use_ticket_list').DataTable({
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
                'url': "/search_do_not_use_ticket_list",
                'type': 'GET',
                'data': function(d) {
                    d.lucky_draw_uuid = $('#lucky_draw_uuid').val();
                }
            },
            columns: [
                {
                    data: 'promotion_name',
                    name: 'promotion_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'ticket_header_no',
                    name: 'ticket_header_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'ticket_no',
                    name: 'ticket_no',
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
                    data: 'customer_phone_no',
                    name: 'customer_phone_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'cancel_at',
                    name: 'cancel_at',
                    orderable: true,
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'cancel_user',
                    name: 'cancel_user',
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
                        return `<div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        href="/tickets/edit_ticket_header/${row.uuid}"><i class="ri-eye-line mr-0"></i></a>
                                </div>`
                        
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        $('#do_not_use_ticket_search').on('click', function(e) {
            $('#do_not_use_ticket_list').DataTable().draw(true);
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
                            Swal.fire({
                                icon: 'success',
                                title: "{{ __('message.success') }}",
                                text: `{{ __('message.successfully_canceled') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            }).then(function(){
                                $('#ticket_header_list').DataTable().draw(true);
                            })
                        }
                    });
                }
            });
        });
        $('#do_not_use_ticket_export').on('click', function(e) {
            var lucky_draw_uuid = $('#lucky_draw_uuid').val();
          
            var url = `/export_not_use_tickets/${lucky_draw_uuid}`;
            window.location = url;
        })
    });
</script>
@stop