@extends('create_tickets.layout')

@section('content')
    <div class="main-area row col-md-9">

        <div class="column col-md-12">
        <button type="button" id="go_to_customer_info">{{__('new_promotion.next')}}</button>
            <h4 class="card-second-title">{{__('new_promotion.collect_invoices')}} :</h4>
            <h4 class="card-label">{{__('new_promotion.invoice')}} *</h4>
            <button type="submit" class="add-button" id="add_invoice">{{__('new_promotion.add')}}</button>
            <input class="card-input search-input" name="invoice_no" id="invoice_no" />

            <h4 class="total-amount">{{ number_format($ticket_header->total_valid_amount,0) }} MMK</h4>
            <h4 class="total-amount-lable">{{__('new_promotion.total_amount')}}</h4>

            <div class="table-responsive rounded mb-3">
                <table class="card-table table mb-0 tbl-server-info" id="collect_invoice_list">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>{{__('new_promotion.invoice_no')}} </th>
                            <th class="text-center"> {{__('new_promotion.amount')}}</th>
                            <th> {{__('new_promotion.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
         $(document).on("click", "#go_to_customer_info", function() {
            let url = "{{ route('tickets.customer_info', $ticket_header->uuid ?? '') }}";
            document.location.href = url;
        })
        $(document).on("click", "#add_invoice", function() {
            var invoice_no = $('#invoice_no').val();
            var ticket_header_uuid = $('#ticket_header_uuid').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            var token = $("meta[name='csrf-token']").attr("content");
            if (invoice_no) {
                $.ajax({
                    url: '../../../tickets/add_invoice',
                    type: 'post',
                    data: {
                        "_token": token,
                        "invoice_no": invoice_no,
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
                            if (response.data.old_user_name) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: `{{ __('message.customer_is_not_same') }}`,
                                    html: "choose_customer" + "<br>" +
                                        "<table class='table'><tr><th width='50%'>" +
                                        response.data
                                        .old_user_name + "</th><th width='50%'>" +
                                        response.data
                                        .new_user_name +
                                        "</th></tr><tr><td width='50%'>" + response
                                        .data.old_phone_no + "</td><td width='50%'>" +
                                        response.data
                                        .new_phone_no + "</td></tr></table>",
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: response.data.old_user_name,
                                    denyButtonText: response.data.new_user_name,
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        Swal.fire(
                                            `{{ __('message.choosed_old_customer') }}`,
                                            '',
                                            'info').then(function() {
                                            var url =
                                                `/tickets/collect_invoice/${response.data.ticket_header_uuid}/edit`;
                                            window.location = url;
                                        })
                                    } else if (result.isDenied) {
                                        $.ajax({
                                            url: '../../../tickets/update_ticket_header_customer',
                                            type: 'post',
                                            data: {
                                                "_token": token,
                                                "ticket_header_uuid": ticket_header_uuid,
                                                "customer_id": response.data
                                                    .new_customer_id,
                                                "invoice_id": response.data
                                                    .invoice_id,
                                            },
                                        })
                                        Swal.fire(
                                            `{{ __('message.choosed_new_customer') }}`,
                                            '',
                                            'info').then(function() {
                                            var url =
                                                `/tickets/collect_invoice/${response.data.ticket_header_uuid}/edit`;
                                            window.location = url;
                                        })
                                    }
                                })
                            } else {
                                message = response.data.message;
                                Swal.fire({
                                    icon: 'success',
                                    title: "{{ __('message.success') }}",
                                    text: `{{ __('message.successfully_created') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                }).then(function() {
                                    var url =
                                        `/tickets/collect_invoice/${response.data.ticket_header_uuid}/edit`;
                                    window.location = url;
                                });
                            }
                        } else {
                            if (response.error ==
                                'can_not_add_invoice_when_ticket_is_generated') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_add_invoice_when_ticket_is_generated') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'invoice_is_not_found') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_not_found') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'invoice_is_used') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_used') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'invoice_is_expired') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_expired') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'customer_is_not_same') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.customer_is_not_same') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'ticket_header_uuid_error') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.ticket_header_uuid_error') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error ==
                                'can_not_remove_invoice_when_ticket_is_generated') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_remove_invoice_when_ticket_is_generated') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'permission_denied') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.permission_denied') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'promotion_expired') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_expired') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'promotion_is_not_start') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_is_not_start') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if (response.error == 'promotion_image_is_not_uploaded') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_image_is_not_uploaded') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: `{{ __('message.validation_error') }}`,
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                    }
                });

            }
        })
        var collect_invoice_table = $('#collect_invoice_list').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "autoWidth": true,
            "responsive": true,
            "bInfo": false,
            "bPaginate": false,
            "order": [
                [1, 'des']
            ],
            'ajax': {
                'url': "/result_for_collect_invoice",
                'type': 'GET',
                'data': function(d) {
                    d.ticket_header_uuid = $('#ticket_header_uuid').val();
                }
            },
            columns: [{
                    data: 'invoice_no',
                    name: 'invoice_no',
                    orderable: true
                },
                {
                    data: 'valid_amount',
                    name: 'valid_amount',
                    orderable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        return `<div class="d-flex align-items-center list-action">
                                    <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="invoice_delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                    </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0,
            }],
        })
        collect_invoice_table.on('click', '#invoice_delete', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.invoice_delete') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('message.cancel') }}",
                confirmButtonText: "{{ __('message.ok') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $(this).data('uuid');
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '../../../delete_collect_invoice/' + id,
                        type: 'POST',
                        data: {
                            "_token": token,
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            jQuery("#load").fadeOut();
                            jQuery("#loading").show();
                        },
                        complete: function() {
                            jQuery("#loading").hide();
                        },
                        success: function(response) {
                            $('#uuid').val('');
                            $('#ticket_header_uuid').val('');
                            $('#invoice_id').val('');
                            $('#invoice_no').val('');
                            $('#valid_amount').val('');
                            var url =
                                `/tickets/collect_invoice/${response.data.ticket_header_uuid}/edit`;
                            window.location = url;
                        },
                        error: function(response) {
                            $('#uuid').val('');
                            $('#ticket_header_uuid').val('');
                            $('#invoice_id').val('');
                            $('#invoice_no').val('');
                            $('#valid_amount').val('');
                        }
                    });
                } else {
                    return false;
                }
            });
        })
    </script>
@endsection
