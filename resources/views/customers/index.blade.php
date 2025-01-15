@extends('layouts.app')

@section('content')
<div class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
          <div>
            <h4 class="mb-3">{{__('customer.customers')}}</h4>
          </div>
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
      <div class="col-lg-12 d-flex mb-4">
        <div class="form-row col-md-2">
          <label>{{__('customer.name')}} </label>
          <input type="input" class="form-control" id="customer_name" value="">
        </div>
        <div class="form-row col-md-1">
          <label>{{__('customer.phone_no')}} </label>
          <input type="input" class="form-control" id="customer_phone_no" value="">
        </div>
            <button id="customer_search" class="btn btn-primary main_button mr-2">{{__('button.search')}}</button>
            <!-- <button id="user_add" class="btn btn-secondary main_button" onclick=location.href="{{ route('customers.create') }}">{{__('button.add_new')}}</button> -->
   
        <!-- <button id="user_syn" class="btn btn-success document_search mr-2">Syn Member</button> -->
        </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="table-responsive rounded mb-3">
      <table class="table mb-0 tbl-server-info" id="customer_list">
        <thead class="bg-white text-uppercase">
          <tr class="ligth ligth-data">
            <th>{{__('customer.name')}}</th>
            <th>{{__('customer.phone_no')}}</th>
            <th>{{__('customer.email')}}</th>
            <th>{{__('customer.action')}}</th>
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

    $('#customer_list').DataTable({
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
        'url': "/customers",
        'type': 'GET',
        'data': function(d) {
          d.user_name = $('#customer_name').val();
          d.user_employee_id = $('#customer_phone_no').val();
        }
      },
      columns: [{
          data: 'firstname',
          name: 'firstname',
          // data: 'name',
          // name: 'name',
          orderable: true
        },{
          data: 'phone_no',
          name: 'phone_no',
          orderable: true
        },
        {
          data: 'email',
          name: 'email',
          orderable: true
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          render: function(data, type, row) {
            return `<div class="d-flex align-items-center list-action">
                        <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Detail" data-original-title="Detail"
                            href="/customers/${row.id}"><i class="ri-eye-line mr-0"></i></a>
                    
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

    $('#customer_search').on('click', function(e) {
      $('#customer_list').DataTable().draw(true);
    })
  });
</script>
@stop