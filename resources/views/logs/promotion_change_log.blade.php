@extends('layouts.app')

@section('content')
<div class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
          <div>
            <h4 class="mb-3">{{__('log.logs')}}</h4>
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
          <label>{{__('log.promotion_name')}} </label>
          <input type="input" class="form-control" id="promotion_name" value="">
        </div>
        <div class="form-row col-md-2">
            <label>{{__('log.start_date')}} </label>
            <input type="date" class="form-control" id="start_date" value="">
          </div>
          <div class="form-row col-md-2">
            <label>{{__('log.end_date')}} </label>
            <input type="date" class="form-control" id="end_date" value="">
          </div>
            <button id="log_search" class="btn btn-primary main_button mr-2">{{__('button.search')}}</button>
        </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="table-responsive rounded mb-3">
      <table class="table mb-0 tbl-server-info" id="log_list">
        <thead class="bg-white text-uppercase">
          <tr class="ligth ligth-data">
            <th>{{__('log.promotion_name')}}</th>
            <th>{{__('log.reason')}}</th>
            <th>{{__('log.date')}}</th>
            <th>{{__('log.old_info')}}</th>
            <th>{{__('log.new_info')}}</th>
            <th>{{__('log.user_name')}}</th>
            <th>{{__('log.action')}}</th>
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
    var table = $('#log_list').DataTable({
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
        'url': "/promotion_change_log",
        'type': 'GET',
        'data': function(d) {
          d.start_date = $('#start_date').val();
          d.end_date = $('#end_date').val();
          d.promotion_name = $('#promotion_name').val();
        }
      },
      columns: [{
          data: 'promotion_uuid',
          name: 'promotion_uuid',
          orderable: true
        },
        {
          data: 'reason',
          name: 'reason',
          orderable: true
        },
        {
          data: 'date',
          name: 'date',
          orderable: true
        },
        {
          data: 'old_info',
          name: 'old_info',
          orderable: true
        },
        {
          data: 'new_info',
          name: 'new_info',
          orderable: true
        },
        {
          data: 'user_uuid',
          name: 'user_uuid',
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
            return `<div class="d-flex align-items-center list-action">
                        <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Detail" data-original-title="Detail"
                            href="/promotion_change_show/${row.uuid}"><i class="ri-eye-line mr-0"></i></a>
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

        $('#log_search').on('click', function(e) {
            $('#log_list').DataTable().draw(true);
            })

    });
</script>
@stop
