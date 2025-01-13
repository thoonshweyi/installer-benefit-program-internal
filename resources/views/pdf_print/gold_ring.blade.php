@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="row"  class='ml-10'>
                    <div class="col-md-12" >
                    <iframe src={{asset('tickets/'.'ring'.'.pdf')}} id="frame" width="800" height="1200" ></iframe>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="" />
                                <button class="btn btn-success col-md-2 mr-2" id="print"  onclick="print()">{{ __('button.print') }}</button>
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
        $('#print').show();
        var frame = document.getElementById('frame');
        // frame.contentWindow.focus();

        var ticket_header_uuid = $('#ticket_header_uuid').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        // $.ajax({
        //     url: '../../remove_ticket_file',
        //     type: 'get',
        //     data: {
        //         "ticket_header_uuid": ticket_header_uuid,
        //     },
        //     success: function(response) {

        //     },
        //     error: function() {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: "{{ __('message.warning') }}",
        //             text: "{{ __('message.validation_error') }}",
        //             confirmButtonText: "{{ __('message.ok') }}",
        //         });
        //     }
        // });
        // $('#print').hide();
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
