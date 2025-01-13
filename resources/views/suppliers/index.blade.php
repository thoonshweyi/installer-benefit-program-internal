@extends('layouts.app')

@section('content')
<div class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
          <div>
            <h4 class="mb-3">{{__('supplier.suppliers')}}</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-12 d-flex mb-4">
        <div class="form-row col-md-2">
          <label>{{__('supplier.supplier_code')}}</label>
          <input type="input" class="form-control" id="supplier_code" value="">
        </div>
        <div class="form-row col-md-2">
          <label>{{__('supplier.supplier_name')}} </label>
          <input type="input" class="form-control" id="supplier_name" value="">
        </div>
        <button id="supplier_search" class="btn btn-primary main_button ml-2 mr-2">{{__('button.search')}}</button>
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

  <div class="col-lg-12">
    <div class="table-responsive rounded mb-3">
      <table class="table mb-0 tbl-server-info" id="supplier_list">
        <thead class="bg-white text-uppercase">
          <tr class="ligth ligth-data">
            <th>{{__('supplier.supplier_code')}}</th>
            <th>{{__('supplier.supplier_name')}}</th>
          </tr>
        </thead>
        <tbody class="ligth-body">
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('js')
<script>
  $(document).ready(function() {

    $('#supplier_list').DataTable({
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
        'url': "/suppliers",
        'type': 'GET',
        'data': function(d) {
          d.supplier_code = $('#supplier_code').val();
          d.supplier_name = $('#supplier_name').val();
        }
      },
      columns: [{
          data: 'vendor_code',
          name: 'vendor_code',
          orderable: true,
          render: function(data, type, row) {
            return `<div style="text-align:left">${data}</div>`;
          }
        },
        {
          data: 'vendor_name',
          name: 'vendor_name',
          orderable: true,
          render: function(data, type, row) {
            return `<div style="text-align:left">${data}</div>`;
          }
        }
      ],
      "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": 0,
      }],
    })

    $('#supplier_search').on('click', function(e) {
      $('#supplier_list').DataTable().draw(true);
    })

    $('#supplier_syn').on('click', function(e) {
      $.ajax({
        url: '../../supplier/syn/' + id + '/' + branch_code,
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
            if (branch_code == response.data.branch_code) {
              $('#product_code_no').removeClass('is-invalid');
              $('#product_name').val('');
              $('#product_name').val(response.data.product_name);
              $('#product_name').attr('readonly', true);
              $('#product_unit').val(response.data.product_unit);
              $('#product_unit').attr('readonly', true);
              $('#stock_quantity').val(Number(response.data.stock_qty));
              $('#stock_quantity').attr('readonly', true);
            } else {
              Swal.fire({
                  icon: 'warning',
                  title: "{{ __('message.warning') }}",
                  text: "{{ __('message.validation_error') }}",
                  confirmButtonText: "{{ __('message.ok') }}",
              });
            }

          } else {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.product_not_found') }}",
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
    })
  });
</script>
@stop