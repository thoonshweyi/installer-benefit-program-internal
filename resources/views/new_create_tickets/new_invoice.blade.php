
  <!-- Invoices Subpage -->
  <section data-id="new_invoice" class="animated-section ">
    <div class="section-content">
      <div class="page-title">
        <h2> {{__('new_promotion.start')}}</h2>
    </div>
    <p> {{__('new_promotion.new_invoice_description')}}</p>
      <div class="row pl-3">
          <div class="form-group form-group-with-icon" style="
          margin-right: 20px;
          width: 500px;">
            <input id="invoice_no" type="text" name="invoice_no" class="form-control" placeholder="" required="required" data-error="Valid Invoice No is required.">
            <label>Invoice No</label>
            <div class="form-control-border"></div>
            <div class="help-block with-errors"></div>
          </div>
          <div class="form-group form-group-with-icon">
            <input type="button" class="button btn-send" id="add_invoice" value="{{__('new_promotion.add')}}">
          </div>
          <div class="clear-button row"  style="
                    right: 260px !important;
                    top: 157px !important;
                    background-color: transparent!important;
                    width:0px;!important ">
            <div id="clear_button"  style="margin: auto !important;">
              <h3 style="margin: 1px;color: #666;">X</h3>
            </div>
          </div>
      </div>

      <!-- End of Services -->

    </div>
</section>
<script>
    $(document).ready(function() {
        $(document).on("click", "#add_invoice", function () {
            var invoice_no = $('#invoice_no').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            var token = $("meta[name='csrf-token']").attr("content");
            if (invoice_no) {
                $.ajax({
                    url: '../../../create_new_ticket',
                    type: 'post',
                    data: {
                        "_token": token,
                        "invoice_no": invoice_no,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
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
                            timer: 1000,
                        }).then(function () {
                            $('#invoice_no').val('');
                            // hiden ticket header uuid
                            $('#next_button').show();
                            $('#previous_button').show();
                            $('#ticket_header_uuid').val(response.data.ticket_header_uuid);
                            $('#branch_id').val(response.data.branch_id);

                            var url =
                                `/create_ticket/${response.data.ticket_header_uuid}#invoices`;
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
                        }
                    },
                    error: function () {
                        if (response == 'unauthorized') {
                            window.location = `/`;
                        }else{
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.validation_error') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                        }
                    }
                });

            }
        })
    });
</script>
