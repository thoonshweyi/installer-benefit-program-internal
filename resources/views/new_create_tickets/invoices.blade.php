
<!-- Invoices Subpage -->
<section data-id="invoices" class="animated-section ">
  <div class="section-content">
    <div class="page-title">
      <h2>  {{__('new_promotion.invoices')}}</h2>

    </div>
    <p> {{__('new_promotion.invoices_description')}}</p>
    <div class="row">
        <div class="col-12 d-flex ml-1 align-items-center">
            <div class="form-group" >
                <label for="invoice_no_2">{{ __('new_promotion.invoices') }}</label>
                <div class="d-flex" >
                    <input id="invoice_no_2" type="text" class="form-control" aria-describedby="invoiceHelp" required="required" data-error="Valid Invoice No is required." style="width:450px">
                    {{-- <div class="form-control-border"></div> --}}
                    <div class="help-block with-errors"></div>
                    <button class="btn btn-primary ml-2 " style="transform: translateY(-2px);" id="add_invoice_2">{{ __('new_promotion.add_more') }}</button>
                    <button id="clear_button" style="transform: translateY(3px);" class="btn bg-transparent shadow-none ml-2 border-0 d-flex align-items-center justify-content-center" id="add_invoice_2"><i class="fas fa-times h3"></i></button>
                    {{-- <div   style="margin: auto !important;">
                        <h3 style="margin: 1px;color: #666;">X</h3>
                    </div> --}}
                </div>
                <small id="invoiceHelp" class="form-text text-muted" style="margin-top: -15px!important;">{{ __('new_promotion.invoice_help') }}</small>

              </div>
            {{-- <div class="form-group form-group-with-icon " style="margin-right: 20px; width: 450px;">
                <input id="invoice_no_2" type="text" name="invoice_no_2" class="form-control" placeholder="" required="required" data-error="Valid Invoice No is required.">
                <label>{{__('new_promotion.invoice_no')}}</label>
                <div class="form-control-border"></div>
                <div class="help-block with-errors"></div>
            </div>
            <div class="d-flex align-items-center">
                <div class="form-group form-group-with-icon">
                    <input type="button" class="button btn-send" id="add_invoice_2" value="{{__('new_promotion.add_more')}}">
                </div>
                <div id="clear_button"  style="margin: auto !important;">
                    <h3 style="margin: 1px;color: #666; margin-bottom:25px;margin-left:10px;">X</h3>
                </div>
            </div> --}}
        </div>

    <!-- Services -->


    <div class="row ml-2">
      <div class="col-12">

          <table class="card-table table mb-0 tbl-server-info" id="collect_invoice_list">
            <thead class="bg-white ">
                <tr class="ligth ligth-data" style="
                width:80%">
                <th> {{__('new_promotion.invoice_status')}} </th>
                    <th class="text-nowrap"> {{__('new_promotion.invoice_no')}} </th>
                    <th> {{__('new_promotion.invoice_amount')}} </th>
                    <th>  {{__('new_promotion.action')}}</th>
                </tr>
            </thead>
            <tbody class="ligth-body">
            </tbody>
        </table>

      </div>
    </div>
    <!-- End of Services -->

  </div>
</section>
<script>

    $(document).on("click", "#add_invoice_2", function () {
        jQuery("#loading").show();
        var delayInMilliseconds = 1000; //1 second
        setTimeout(function() {
        //your code to be executed after 1 second
            jQuery("#loading").hide();
        }, delayInMilliseconds);
        var invoice_no = $('#invoice_no_2').val();
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //     }
        // });
        // var token = $("meta[name='csrf-token']").attr("content");
        if (invoice_no) {
            $.ajax({
                url: '../../../check_customer',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    "invoice_no": invoice_no,
                },
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {

                },
                success: function (response) {
                    if (response.data != null) {
                        $('#invoice_no_2').val('');
                        // Check Old and New
                        var gbh_customer_id = $('#gbh_customer_id').val();
                        var old_customer_name = $('#firstname').val();
                        var old_phone_no = $('#phone_no').val();
                        if(response.data.gbh_customer_id != gbh_customer_id){
                            Swal.fire({
                                icon: 'warning',
                                title: `{{ __('message.customer_is_not_same') }}`,
                                html: "{{ __('message.choose_customer') }}" + "<br>" +
                                    "<table class='table'><tr><th width='50%'>" +
                                    old_customer_name + "</th><th width='50%'>" +
                                    response.data
                                    .new_user_name +
                                    "</th></tr><tr><td width='50%'>" + old_phone_no + "</td><td width='50%'>" +
                                    response.data
                                    .new_phone_no + "</td></tr></table>",
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: old_customer_name,
                                denyButtonText: response.data.new_user_name,
                            }).then((result) => {
                                var ticket_header_uuid = $('#ticket_header_uuid').val();
                                if (result.isConfirmed) {
                                    Swal.fire(
                                        `{{ __('message.choosed_old_customer') }}`,
                                        '',
                                        'info'
                                    ).then(function() {
                                        console.log('aa',gbh_customer_id)
                                        add_more_invoice(invoice_no,ticket_header_uuid,gbh_customer_id);
                                    })
                                } else if (result.isDenied) {
                                    Swal.fire(
                                        `{{ __('message.choosed_new_customer') }}`,
                                        '',
                                        'info'
                                    ).then(function() {
                                        add_more_invoice(invoice_no,ticket_header_uuid,response.data.gbh_customer_id);
                                    })
                                }
                            })
                        }else{
                            var ticket_header_uuid = $('#ticket_header_uuid').val();
                            jQuery("#loading").show();
                            add_more_invoice(invoice_no,ticket_header_uuid,gbh_customer_id);
                        }
                    } else {
                        if (response.error == 'invoice_is_not_format') {
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.invoice_is_not_format') }}`,
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
            'url': "/new_result_for_collect_invoice",
            'type': 'GET',
            'data': function(d) {
                d.ticket_header_uuid = $('#ticket_header_uuid').val();
            }
        },
        columns: [
            {
                data: 'status',
                name: 'status',
                orderable: true
            },
            {
                data: 'invoice_no',
                name: 'invoice_no',
                orderable: true,
                render: function(data, type, row)
                {
                    return `<div class="text-right text-nowrap">
                            ${data}
                            </div>`
                }
            },
            {
                data: 'valid_amount',
                name: 'valid_amount',
                orderable: true,
                render: function(data, type, row)
                {

                    return `<div class="text-right text-wrap">
                            ${data}
                            </div>`
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    console.log(row)
                    return `<div class="d-flex align-items-center list-action">
                                <a class="badge mr-2" data-uuid="${row.uuid}" title="Delete" id="invoice_delete"  href="#"><h3 style="margin: 1px;color: red;">X</h3></a>
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
                    url: '/delete_invoice/' + id,
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
                    if (response.error == 'can_not_add_invoice_when_ticket_is_generated') {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: `{{ __('message.can_not_add_invoice_when_ticket_is_generated') }}`,
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                    }
                    else if (response.error == 'can_not_add_invoice_when_promotion_is_choosed') {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: `{{ __('message.can_not_add_invoice_when_promotion_is_choosed') }}`,
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                    }else{
                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('message.success') }}",
                            text: `{{ __('message.successfully_removed') }}`,
                            // confirmButtonText: "{{ __('message.ok') }}",
                            timer: 1000
                        }).then(function () {
                            $('#uuid').val('');
                            $('#invoice_id').val('');
                            $('#invoice_no').val('');
                            $('#valid_amount').val('');
                            location.reload();
                            collect_invoice_table.draw(true);
                        })
                    }


                    },
                    error: function(response) {
                        $('#uuid').val('');
                        $('#invoice_id').val('');
                        $('#invoice_no').val('');
                        $('#valid_amount').val('');
                    }
                });
            } else {
                return false;
            }
        });
    });

    function add_more_invoice(invoice_no,ticket_header_uuid,gbh_customer_id){
        console.log(invoice_no,ticket_header_uuid,gbh_customer_id)
        jQuery("#loading").show();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: '../../../add_more_invoice',
            type: 'post',
            data: {
                "_token": token,
                "invoice_no": invoice_no,
                "ticket_header_uuid": ticket_header_uuid,
                "gbh_customer_id": gbh_customer_id,
            },
            beforeSend: function() {
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function (response) {
                if (response.data != null) {
                Swal.fire({
                    icon: 'success',
                    title: "{{ __('message.success') }}",
                    text: `{{ __('message.successfully_created') }}`,
                    // confirmButtonText: "{{ __('message.ok') }}",
                    timer:1000
                }).then(function () {
                    $('#invoice_no').val('');
                    // hiden ticket header uuid
                    $('#next_button').show();
                    $('#previous_button').show();
                    $('#ticket_header_uuid').val(response.data.ticket_header_uuid);
                    $('#branch_id').val(response.data.branch_id);
                    $('#collect_invoice_list').DataTable().draw(true);
                    var url =
                        `/create_ticket/${response.data.ticket_header_uuid}#invoices`;
                        location.reload();
                    window.location = url;
                });
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
                    if (response.error == 'cannot_more_than_10_invoices') {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: `{{ __('message.cannot_more_than_10_invoices') }}`,
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
</script>

