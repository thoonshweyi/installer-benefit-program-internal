@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('ticket.check_ticket') }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('ticket.lucky_draw')}} </label>
                                        <select id="lucky_draw_type_status" class="form-control ">
                                            <option value="0">All Status</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('ticket.ticket_no')}} </label>
                                        <input name="document_date" type="text" class="form-control" >
                                    </div>
                                </div> 
                                <button type="submit" class="btn btn-primary mr-2">{{ __('button.search') }}</button>
                            </div>        
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12">
               <div class="card card-block card-stretch card-height blog pricing-details">
                  <div class="card-body text-center rounded">
                     <div class="pricing-header bg-primary text-white">
                        <div class="icon-data mb-3"><i class="ri-star-line"></i>
                        </div>
                        <h2 class="mb-4 display-5 font-weight-bolder text-white">$26<small class="font-size-14">/ Month</small></h2>
                     </div>
                     <h3 class="mb-3">Premium Plan</h3>
                     <ul class="list-unstyled mb-0">
                        <li>Lorem ipsum dolor sit amet</li>
                        <li>Consectetur adipiscing elit</li>
                        <li>Integer molestie at massa</li>
                        <li>Facilisis in pretium nisl aliquet</li>
                        <li>Nulla volutpat aliquam velit</li>
                     </ul> <a href="#" class="btn btn-primary mt-5">Activate</a>
                  </div>
               </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        var table = $('#lucky_draw_types_list1').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": false,
            "autoWidth": true,
            "responsive": true,
            "pageLength": 10,
            "scrollY": "450px",
            "scrollCollapse": true,
            'ajax': {
                'url': "/lucky_draw_types/search_result",
                'type': 'GET',
                'data': function(d) {
                    d.lucky_draw_type_name = $('#lucky_draw_type_name').val();
                    d.lucky_draw_type_status = $('#lucky_draw_type_status').val();
                }
            },
            columns: [{
                    data: 'lucky_draw_type_name',
                    name: 'lucky_draw_type_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/lucky_draw_types/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'lucky_draw_type_description',
                    name: 'lucky_draw_type_description',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/lucky_draw_types/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'lucky_draw_type_status',
                    name: 'lucky_draw_type_status',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/lucky_draw_types/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        if (row.document_status >= 2) {
                            return `<div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        href="/documents/${row.id}/edit"><i class="ri-eye-line mr-0"></i></a>
                                </div>`
                        };
                        if (data == true) {
                            return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        href="/documents/${row.id}/edit"><i class="ri-eye-line mr-0"></i></a>
                                    
                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete"
                                        id="delete" href="#"" data-document_id="${row.id}"
                                        ><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>`
                        }
                        return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        href="/documents/${row.id}/edit"><i class="ri-eye-line mr-0"></i></a>
                                </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        $('#document_search').on('click', function(e) {
            $('#document_list').DataTable().draw(true);
        })
        table.on('click', '#delete', function(e) {

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
                    var document_id = $(this).data('document_id');
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '/documents/' + document_id + '/delete',
                        type: 'GET',

                        data: {
                            "_token": token,
                            "document_id": document_id,
                        },
                        beforeSend: function() {
                            jQuery("#load").fadeOut();
                            jQuery("#loading").show();
                        },
                        complete: function() {
                            jQuery("#loading").hide();
                        },
                        success: function(response) {
                            $('#document_list').DataTable().draw(true);
                        }
                    });
                }
            });
        });
        $('#document_export').on('click', function(e) {
            var today = new Date().toISOString().slice(0, 10);
            var document_no = $('#document_no').val();
            var document_from_date = $('#document_from_date').val().length === 0 ? today : $('#document_from_date').val();
            var document_to_date = $('#document_to_date').val().length === 0 ? today : $('#document_to_date').val();
            var document_type = $('#document_type').val();
            var document_branch = $('#document_branch').val();
            var document_status = $('#document_status').val();
            var category = $('#category').val();
            var other = document_no + '-' + document_type + '-' + document_branch + '-' + document_status + '-' + category;
            var url = `/documents/document_export/${document_from_date}/${document_to_date}/${other}`;
            window.location = url;
        })
    });
</script>
@stop