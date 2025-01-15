
@extends('layouts.app')

@section('content')
<div class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
          <div>
            <h4 class="mb-3">{{__('customer_ip.customer_ip_address')}}</h4>
          </div>
        </div>
      </div>
      <meta name="csrf-token" content="{{ csrf_token() }}">
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
      <div class="col-lg-12 d-flex mb-4">
        <div class="form-row col-md-2">
            <label>{{__('customer_ip.branch')}} </label>
            <select id="branch_id" class="form-control ">
                <option value="">All Branch</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->branch_id }}" {{ $branch->branch_id == old('document_branch') ? 'selected' : '' }}>
                        {{ $branch->branch_name_eng }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-row col-md-2">
          <label>{{__('customer_ip.no')}} </label>
          <input type="input" class="form-control" id="no" value="">
        </div>
        <div class="form-row col-md-2">
          <label>{{__('customer_ip.ip_address')}} </label>
          <input type="input" class="form-control" id="ip_address" value="">
        </div>
            <button id="customer_ip_search" class="btn btn-primary main_button mr-2">{{__('button.search')}}</button>
            <button id="user_add" class="btn btn-secondary main_button" onclick=location.href="{{ route('customer_ips.create') }}">{{__('button.add_new')}}</button>
        </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="table-responsive rounded mb-3">
      <table class="table mb-0 tbl-server-info" id="customer_ip_list">
        <thead class="bg-white text-uppercase">
          <tr class="ligth ligth-data">
            <th>{{__('customer_ip.branch')}}</th>
            <th>{{__('customer_ip.no')}}</th>
            <th>{{__('customer_ip.ip_address')}}</th>
            <th>{{__('customer_ip.action')}}</th>
          </tr>
        </thead>
        <tbody class="ligth-body">
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

@endsection
@section('js')
<script>
  $(document).ready(function() {
    var table = $('#customer_ip_list').DataTable({
      "processing": true,
      "serverSide": true,
      "searching": false,
      "lengthChange": false,
      "autoWidth": true,
      "responsive": true,
      "order": [
        [1, 'des']
      ],
      'ajax': {
        'url': "/customer_ips",
        'type': 'GET',
        'data': function(d) {
          d.no = $('#no').val();
          d.ip_address = $('#ip_address').val();
          d.branch_id = $('#branch_id').val();
        }
      },
      columns: [
        {
          data: 'branch_id',
          name: 'branch_id',
          orderable: true
        },
        {
          data: 'no',
          name: 'no',
          orderable: true
        },{
          data: 'ip_address',
          name: 'ip_address',
          orderable: true
        },

        {
          data: 'action',
          name: 'action',
          orderable: false,
          render: function(data, type, row) {
            return `<div class="d-flex align-items-center list-action">
                        <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Detail" data-original-title="Detail"
                            href="/customer_ips/${row.id}"><i class="ri-eye-line mr-0"></i></a>
                        <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="Detail" data-original-title="Detail"
                            href="/customer_ips/${row.id}/edit"><i class="ri-edit-line mr-0"></i></a>
                        <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete"
                            id="delete" href="#"" data-id="${row.id}"><i class="ri-delete-bin-line mr-0"></i></a>
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

    $('#customer_ip_search').on('click', function(e) {
      $('#customer_ip_list').DataTable().draw(true);
    })
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    table.on('click', '#delete', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).data('id');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '/customer_ips/' + id,
                    type: 'Delete',

                    data: {
                        "_token": token,
                        "id": id,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                        success: function(response) {
                             $('#customer_ip_list').DataTable().draw(true);
                            },
                        error: function() {
                            $('#customer_ip_list').DataTable().draw(true);
                        }
                    });
                } else {
                    return false;
                    }
        });
      });
    });
</script>
@stop
