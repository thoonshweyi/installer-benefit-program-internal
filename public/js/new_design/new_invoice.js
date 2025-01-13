$(document).on("click", "#add_invoice", function () {
    var invoice_no = $('#invoice_no').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    var token = $("meta[name='csrf-token']").attr("content");
    if (invoice_noq) {
        $.ajax({
            url: '../../../create_new_ticket',
            type: 'post',
            data: {
                "_token": token,
                "invoice_no": invoice_no,
            },
            beforeSend: function () {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function () {
                jQuery("#loading").hide();
            },
            success: function (response) {
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
                                    'info').then(function () {
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
                                    'info').then(function () {
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
                        }).then(function () {
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
                    if (response.error == 'invoice_is_not_format') {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: `{{ __('message.invoice_is_not_format') }}`,
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                    }
                }
            },
            error: function () {
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