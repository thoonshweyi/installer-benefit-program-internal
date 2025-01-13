@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('ticket.header_detail') }}</h4>
                        </div>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
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
                        <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{$ticket_header->uuid}}" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ticket Header No</label>
                                    <input disabled type="text" class="form-control" value="{{$ticket_header->ticket_header_no}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Valid Amount</label>
                                    <input disabled type="text" class="form-control" value="{{number_format(getTotalValidAmount($ticket_header->uuid))}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input disabled type="text" class="form-control" value="{{isset($ticket_header->customers)? $ticket_header->customers->firstname : ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Phone No</label>
                                    <input disabled type="text" class="form-control" value="{{isset($ticket_header->customers) ? $ticket_header->customers->phone_no : ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer No</label>
                                    <input disabled type="text" class="form-control" value="{{isset($ticket_header->customers) ? $ticket_header->customers->customer_no : ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Type</label>
                                    <input disabled type="text" class="form-control" value="{{isset($ticket_header->customers) ? $ticket_header->customers->customer_type : ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Division</label>
                                    <input disabled type="text" class="form-control" value="{{isset($ticket_header->customers) && ($ticket_header->customers->provinces!=null)  ? $ticket_header->customers->provinces->province_name : ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Township</label>
                                    <input disabled type="text" class="form-control" value="{{isset($ticket_header->customers)&& isset($ticket_header->customers->amphurs)  ? $ticket_header->customers->amphurs->amphur_name : ''}}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <a class="btn btn-light" href="{{ route('tickets.ticket_headers') }}">{{__('button.back')}}</a>
                                <a class="btn btn-primary" target="_blank" href="{{ url("create_ticket/$ticket_header->uuid#invoices")}}">{{__('button.resume')}}</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="header-title">
                    <h4 class="card-title">{{ __('ticket.invoices') }}</h4>
                </div>
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="invoice_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('ticket.invoice_no')}}</th>
                                <th>{{__('ticket.valid_amount')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">

                        </tbody>
                    </table>
                    {{-- <table class="table mb-0 tbl-server-info" id="">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th class="">{{__('ticket.total_amount')}}</th>
                                <th>{{ getTotalValidAmount($ticket_header->uuid) }}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                        </tbody>
                    </table> --}}
                </div>
            </div>
            <div class="col-lg-12">
                <div class="header-title">
                    <h4 class="card-title">{{ __('ticket.promotions') }}</h4>
                </div>
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="claim_history_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('ticket.sub_promotion')}}</th>
                                <th>{{__('ticket.promotion')}}</th>
                                <th>{{__('ticket.valid_qty')}}</th>
                                <th>{{__('ticket.choose_qty')}}</th>
                                <th>{{__('ticket.claimed_qty')}}</th>
                                <th>{{__('ticket.printed_at')}}</th>
                                <th>{{__('ticket.action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Page end  -->
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#invoice_list').DataTable({
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
                'url': "/invoice_list_by_ticket_header",
                'type': 'GET',
                'data': function(d) {
                    d.ticket_header_uuid = $('#ticket_header_uuid').val();
                }
            },
            columns: [
                {
                    data: 'invoice_no',
                    name: 'invoice_no',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}</a>`;
                    }
                },
                {
                    data: 'valid_amount',
                    name: 'valid_amount',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}</a>`;
                    }
                },
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        var table = $('#claim_history_list').DataTable({
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
                'url': "/claim_history_list",
                'type': 'GET',
                'data': function(d) {
                    d.ticket_header_uuid = $('#ticket_header_uuid').val();
                }
            },
            columns: [
                {
                    data: 'sub_promotion',
                    name: 'sub_promotion',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'promotion',
                    name: 'promotion',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'valid_qty',
                    name: 'valid_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'choosed_qty',
                    name: 'choosed_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'claimed_qty',
                    name: 'claimed_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'printed_at',
                    name: 'printed_at',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        if (row.print_status == 2) {
                            return `<div class="d-flex align-items-center list-action">
                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Reprint" data-original-title="Reprint"
                                        href="/reprint/${row.uuid}"><i class="ri-printer-line mr-0"></i></a>
                                </div>`
                        }
                        return '';

                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })
    });
</script>
@endsection
