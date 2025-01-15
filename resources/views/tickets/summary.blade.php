@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('ticket.summary') }}: {{$ticket_header->ticket_header_no}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                        <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{$ticket_header->uuid}}" />
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('ticket.customer_phone_no')}} </label>
                                            <input name="customer_phone_no" id="customer_phone_no" type="text" class="form-control" value={{isset($customer) ? $customer->phone_no : ''}} disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('ticket.firstname')}}</label>
                                            <input name="firstname" id="firstname" type="text" class="form-control" value="{{isset($customer) ? $customer->firstname : ''}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('ticket.lastname')}}</label>
                                            <input name="lastname" id="lastname" type="text" class="form-control" value="{{isset($customer) ? $customer->lastname : ''}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('ticket.total_ticket') }} </label>
                                            <input name="firstname" id="firstname" type="text" class="form-control" value={{$ticket_header->total_valid_ticket_qty}} disabled>
                                        </div>
                                    </div>
                                </div>
                                @if($ticket_header_step_sales->isNotEmpty())
                                <div class="row">
                                    @foreach($ticket_header_step_sales as $ticket_header_step_sale)
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>{{ __('ticket.used_step_sale_promotion') }} </label>
                                                @if($ticket_header_step_sale->step_sale_type == 1)
                                                <input name="firstname" id="firstname" type="text" class="form-control" value='20 lk' disabled>
                                                @endif
                                                @if($ticket_header_step_sale->step_sale_type == 2)
                                                <input name="firstname" id="firstname" type="text" class="form-control" value='30 lk' disabled>
                                                @endif
                                                @if($ticket_header_step_sale->step_sale_type == 3)
                                                <input name="firstname" id="firstname" type="text" class="form-control" value='40 lk' disabled>
                                                @endif
                                                @if($ticket_header_step_sale->step_sale_type == 4)
                                                <input name="firstname" id="firstname" type="text" class="form-control" value='50 lk' disabled>
                                                @endif
                                                @if($ticket_header_step_sale->step_sale_type == 5)
                                                <input name="firstname" id="firstname" type="text" class="form-control" value='100 lk' disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>{{ __('ticket.used_step_sale_qty') }} </label>
                                                <input name="firstname" id="firstname" type="text" class="form-control" value={{$ticket_header_step_sale->qty}} disabled>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('ticket.ticket_no')}} </label>
                                            <textarea name="customer_address" id="customer_address" class="form-control" oninput="auto_grow(this)" >{{implode(", ", $tickets);}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"  class='ml-10'>
                                    <div class="col-md-12" >
                                    <iframe src={{asset('tickets/'.$filename.'.pdf')}} id="frame" width="800" height="1200" style="position: absolute;width:0;height:0;border:0;"></iframe>
                                  </div>
                                </div>
                                @can('print-ticket')
                                    @if(!$ticket_header->printed_at || @auth()->user()->can('reprint-ticket'))
                                    <button class="btn btn-success col-md-2 mr-2" id="print"  onclick="print()">{{ __('button.print') }}</button>
                                    @endif
                                @endcan
                                <a class="btn btn-light" href="{{ route('tickets.ticket_headers') }}">{{ __('button.back') }}</a>

                            </form>
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
    function print() {
        var frame = document.getElementById('frame');
        frame.contentWindow.focus();
        var ticket_header_uuid = $('#ticket_header_uuid').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        $.ajax({
            url: '../../remove_ticket_file',
            type: 'get',
            data: {
                "ticket_header_uuid": ticket_header_uuid,
            },
            success: function(response) {

            },
            error: function() {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.validation_error') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
            }
        });
        $('#print').hide();
        frame.contentWindow.print();
    }
    window.onafterprint = (event) => {
        console.log('After print');
    };
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
    function auto_grow(element) {
        element.style.height = "5px";
        element.style.height = (element.scrollHeight)+"px";
    }
</script>
@endsection
