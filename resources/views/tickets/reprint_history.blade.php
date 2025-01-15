@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('ticket.reprint_history') }}</h4>
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
                        <div class="row">
                            <input type="hidden" name="claim_history_uuid" id="claim_history_uuid" value="{{$claim_history->uuid}}" />
                            <input type="hidden" name="user_uuid" id="user_uuid" value="" />
                            <div class="col-md-12">
                                <a class="btn btn-success" href="javascript: reprint()">Reprint</a>
                                <a class="btn btn-light" href="{{ route('tickets.show',$claim_history->ticket_header_uuid) }}">{{__('button.back')}}</a>
                            </div>
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="reprint_history_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>{{__('ticket.printed_user')}}</th>
                                            <th>{{__('ticket.printed_at')}}</th>
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

        </div>
    </div>
</div>
<iframe id="reprint_frame" name="reprint_frame" src="{{ asset('tickets/'.$claim_history->uuid.'.pdf') }}" style="position: absolute;width:0;height:0;border:0;"></iframe>
<!-- Page end  -->
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">

    function reprint(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        Swal.fire({
            title: 'Please Enter Employee ID and Password',
            html:
                '<div class="floating-label form-group">' +
                    '<label>Employee ID</label>' +
                    '<input class="floating-input form-control keyboardType0" id="employee_id" name="employee_id" value="{{ old('login_value') }}" placeholder="" autofocus required>' +
                '</div>'+
                '<div class="floating-label form-group">' +
                    '<label>Password</label>' +
                    '<input class="floating-input form-control keyboardType0" type="password" id="password" name="password" value="{{ old('login_value') }}" placeholder="" autofocus required>' +
                '</div>',
            preConfirm: function () {
                return new Promise(function (resolve) {
                resolve([
                    $('#employee_id').val(),
                    $('#password').val()
                ])
                })
            },
            onOpen: function () {
                $('#employee_id').focus()
            }
            }).then(function (result) {
                employee_id = result.value[0];
                password = result.value[1];
                $.ajax({
                        url: '/check_user',
                        type: 'post',
                        data: {
                            "employee_id": employee_id,
                            "password": password,
                    },
                    success: function(response) {
                        if(response.data != null){
                            var user_uuid = response.data.user_uuid;
                            var claim_history_uuid = $('#claim_history_uuid').val();
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            });
                            $.ajax({
                                url: '../../add_reprint_history',
                                type: 'get',
                                data: {
                                    "user_uuid": user_uuid,
                                    "claim_history_uuid": claim_history_uuid,
                                },
                                success: function(response) {
                                    $('#reprint_history_list').DataTable().draw(true);
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
                            var pdfFrame1 = window.frames["reprint_frame"];
                            pdfFrame1.focus();
                            pdfFrame1.print();
                        }else{
                            if(response.error == 'user_not_found'){
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: "{{ __('message.user_not_found') }}",
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'password_is_not_correct'){
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: "{{ __('message.password_is_not_correct') }}",
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                            if(response.error == 'do_not_have_permission_to_reprint'){
                                Swal.fire({
                                    icon: 'warning',
                                    title: "{{ __('message.warning') }}",
                                    text: "{{ __('message.do_not_have_permission_to_reprint') }}",
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                            }
                        }
                        console.log(response);
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.validation_error') }}",
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                    }
                })
            }).catch(swal.noop)
    }
    $(document).ready(function() {
        var table = $('#reprint_history_list').DataTable({
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
                'url': "/reprint_history_list",
                'type': 'GET',
                'data': function(d) {
                    d.claim_history_uuid = $('#claim_history_uuid').val();
                }
            },
            columns: [
                {
                    data: 'printed_user',
                    name: 'printed_user',
                    orderable: true,
                    render: function(data, type, row) {
                        return `${data}</a>`;
                    }
                },
                {
                    data: 'printed_at',
                    name: 'printed_at',
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


    });
</script>
@endsection
