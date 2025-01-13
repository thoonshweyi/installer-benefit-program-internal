@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('ticket.header_create') }}</h4>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('ticket.branch')}} </label>
                                        <select name="branch_id" id="branch_id" class="form-control" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('ticket.ticket_type')}} </label>
                                        <select name="ticket_type" id="ticket_type" class="form-control" required>
                                                <option value="1">Normal Ticket</option>
                                                <option value="2">Special Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('ticket.lucky_draw')}} </label>
                                        <select name="lucky_draw_id" id="lucky_draw_id" class="form-control" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('ticket.invoice_no')}} </label>
                                        <input name="invoice_no" id="invoice_no" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <button class="btn btn-primary col-md-2" id="add_invoice">{{ __('button.add') }}</button>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="header-title row ml-1">
                                            <h4 class="card-title">{{ __('ticket.total_amount') }} : </h4>
                                            <h4 class="card-title" id="total_amount">0</h4>
                                            <h4 class="card-title"> MMK</h4>
                                        </div>
                                        <div class="header-title row mr-1">
                                            <h4 class="card-title">{{ __('ticket.total_ticket') }} : </h4>
                                            <h4 class="card-title" id="total_qty">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive rounded mb-3">
                                    <table class="table" id="invoice_list_by_ticket_header">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th>{{ __('ticket.invoice_no') }}</th>
                                                <th>{{ __('ticket.amount') }}</th>
                                                <th>{{ __('ticket.qty') }}</th>
                                                <th>{{ __('ticket.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="header-title">
                                            <h4 class="card-title">{{ __('ticket.customer_info') }}</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{route('customers.store')}}" method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">

                                        <input type="hidden" name="customer_id[]" id="customer_id" value="1" />
                                        <input type="hidden" name="old_customer_id" id="old_customer_id" value="" />
                                        @csrf
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.customer_phone_no')}} (*)</label>
                                                        <input name="customer_phone_no" id="customer_phone_no" type="text" class="form-control" value="{{isset($customer) ? $customer->phone_no : ''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.phone_no2')}} </label>
                                                        <input name="phone_no2" id="phone_no2" type="text" class="form-control"  value={{isset($customer) ? $customer->phone_no_2 : ''}}>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.titlename')}} </label>
                                                        <select name="titlename" id="titlename" class="form-control" >

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.firstname')}} (*)</label>
                                                        <input name="firstname" id="firstname" type="text" class="form-control" value="{{isset($customer) ? $customer->firstname : ''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.lastname')}} </label>
                                                        <input name="lastname" id="lastname" type="text" class="form-control" value={{isset($customer) ? $customer->lastname : ''}}>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.customer_type')}} </label>
                                                        <input name="customer_type" id="customer_type" type="text"  class="form-control" value={{isset($customer->customer_type) ? $customer->customer_type : 'Other'}} disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.customer_no')}} </label>
                                                        <input name="customer_no" id="customer_no" type="text"  class="form-control" value={{isset($customer) ? $customer->customer_no : ''}}>
                                                    </div>
                                                </div>


                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.nrc_no')}} </label>
                                                        <select name="nrc_no" id="nrc_no" class="form-control" >

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.nrc_name')}} </label>
                                                        <select name="nrc_name" id="nrc_name" class="form-control" >

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.nrc_short')}} </label>
                                                        <select name="nrc_short" id="nrc_short" class="form-control">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.nrc_number')}} </label>
                                                        <input name="nrc_number" id="nrc_number" type="text" class="form-control" value={{isset($customer) ? $customer->nrc_number : ''}}>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.email')}} </label>
                                                        <input name="email" id="email" type="text" class="form-control"  value={{isset($customer) ? $customer->email : ''}}>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.passport')}} </label>
                                                        <input name="passport"  id="passport" type="text" class="form-control"  value={{isset($customer) ? $customer->passport : ''}}>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.customer_division')}} </label>
                                                        <select name="customer_division" id="customer_division" class="form-control" >

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.customer_township')}} </label>
                                                        <select name="customer_township" id="customer_township" class="form-control">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>{{__('ticket.customer_address')}} </label>
                                                        <input name="customer_address" id="customer_address" type="text" class="form-control" value={{isset($customer) ? $customer->address : ''}}>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <button class="btn btn-primary col-md-2 mr-2">{{ __('button.save') }}</button>
                                <button class="btn btn-success col-md-2 mr-2">{{ __('button.print') }}</button>

                                <a class="btn btn-light" href="{{ route('documents.index') }}">{{ __('button.back') }}</a>
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
    function validateForm() {
        if ($('#document_type').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_type') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#document_remark').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_remark') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
    }
    $(document).ready(function() {
            //Check Valid Invoice No
            var table = document.getElementById("invoice_list_by_ticket_header");

// Create an empty <tr> element and add it to the 1st position of the table:
var row = table.insertRow(1);

// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
// Add some text to the new cells:
cell1.innerHTML = 'aa';
cell2.innerHTML = 'bb';
cell3.innerHTML = 'cc';
cell4.innerHTML = `<div class="d-flex align-items-center list-action">
                <a class="badge bg-warning" data-toggle="tooltip" data-placement="top" title="Delete" id="delete"><i class="ri-delete-bin-line mr-0"></i></a>
            </div>`;

            //Add Invoice
            $(document).on("click", "#add_invoice", function() {

                var branch_id = $('#branch_id').val();
                var ticket_type = $('#ticket_type').val();
                var lucky_draw_id = $('#lucky_draw_id').val();
                var invoice_no = $('#invoice_no').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                var token = $("meta[name='csrf-token']").attr("content");
                if (!invoice_no) {
                    $.ajax({
                        url: '../../tickets/add_invoice',
                        type: 'post',
                        data: {
                            "_token": token,
                            "branch_id": branch_id,
                            "ticket_type" : ticket_type,
                            "lucky_draw_id" : lucky_draw_id,
                            "invoice_no": invoice_no,
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
                                var total_amount = parseInt(document.getElementById("total_amount").innerHTML);
                                var new_total_amount = total_amount +  parseInt(response.data.valid_total_price);
                                $('#total_amount').html(new_total_amount);

                                var total_qty = parseInt(document.getElementById("total_qty").innerHTML);
                                var new_total_qty = total_qty +  parseInt(response.data.total_valid_ticket_qty);
                                $('#total_qty').html(new_total_qty);
                                var table = document.getElementById("invoice_list_by_ticket_header");

                                // Create an empty <tr> element and add it to the 1st position of the table:
                                var row = table.insertRow(1);

                                // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
                                var cell1 = row.insertCell(0);
                                var cell2 = row.insertCell(1);
                                var cell3 = row.insertCell(2);
                                var cell4 = row.insertCell(3);
                                // Add some text to the new cells:
                                cell1.innerHTML = response.data.invoice.sale_cash_document_no;
                                cell2.innerHTML = response.data.valid_total_price;
                                cell3.innerHTML = response.data.total_valid_ticket_qty;
                                cell4.innerHTML = `<div class="d-flex align-items-center list-action">
                                                <a class="badge bg-warning" data-toggle="tooltip" data-placement="top" title="Delete" id="delete"><i class="ri-delete-bin-line mr-0"></i></a>
                                            </div>`;

                                $('#phone_no').val('');
                                $('#phone_no').val(response.data.customer.phone_no);
                                $('#phone_no2').val('');
                                $('#phone_no2').val(response.data.customer.phone_no_2);
                                $('#titlename').val('');
                                $('#titlename').val(response.data.customer.titlename);
                                $('#firstname').val('');
                                $('#firstname').val(response.data.customer.firstname);
                                $('#lastname').val('');
                                $('#lastname').val(response.data.customer.lastname);
                                $('#customer_type').val('');
                                $('#customer_type').val(response.data.customer_type);
                                $('#customer_no').val('');
                                $('#customer_no').val(response.data.customer.customer_barcode);
                                $('#nrc_no').val('');
                                $('#nrc_no').val(response.data.customer.nrc_no);
                                $('#nrc_name').val('');
                                $('#nrc_name').val(response.data.customer.nrc_name);
                                $('#nrc_short').val('');
                                $('#nrc_short').val(response.data.customer.nrc_short);
                                $('#nrc_number').val('');
                                $('#nrc_number').val(response.data.customer.nrc_number);
                                $('#email').val('');
                                $('#email').val(response.data.customer.email);
                                $('#passport').val('');
                                $('#passport').val(response.data.customer.passport);
                                $('#customer_division').val('');
                                $('#customer_division').val(response.data.customer.province_id);
                                $('#customer_township').val('');
                                $('#customer_township').val(response.data.customer.amphur_id);
                                $('#customer_address').val('');
                                $('#customer_address').val(response.data.customer.full_address);



                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.${response.error}') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                        },
                        error: function() {
                            $('#product_code_no').addClass('is-invalid');
                            $('#product_code_noFeedback').removeClass("d-none");
                            $('#product_name').val("");
                            $('#product_unit').val("");
                            $('#stock_quantity').val("");
                            $('#operation_remark').val("");
                        }
                    });

                }
            })
            $(document).on("click", "#delete", function(e) {

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
                        index = this.closest('tr').rowIndex;
                        table = document.getElementById('invoice_list_by_ticket_header');
                        table.deleteRow(index);
                        var tr = this.closest('tr');
                        // tr = tr.replace('<tr>','');
                        // tr = tr.replace('</tr>','');
                        var c = $('#customer_id').val();
                        s=2;
                        c= $('#customer_id').value = c +s;
                        c = $('#customer_id').val();

                        var total_amount = parseInt(document.getElementById("total_amount").innerHTML);
                        var new_total_amount = total_amount +  parseInt(response.data.valid_total_price);
                        $('#total_amount').html(new_total_amount);

                        var total_qty = parseInt(document.getElementById("total_qty").innerHTML);
                        var new_total_qty = total_qty +  parseInt(response.data.total_valid_ticket_qty);
                        $('#total_qty').html(new_total_qty);

                    }
                });
            });

        })
</script>
@endsection
