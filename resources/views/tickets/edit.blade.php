@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('ticket.create') }} : {{$ticket_header->ticket_header_no}}</h4>
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
                                <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{$ticket_header->uuid}}" />
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.branch')}} </label>
                                            <select name="branch_id" id="branch_id" class="form-control" required disabled>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->branches->branch_id }}" {{ $branch->branches->branch_id == $ticket_header->branch_id ? 'selected' : '' }}>
                                                        {{ $branch->branches->branch_name_eng}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.ticket_type')}} </label>
                                            <select name="ticket_type" id="ticket_type" class="form-control" required>
                                                    <option value="1"  {{ $ticket_header->ticket_type == 1 ? 'selected' : '' }}>Normal Ticket</option>
                                                    <option value="2"  {{ $ticket_header->ticket_type == 2 ? 'selected' : '' }}>Special Ticket</option>
                                                    {{-- <option value="3"  {{ $ticket_header->ticket_type == 3 ? 'selected' : '' }}>Deposit Ticket</option> --}}
                                                    <option value="4"  {{ $ticket_header->ticket_type == 4 ? 'selected' : '' }}>Return Ticket</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.lucky_draw')}} </label> <i class="fa fa-info-circle fa-lg exchange_deducted" id="view_promotion_info"></i>
                                            <select name="lucky_draw_uuid" id="lucky_draw_uuid" class="form-control" required disabled>
                                                @foreach($luckydraws as $luckydraw)
                                                    <option value="{{ $luckydraw->uuid }}" {{ $luckydraw->uuid == $ticket_header->promotion_uuid ? 'selected' : '' }}>
                                                        {{ $luckydraw->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('ticket.invoice_no')}} </label>
                                            <input name="invoice_no" id="invoice_no" type="text" class="form-control" placeholder="Type CA,SA" >
                                        </div>
                                    </div>
                                    <button class="btn btn-primary col-md-2" id="add_invoice">{{ __('button.add') }}</button>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between bg-info">
                            <div class="header-title">
                            <input id="total_valid_amount" type="hidden" value={{$ticket_header->total_valid_amount }}>
                                <h4 class="card-title text-dark">{{ __('ticket.total_amount') }}  : {{ number_format($ticket_header->total_valid_amount,0) }} MMK</h4>
                            </div>
                            <div class="header-title">
                            @if($ticket_header_step_sales->isNotEmpty())
                                @foreach($ticket_header_step_sales as $ticket_header_step_sale)
                                <h4 class="card-title text-dark">Step Sale
                                @if($ticket_header_step_sale->step_sale_type == 1)
                                20 lk
                                @endif
                                @if($ticket_header_step_sale->step_sale_type == 2)
                                30 lk
                                @endif
                                @if($ticket_header_step_sale->step_sale_type == 3)
                                40 lk
                                @endif
                                @if($ticket_header_step_sale->step_sale_type == 4)
                                50 lk
                                @endif
                                @if($ticket_header_step_sale->step_sale_type == 5)
                                100 lk
                                @endif
                                :  {{$ticket_header_step_sale->qty}} </h4>
                                @endforeach
                            @else
                                <input type="hidden" id="t_h_step_sale_status" value="0" />
                            @endif
                            <input type="hidden" id="total_valid_ticket_qty" value="{{$ticket_header->total_valid_ticket_qty}}" />
                                <h4 class="card-title text-dark">{{ __('ticket.total_ticket') }} :  {{ $ticket_header->total_valid_ticket_qty}} </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="table mb-0 tbl-server-info" id="invoice_list_by_ticket_header">
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
                            <form action="{{route('customers.store')}}" method="POST" enctype="multipart/form-data" id="customer_save_form">
                            <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{$ticket_header->uuid}}" />
                            <input type="hidden" name="ticket_header_printed_at" id="ticket_header_printed_at" value="{{$ticket_header->printed_at}}" />
                            <input type="hidden" name="customer_id" id="customer_id" value="" />
                            <input type="hidden" name="old_customer_id" id="old_customer_id" value="{{isset($customer) ? $customer->customer_id :''}}" />
                            <input type="hidden" name="new_customer_id" id="new_customer_id" value="" />
                            @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_phone_no')}}  <span class="cancel_status">*</sapn> </label>
                                            <input name="customer_phone_no" id="customer_phone_no" type="text" class="form-control" value="{{isset($customer) ? $customer->phone_no : ''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.phone_no2')}} </label>
                                            <input name="phone_no2" id="phone_no2" type="text" class="form-control"  value="{{isset($customer) ? $customer->phone_no_2 : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.titlename')}} </label>
                                            <select name="titlename" id="titlename" class="form-control" >
                                                @foreach($titles as $title)
                                                    <option value="{{ $title}}"
                                                        @if(isset($customer))
                                                            {{$title == $customer->titlename ?
                                                            'selected' : '' }}
                                                        @endif>
                                                        {{ $title}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('ticket.firstname')}}  <span class="cancel_status">*</sapn> </label>
                                            <input name="firstname" id="firstname" type="text" class="form-control" value="{{isset($customer) ? $customer->firstname : ''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.lastname')}} </label>
                                            <input name="lastname" id="lastname" type="text" class="form-control" value="{{isset($customer) ? $customer->lastname : ''}}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.nrc_no')}} </label>
                                            <select name="nrc_no" id="nrc_no" class="form-control" >
                                            <option value=""> Select NRC No</option>
                                                @foreach($nrc_nos as $nrc_no)
                                                    <option value="{{ $nrc_no->id}}"
                                                        @if(isset($customer))
                                                        {{$nrc_no->id == $customer->nrc_no ?
                                                            'selected' : ''}}
                                                        @endif>
                                                        {{ $nrc_no->nrc_number_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.nrc_name')}} </label>
                                            <select name="nrc_name" id="nrc_name" class="form-control">
                                            <option value=""> Select NRC Name</option>
                                                @foreach($nrc_names as $nrc_name)
                                                    <option value="{{ $nrc_name->id}}"
                                                        @if(isset($customer))
                                                        {{$nrc_name->id == $customer->nrc_name ?
                                                            'selected' : '' }}
                                                        @endif>
                                                        {{ $nrc_name->district}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.nrc_short')}} </label>
                                            <select name="nrc_short" id="nrc_short" class="form-control">
                                            <option value=""> Select NRC Short</option>
                                                @foreach($nrc_naings as $nrc_naing)
                                                    <option value="{{ $nrc_naing->id}}"
                                                        @if(isset($customer))
                                                        {{$nrc_naing->id == $customer->nrc_short ?
                                                            'selected' : '' }}
                                                        @endif>
                                                        {{ $nrc_naing->shortname}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.nrc_number')}} </label>
                                            <input name="nrc_number" id="nrc_number" type="text" class="form-control" value="{{isset($customer) ? $customer->nrc_number : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_type')}} </label>
                                            <input name="customer_type" id="customer_type" type="hidden"  class="form-control" value="{{isset($customer->customer_type) ? $customer->customer_type : 'Other'}}">
                                            <input class="form-control" value="{{isset($customer->customer_type) ? $customer->customer_type : 'Other'}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.passport')}} </label>
                                            <input name="passport"  id="passport" type="text" class="form-control"  value="{{isset($customer) ? $customer->passport : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_no')}} </label>
                                            <input name="customer_no" id="customer_no" type="text"  class="form-control" value="{{isset($customer) ? $customer->customer_no : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('ticket.email')}} </label>
                                            <input name="email" id="email" type="text" class="form-control"  value="{{isset($customer) ? $customer->email : ''}}">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_division')}} <span class="cancel_status">*</sapn> </label>
                                            <select name="customer_division" id="customer_division" class="form-control" >
                                                <option value=""> Select Division </option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->province_id}}"
                                                        @if(isset($customer))
                                                            {{$province->province_id == $customer->province_id ?
                                                            'selected' : '' }}
                                                        @endif>
                                                        {{ $province->province_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_township')}} <span class="cancel_status">*</sapn> </label>
                                            <select name="customer_township" id="customer_township" class="form-control">
                                            <option value=""> Select Township</option>
                                                @foreach($amphurs as $amphur)
                                                    <option value="{{ $amphur->amphur_id}}"
                                                        @if(isset($customer))
                                                            {{$amphur->amphur_id == $customer->amphur_id ?
                                                            'selected' : '' }}
                                                        @endif>
                                                        {{ $amphur->amphur_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_address')}} <span class="cancel_status">*</sapn> </label>
                                            <input name="customer_address" id="customer_address" type="text" class="form-control" value="{{isset($customer) ? $customer->address : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary col-md-2 mr-2 mb-2">{{ __('button.save') }}</button>
                                <a class="btn btn-light col-md-2 mr-2 mb-2" href="{{ route('tickets.ticket_headers') }}">{{ __('button.back') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade show_promotion_info" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-l">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="product_modal_title">Promotion Infomation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="product_form">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <label style="font-weight:bold" id="lucky_draw_name"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Branches :</label>
                                            <label style="font-weight:bold" id="lucky_draw_branches"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Categories :</label>
                                            <label style="font-weight:bold"id="lucky_draw_categories"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Brands :</label>
                                            <label style="font-weight:bold" id="lucky_draw_brands"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Discon Status :</label>
                                            <label style="font-weight:bold" id="discon_status"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Amount For 1 Ticket :</label>
                                            <label style="font-weight:bold" id="lucky_draw_promotion_amount"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Start Date :</label>
                                            <label style="font-weight:bold" id="start_date"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>End Date :</label>
                                            <label style="font-weight:bold" id="end_date"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade try_step_sale_promotion" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-l">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="product_modal_title">Step Sale Promotion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('update_ticket_amount_by_step_sale',$ticket_header->uuid)}}">
                        @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Available Amount :</label>
                                            <input name="available_amount" id="available_amount" style="border: 0px; font-size: 20px; font-weight: bold;" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Remain Amount :</label>
                                            <input name="remain_amount" id="remain_amount" style="border: 0px; font-size: 20px; font-weight: bold;" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Step Sale Type:</label>
                                            <table class="table mb-0 tbl-server-info">
                                                <tr>
                                                    <td>20 lk</td>
                                                    <td>
                                                        <input class="form-control" type="text" name="20lk" id="20lk">
                                                        <input class="form-control" type="hidden" name="20lk" id="old_20lk">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>30 lk</td>
                                                    <td>
                                                        <input class="form-control" type="text" name="30lk" id="30lk">
                                                        <input class="form-control" type="hidden" name="30lk" id="old_30lk">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>40 lk</td>
                                                    <td><input class="form-control" type="text" name="40lk" id="40lk">
                                                    <input class="form-control" type="hidden" name="40lk" id="old_40lk">
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>50 lk</td>
                                                    <td><input class="form-control" type="text" name="50lk" id="50lk">
                                                    <input class="form-control" type="hidden" name="50lk" id="old_50lk">
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>100 lk</td>
                                                    <td><input class="form-control" type="text" name="100lk" id="100lk">
                                                    <input class="form-control" type="hidden" name="100lk" id="old_100lk"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="step_sale_promotion_form_confirm">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
    $('#customer_phone_no').keydown(function(e) {
        var ticket_header_printed_at = $('#ticket_header_printed_at').val();
        if(ticket_header_printed_at){
            e.preventDefault();
        }else{
            let key = e.key;
            let keyCharCode = key.charCodeAt(0);
            // 0-9
            if(keyCharCode >= 48 && keyCharCode <= 57) {
                return true;
            }
            // Backspace
            if(keyCharCode == 66) {
                return true;
            }
            e.preventDefault();
        }
    });

    $('#phone_no2').keydown(function(e) {
        var ticket_header_printed_at = $('#ticket_header_printed_at').val();
        if(ticket_header_printed_at){
            e.preventDefault();
        }else{
            let key = e.key;
            let keyCharCode = key.charCodeAt(0);
            // 0-9
            if(keyCharCode >= 48 && keyCharCode <= 57) {
                return true;
            }
            // Backspace
            if(keyCharCode == 66) {
                return true;
            }
            e.preventDefault();
        }
    });
     
    $("#nrc_name").select2({
        width: '100%',
        allowClear: true,
    });
    $("#customer_township").select2({
        width: '100%',
        allowClear: true,
    });
    $(".select2-selection__clear").hide();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function validateForm() {
        if ($('#customer_phone_no').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_customer_phone_no') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }else{
            if ($('#customer_phone_no').val() == "09777777777") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.fill_correct_phone_no') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
        }
        if ($('#firstname').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_customer_first_name') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }else{
            if ($('#firstname').val() == "Cash") {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.fill_correct_first_name') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
        }

        if ($('#email').val() != "") {
            var re = /\S+@\S+\.\S+/;
            var result = re.test($('#email').val());
            if(!result){
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                text: "{{ __('message.email_format_is_wrong') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
        }

        if ($('#customer_division').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.choose_customer_division') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#customer_township').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.choose_customer_township') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#customer_address').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.fill_customer_address') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#total_valid_ticket_qty').val() < 1) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.total_ticket_must_be_more_than_one') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        return true;
    }

    $(document).ready(function() {
        var invoice_no = document.getElementById("invoice_no");
        invoice_no.onkeyup = function(e){
            if(e.keyCode == 13){
                var branch_id = $('#branch_id').val();
                var ticket_type = $('#ticket_type').val();
                var lucky_draw_uuid = $('#lucky_draw_uuid').val();
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
                        url: '../../tickets/add_invoice',
                        type: 'post',
                        data: {
                            "_token": token,
                            "branch_id": branch_id,
                            "ticket_type" : ticket_type,
                            "lucky_draw_uuid" : lucky_draw_uuid,
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
                                if(response.data.old_user_name)
                                {
                                    Swal.fire({
                                            icon: 'warning',
                                            title: `{{ __('message.customer_is_not_same') }}`,
                                            html: "choose_customer" + "<br>" + "<table class='table'><tr><th width='50%'>"+ response.data.old_user_name + "</th><th width='50%'>"+ response.data.new_user_name+"</th></tr><tr><td width='50%'>"+ response.data.old_phone_no +"</td><td width='50%'>"+ response.data.new_phone_no +"</td></tr></table>",
                                            showDenyButton: true,
                                            showCancelButton: false,
                                            confirmButtonText: response.data.old_user_name,
                                            denyButtonText:  response.data.new_user_name,
                                        }).then((result) => {
                                            if (result.isConfirmed) {

                                                Swal.fire(`{{ __('message.choosed_old_customer') }}`, '', 'info').then(function(){
                                                    var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                    window.location = url;
                                                })
                                            } else if (result.isDenied) {
                                                $.ajax({
                                                    url: '../../tickets/update_ticket_header_customer',
                                                    type: 'post',
                                                    data: {
                                                        "_token": token,
                                                        "ticket_header_uuid": ticket_header_uuid,
                                                        "customer_id": response.data.new_customer_id,
                                                        "invoice_id": response.data.invoice_id,
                                                    },
                                                })
                                                Swal.fire(`{{ __('message.choosed_new_customer') }}`, '', 'info').then(function(){
                                                    var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                    window.location = url;
                                                })
                                            }
                                        }
                                    )
                                }else{
                                    message = response.data.message;
                                    Swal.fire({
                                        icon: 'success',
                                        title: "{{ __('message.success') }}",
                                        text: `{{ __('message.successfully_created') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    }).then(function(){
                                        var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                        window.location = url;
                                    });
                                }
                            }
                            else
                            {
                                if(response.error == 'can_not_add_invoice_when_ticket_is_generated')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_add_invoice_when_ticket_is_generated') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'can_not_add_invoice_when_step_sale_is_generated')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_add_invoice_when_step_sale_is_generated') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_not_found')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_not_found') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_used')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_used') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_expired')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_expired') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'customer_is_not_same')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.customer_is_not_same') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'ticket_header_uuid_error')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.ticket_header_uuid_error') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'can_not_remove_invoice_when_ticket_is_generated')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_remove_invoice_when_ticket_is_generated') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'permission_denied')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.permission_denied') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'promotion_expired')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.promotion_expired') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'promotion_is_not_start')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.promotion_is_not_start') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'accept_adding_only_10_invoices')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.accept_adding_only_10_invoices') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'can_not_use_this_invoice')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_use_this_invoice') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'promotion_image_is_not_uploaded')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.promotion_image_is_not_uploaded') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                                if(response.error == 'invoice_is_used_for_deposit_invoice')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.invoice_is_used_for_deposit_invoice') }}`,
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
            }
        }

        $(document).on('click',"#view_promotion_info", function(){
            var lucky_draw_uuid = $('#lucky_draw_uuid').val();
            var token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: '../../lucky_draws/'+ lucky_draw_uuid,
                type: 'get',
                data: {
                    "_token": token,
                },
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {
                    jQuery("#loading").hide();
                },
                success: function(response) {
                    $('#lucky_draw_name').text(response.lucky_draw);
                    $('#lucky_draw_branches').text(response.lucky_draw_branches);
                    $('#lucky_draw_categories').text(response.lucky_draw_categories);
                    $('#lucky_draw_brands').text(response.lucky_draw_brands);
                    $('#discon_status').text(response.lucky_draw_discon);
                    $('#lucky_draw_promotion_amount').text(response.lucky_draw_promotion_amount.toLocaleString());
                    $('#start_date').text(response.lucky_draw_start_date);
                    $('#end_date').text(response.lucky_draw_end_date);
                    $('.show_promotion_info').modal('show');
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
        })

        $(document).on('change',"#ticket_type", function(){
            $("#lucky_draw_id option").remove();
            var ticket_type = this.value;
            if(ticket_type == 1){
                $('#invoice_no').attr('placeholder','Type CA,SA');
            }
            else if(ticket_type == 2){
                $('#invoice_no').attr('placeholder','Type CA,SA');
            }else if(ticket_type == 3){
                $('#invoice_no').attr('placeholder','Type RD');
            }else{
                $('#invoice_no').attr('placeholder','Type CA,SA of Return Deposit');
            }
            var token = $("meta[name='csrf-token']").attr("content");
            if (ticket_type) {
                $.ajax({
                    url: '../../lucky_draw_search_by_type',
                    type: 'get',
                    data: {
                        "_token": token,
                        "ticket_type" : ticket_type,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $.each( response, function(k, v) {
                            $('#lucky_draw_id').append($('<option>', {value:k, text:v}));
                        });
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
        $(document).on("click", "#add_invoice", function() {
            var branch_id = $('#branch_id').val();
            var ticket_type = $('#ticket_type').val();
            var lucky_draw_uuid = $('#lucky_draw_uuid').val();
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
                    url: '../../tickets/add_invoice',
                    type: 'post',
                    data: {
                        "_token": token,
                        "branch_id": branch_id,
                        "ticket_type" : ticket_type,
                        "lucky_draw_uuid" : lucky_draw_uuid,
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
                            if(response.data.old_user_name)
                            {
                                Swal.fire({
                                        icon: 'warning',
                                        title: `{{ __('message.customer_is_not_same') }}`,
                                        html: "{{ __('message.choose_customer') }}" + "<br>" + "<table class='table'><tr><th width='50%'>"+ response.data.old_user_name + "</th><th width='50%'>"+ response.data.new_user_name+"</th></tr><tr><td width='50%'>"+ response.data.old_phone_no +"</td><td width='50%'>"+ response.data.new_phone_no +"</td></tr></table>",
                                        showDenyButton: true,
                                        showCancelButton: false,
                                        confirmButtonText: response.data.old_user_name,
                                        denyButtonText:  response.data.new_user_name,
                                    }).then((result) => {
                                        if (result.isConfirmed) {

                                            Swal.fire(`{{ __('message.choosed_old_customer') }}`, '', 'info').then(function(){
                                                var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                window.location = url;
                                            })
                                        } else if (result.isDenied) {
                                            $.ajax({
                                                url: '../../tickets/update_ticket_header_customer',
                                                type: 'post',
                                                data: {
                                                    "_token": token,
                                                    "ticket_header_uuid": ticket_header_uuid,
                                                    "customer_id": response.data.new_customer_id,
                                                    "invoice_id": response.data.invoice_id,
                                                },
                                            })
                                            Swal.fire(`{{ __('message.choosed_new_customer') }}`, '', 'info').then(function(){
                                                var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                                window.location = url;
                                            })
                                        }
                                    }
                                )
                            }else{
                                message = response.data.message;
                                Swal.fire({
                                    icon: 'success',
                                    title: "{{ __('message.success') }}",
                                    text: `{{ __('message.successfully_created') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                }).then(function(){
                                    var url = `/tickets/edit_ticket_header/${response.data.ticket_header_uuid}`;
                                    window.location = url;
                                });
                            }
                        }
                        else
                        {
                            if(response.error == 'can_not_add_invoice_when_ticket_is_generated')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_add_invoice_when_ticket_is_generated') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'can_not_add_invoice_when_step_sale_is_generated')
                                {
                                        Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.can_not_add_invoice_when_step_sale_is_generated') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                            if(response.error == 'invoice_is_not_found')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_not_found') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'invoice_is_used')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_used') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'invoice_is_expired')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.invoice_is_expired') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'customer_is_not_same')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.customer_is_not_same') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'ticket_header_uuid_error')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.ticket_header_uuid_error') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'can_not_remove_invoice_when_ticket_is_generated')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_remove_invoice_when_ticket_is_generated') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'permission_denied')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.permission_denied') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'promotion_expired')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_expired') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'promotion_is_not_start')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_is_not_start') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'accept_adding_only_5_invoices')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.accept_adding_only_5_invoices') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'can_not_use_this_invoice')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.can_not_use_this_invoice') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'promotion_image_is_not_uploaded')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.promotion_image_is_not_uploaded') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'this_invoice_deposit_no_is_used')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.this_invoice_deposit_no_is_used') }}`,
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'this_invoice_sale_invoice_is_used')
                            {
                                    Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: `{{ __('message.this_invoice_sale_invoice_is_used') }}`,
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

        $(document).on('change',"#nrc_no", function(){
            $("#nrc_name option").remove();
            var nrc_no = $('#nrc_no').val();
            var token = $("meta[name='csrf-token']").attr("content");
            if (nrc_no) {
                $.ajax({
                    url: '../../nrc_name_by_nrc_no',
                    type: 'get',
                    data: {
                        "_token": token,
                        "nrc_no" : nrc_no,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $("#nrc_name").empty();
                        $.each( response, function(k, v) {
                            $('#nrc_name').append($('<option>', {value:k, text:v}));
                        });
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

        $(document).on('change',"#customer_division", function(){
            $("#customer_township option").remove();
            var customer_division = $('#customer_division').val();
            var branch_id = $('#branch_id').val();
            var token = $("meta[name='csrf-token']").attr("content");
            if (customer_division) {
                $.ajax({
                    url: '../../customer_township_by_customer_division',
                    type: 'get',
                    data: {
                        "_token": token,
                        "customer_division" : customer_division,
                        "branch_id": branch_id
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $("#customer_township").empty();
                        $.each( response, function(k, v) {
                            $('#customer_township').append($('<option>', {value:k, text:v}));
                        });
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
        $(document).on('click',"#save_button", function(){
            if(validateForm()){
                total_valid_amount = $('#total_valid_amount').val();
                t_h_step_sale_status = $('#t_h_step_sale_status').val();
                if(total_valid_amount > 2000000 && t_h_step_sale_status == 0){
                    if(total_valid_amount > 10000000){
                        s_n = 10000000;
                        qty = total_valid_amount / 10000000;
                        $('#100lk').val(parseInt(qty));
                        $('#old_100lk').val(parseInt(qty));
                        set20lkZero();
                        set30lkZero();
                        set40lkZero();
                        set50lkZero();
                    }else if(total_valid_amount > 5000000 && total_valid_amount < 10000000 ){
                        s_n = 5000000;
                        qty = total_valid_amount / 5000000;
                        $('#50lk').val(parseInt(qty));
                        $('#old_50lk').val(parseInt(qty));
                        set20lkZero();
                        set30lkZero();
                        set40lkZero();
                        set100lkZero();
                    }else if(total_valid_amount > 5000000 ){
                        s_n = 5000000;
                        qty = total_valid_amount / 5000000;
                        $('#50lk').val(parseInt(qty));
                        $('#old_50lk').val(parseInt(qty));
                        set20lkZero();
                        set30lkZero();
                        set40lkZero();
                        set100lkZero();
                    }
                    else if(total_valid_amount > 4000000 && total_valid_amount < 5000000 ){
                        s_n = 4000000;
                        qty = total_valid_amount / 4000000;
                        $('#40lk').val(parseInt(qty));
                        $('#old_40lk').val(parseInt(qty));
                        set20lkZero();
                        set30lkZero();
                        set50lkZero();
                        set100lkZero();
                    }else if(total_valid_amount > 4000000 ){
                        s_n = 4000000;
                        qty = total_valid_amount / 4000000;
                        $('#40lk').val(parseInt(qty));
                        $('#old_40lk').val(parseInt(qty));
                        set20lkZero();
                        set30lkZero();
                        set50lkZero();
                        set100lkZero();
                    }else if(total_valid_amount > 3000000 && total_valid_amount < 4000000 ){
                        s_n = 3000000;
                        qty = total_valid_amount / 3000000;
                        $('#30lk').val(parseInt(qty));
                        $('#old_30lk').val(parseInt(qty));
                        set20lkZero();
                        set40lkZero();
                        set50lkZero();
                        set100lkZero();
                    }else if(total_valid_amount > 3000000 ){
                        s_n = 3000000;
                        qty = total_valid_amount / 3000000;
                        $('#30lk').attr({"max" : parseInt(qty),"min" : 1});
                        $('#30lk').val(parseInt(qty));
                        set20lkZero();
                        set40lkZero();
                        set50lkZero();
                        set100lkZero();
                    }else if(total_valid_amount > 2000000 && total_valid_amount < 3000000 ){
                        s_n = 2000000;
                        qty = total_valid_amount / 2000000;
                        $('#20lk').val(parseInt(qty));
                        $('#old_20lk').val(parseInt(qty));
                        set30lkZero();
                        set40lkZero();
                        set50lkZero();
                        set100lkZero();
                    }else if(total_valid_amount > 2000000 ){
                        s_n = 2000000;
                        qty = total_valid_amount / 2000000;
                        $('#20lk').val(parseInt(qty));
                        $('#old_20lk').val(parseInt(qty));
                        set30lkZero();
                        set40lkZero();
                        set50lkZero();
                        set100lkZero();
                    }
                    $('#available_amount').val(total_valid_amount);
                    remain_valid_amount = total_valid_amount - (s_n * parseInt(qty));
                    $('#remain_amount').val(remain_valid_amount);

                    $('#qty').val(parseInt(qty));
                    $('.try_step_sale_promotion').modal('show');
                }else{
                    $('#customer_save_form').submit();
                }
            }
        })

        function set20lkZero(){
            $('#20lk').val(0);
            $('#old_20lk').val(0);
        }
        function set30lkZero(){
            $('#30lk').val(0);
            $('#old_30lk').val(0);
        }
        function set40lkZero(){
            $('#40lk').val(0);
            $('#old_40lk').val(0);
        }
        function set50lkZero(){
            $('#50lk').val(0);
            $('#old_50lk').val(0);
        }
        function set100lkZero(){
            $('#100lk').val(0);
            $('#old_100lk').val(0);
        }
        function checkDuplicateZero(first_digit,second_digit){
            if(first_digit == 0 && first_digit == 0)
                return false
        }
        function get_all_new_amount(){
            var new_20_qty =  parseInt($('#20lk').val());
            var new_30_qty =  parseInt($('#30lk').val());
            var new_40_qty =  parseInt($('#40lk').val());
            var new_50_qty =  parseInt($('#50lk').val());
            var new_100_qty =  parseInt($('#100lk').val());
            return (new_20_qty * 2000000) + (new_30_qty * 3000000) +(new_40_qty * 4000000) +(new_50_qty * 5000000) +(new_100_qty * 10000000)
        }

        $(document).on('keyup','#20lk',function(){
            var new_qty = parseInt($(this).val());
            var old_qty =  parseInt($('#old_20lk').val());

            if($.isNumeric(new_qty)){
                var total = get_all_new_amount();
                remain_amount = parseInt($('#remain_amount').val());
                available_amount = parseInt($('#available_amount').val());
                if(total == 0 && new_qty != 0){
                    $('#20lk').val(old_qty);
                    $('#old_20lk').val(old_qty);
                }else if(total < remain_amount && new_qty != 0){
                    different_qty = old_qty - new_qty;
                    if(different_qty < 0){
                        amount = 2000000 * different_qty;
                        u_remain_amount = remain_amount + amount;
                    }else{
                        different_qty = new_qty - old_qty;
                        amount = 2000000 * different_qty;
                        u_remain_amount = remain_amount - amount;

                    }
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_20lk').val(new_qty);
                }else if(total > available_amount && new_qty != 0){
                    $('#20lk').val(old_qty);
                    $('#old_20lk').val(old_qty);
                }else{
                    different_qty = old_qty - new_qty;
                    amount = 2000000 * different_qty;
                    u_remain_amount = remain_amount + amount;
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_20lk').val(new_qty);
                }
            }
        })

        $(document).on('keyup','#30lk',function(){
            var new_qty = parseInt($(this).val());
            var old_qty =  parseInt($('#old_30lk').val());

            if($.isNumeric(new_qty)){
                var total = get_all_new_amount();
                remain_amount = parseInt($('#remain_amount').val());
                available_amount = parseInt($('#available_amount').val());
                if(total == 0 && new_qty != 0){
                    $('#30lk').val(old_qty);
                    $('#old_30lk').val(old_qty);
                }else if(total < remain_amount && new_qty != 0){
                    different_qty = old_qty - new_qty;
                    if(different_qty < 0){
                        amount = 3000000 * different_qty;
                        u_remain_amount = remain_amount + amount;
                    }else{
                        different_qty = new_qty - old_qty;
                        amount = 3000000 * different_qty;
                        u_remain_amount = remain_amount - amount;

                    }
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_30lk').val(new_qty);
                }else if(total > available_amount && new_qty != 0){
                    $('#30lk').val(old_qty);
                    $('#old_30lk').val(old_qty);
                }else{
                    different_qty = old_qty - new_qty;
                    amount = 3000000 * different_qty;
                    u_remain_amount = remain_amount + amount;
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_30lk').val(new_qty);
                }
            }
        })

        $(document).on('keyup','#40lk',function(){
            var new_qty = parseInt($(this).val());
            var old_qty =  parseInt($('#old_40lk').val());
            if($.isNumeric(new_qty)){
                var total = get_all_new_amount();
                remain_amount = parseInt($('#remain_amount').val());
                available_amount = parseInt($('#available_amount').val());
                if(total == 0 && new_qty != 0){
                    $('#40lk').val(old_qty);
                    $('#old_40lk').val(old_qty);
                }else if(total < remain_amount && new_qty != 0){
                    different_qty = old_qty - new_qty;
                    if(different_qty < 0){
                        amount = 4000000 * different_qty;
                        u_remain_amount = remain_amount + amount;
                    }else{
                        different_qty = new_qty - old_qty;
                        amount = 4000000 * different_qty;
                        u_remain_amount = remain_amount - amount;

                    }
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_40lk').val(new_qty);
                }else if(total > available_amount && new_qty != 0){
                    $('#40lk').val(old_qty);
                    $('#old_40lk').val(old_qty);
                }else{
                    different_qty = old_qty - new_qty;
                    amount = 4000000 * different_qty;
                    u_remain_amount = remain_amount + amount;
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_40lk').val(new_qty);
                }
            }
        })

        $(document).on('keyup','#50lk',function(){
            var new_qty = parseInt($(this).val());
            var old_qty =  parseInt($('#old_50lk').val());

            if($.isNumeric(new_qty)){
                var total = get_all_new_amount();
                remain_amount = parseInt($('#remain_amount').val());
                available_amount = parseInt($('#available_amount').val());
                if(total == 0 && new_qty != 0){
                    $('#50lk').val(old_qty);
                    $('#old_50lk').val(old_qty);
                }else if(total < remain_amount && new_qty != 0){
                    different_qty = old_qty - new_qty;
                    if(different_qty < 0){
                        amount = 5000000 * different_qty;
                        u_remain_amount = remain_amount + amount;
                    }else{
                        different_qty = new_qty - old_qty;
                        amount = 5000000 * different_qty;
                        u_remain_amount = remain_amount - amount;

                    }
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_50lk').val(new_qty);
                }else if(total > available_amount && new_qty != 0){
                    $('#50lk').val(old_qty);
                    $('#old_50lk').val(old_qty);
                }else{
                    different_qty = old_qty - new_qty;
                    amount = 5000000 * different_qty;
                    u_remain_amount = remain_amount + amount;
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_50lk').val(new_qty);
                }
            }
        })

        $(document).on('keyup','#100lk',function(){
            var new_qty = parseInt($(this).val());
            var old_qty =  parseInt($('#old_100lk').val());

            if($.isNumeric(new_qty)){
                var total = get_all_new_amount();
                remain_amount = parseInt($('#remain_amount').val());
                available_amount = parseInt($('#available_amount').val());
                if(total == 0 && new_qty != 0){
                    $('#100lk').val(old_qty);
                    $('#old_100lk').val(old_qty);
                }else if(total < remain_amount && new_qty != 0){
                    different_qty = old_qty - new_qty;
                    if(different_qty < 0){
                        amount = 10000000 * different_qty;
                        u_remain_amount = remain_amount + amount;
                    }else{
                        different_qty = new_qty - old_qty;
                        amount = 10000000 * different_qty;
                        u_remain_amount = remain_amount - amount;

                    }
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_100lk').val(new_qty);
                }else if(total > available_amount && new_qty != 0){
                    $('#100lk').val(old_qty);
                    $('#old_100lk').val(old_qty);
                }else{
                    different_qty = old_qty - new_qty;
                    amount = 10000000 * different_qty;
                    u_remain_amount = remain_amount + amount;
                    $('#remain_amount').val(u_remain_amount);
                    $('#old_100lk').val(new_qty);
                }
            }
        })

        $(document).on('click','#step_sale_promotion_form_confirm', function(){
            var ticket_header_uuid = $('#ticket_header_uuid').val();
            var st_20lk =  $("#20lk").val();
            var st_30lk =  $("#30lk").val();
            var st_40lk =  $("#40lk").val();
            var st_50lk =  $("#50lk").val();
            var st_100lk =  $("#100lk").val();
            var token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: '../../update_ticket_amount_by_step_sale/'+ ticket_header_uuid,
                type: 'post',
                data: {
                    "_token": token,
                    "st_20lk": st_20lk,
                    "st_30lk": st_30lk,
                    "st_40lk": st_40lk,
                    "st_50lk": st_50lk,
                    "st_100lk": st_100lk,
                },
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {
                    jQuery("#loading").hide();
                },
                success: function(response) {
                    $('.try_step_sale_promotion').modal('hide');
                    $('#customer_save_form').submit();
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
        })
    })

    var table = $('#invoice_list_by_ticket_header').DataTable({
        'info': true,
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "autoWidth": true,
        "responsive": true,
        "pageLength": 10,
        "scrollY": "600px",
        "scrollCollapse": true,
        "bInfo" : false,
        'ajax': {
            'url': "/invoice_list_by_ticket_header",
            'type': 'GET',
            'data': function(d) {
                d.ticket_header_uuid = $('#ticket_header_uuid').val();
            }
        },
        columns: [{
                data: 'invoice_no',
                name: 'invoice_no',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'valid_amount',
                name: 'valid_amount',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'valid_ticket_qty',
                name: 'valid_ticket_qty',
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
                    return data;
                    id = row.id;
                    return id;
                    return `
                    <a class="badge bg-warning mr-2"  title="Delete" href="../../delete_invoice/"${id}"><i class="ri-delete-bin-line mr-0"></i></a>`
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
        }],
    })

    $('#customer_phone_no').focusout(function() {
        var customer_phone_no = $(this).val();
        var branch_id = $('#branch_id').val();
        var ticket_header_printed_at = $('#ticket_header_printed_at').val();
        if (customer_phone_no && !ticket_header_printed_at) {
            $.ajax({
                url: '../../customers/get_customer_by_phone_no/' + branch_id + '/' + customer_phone_no,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {
                    jQuery("#loading").hide();
                },
                success: function(response) {
                    if (response.data != null) {
                            $('#customer_id').val('');
                            $('#customer_id').val(response.data.customer_id);
                            $('#customer_phone_no').val('');
                            $('#customer_phone_no').val(response.data.phone_no ?? response.data.mobile);
                            $('#phone_no2').val('');
                            $('#phone_no2').val(response.data.phone_no_2);
                            $('#titlename').val('');
                            $('#titlename').val(response.data.titlename);
                            $('#firstname').val('');
                            $('#firstname').val(response.data.firstname);
                            $('#lastname').val('');
                            $('#lastname').val(response.data.lastname);
                            $('#customer_type').val('');
                            $('#customer_type').val(response.data.customer_type);
                            $('#customer_no').val('');
                            $('#customer_no').val(response.data.customer_no);
                            $('#nrc_no').val('');
                            $('#nrc_no').val(response.data.nrc_no);
                            $('#nrc_name').val('');
                            $('#nrc_name').val(response.data.nrc_name).trigger("change");
                            $('#nrc_short').val('');
                            $('#nrc_short').val(response.data.nrc_short);
                            $('#nrc_number').val('');
                            $('#nrc_number').val(response.data.nrc_number);
                            $('#email').val('');
                            $('#email').val(response.data.email);
                            $('#passport').val('');
                            $('#passport').val(response.data.passport);
                            $('#customer_division').val('');
                            $('#customer_division').val(response.data.province_id);
                            $('#customer_township').val('');
                            $('#customer_township').val(response.data.amphur_id).trigger("change");
                            $('#customer_address').val('');
                            $('#customer_address').val(response.data.full_address);


                    } else {
                        $('#customer_id').val('');
                        $('#phone_no2').val('');
                        $('#titlename').val('Mr.');
                        $('#firstname').val('');
                        $('#lastname').val('');
                        $('#customer_type').val('');
                        $('#customer_no').val('');
                        $('#nrc_no').val('');
                        $('#nrc_name').val('');
                        $('#nrc_short').val('');
                        $('#nrc_number').val('');
                        $('#email').val('');
                        $('#passport').val('');
                        $('#customer_division').val('');
                        $('#customer_township').val('');
                        $('#customer_address').val('');
                        $('#customer_type').val('New');
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.customer_not_found') }}",
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
    });
</script>
@endsection
