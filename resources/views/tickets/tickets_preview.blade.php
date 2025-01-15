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
                                            <label>{{__('ticket.customer_phone_no')}} (*) </label>
                                            <input name="customer_phone_no" id="customer_phone_no" type="text" class="form-control" value={{isset($customer) ? $customer->phone_no : ''}} required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('ticket.firstname')}} (*) </label>
                                            <input name="firstname" id="firstname" type="text" class="form-control" value={{isset($customer) ? $customer->firstname : ''}} required>
                                        </div>
                                    </div>
                                </div>
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
                                    <iframe src={{asset('tickets/'.$filename.'.pdf')}} id="frame" width="400" height="400" style="position: absolute;width:0;height:0;border:0;"></iframe>
                                   
                                  </div>
                                </div>
                                <button class="btn btn-success col-md-2 mr-2" id="print"  onclick="print()">{{ __('button.print') }}</button>

                                <a class="btn btn-light" href="{{ route('tickets.index') }}">{{ __('button.back') }}</a>

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
                $('#product_code_no').addClass('is-invalid');
                $('#product_code_noFeedback').removeClass("d-none");
                $('#product_name').val("");
                $('#product_unit').val("");
                $('#stock_quantity').val("");
                $('#operation_remark').val("");
            }
        });
        frame.contentWindow.print();
    }
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
