@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('reports.cash_coupon_issed_report')}}</h4>
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
            <div class="col-lg-12 d-flex mb-2">
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.promotion_name')}} </label>
                    <select class="checkbox-input" id="lucky_draw_name" type="check-box" name="lucky_draw_name" required focus>
                        <option value="">Please select Promotion</option>
                        @foreach($promotions as $promotion)
                        <option value="{{ $promotion->uuid }}" {{ ($promotion->uuid  ? 'selectet' : '') }}>{{ $promotion->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.start_date')}} </label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.end_date')}} </label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                </div>
                    <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
                    @can('export-document-admin')
                    <button id="document_export" class="btn btn-success">{{__('button.product_excel_export')}}</button>
                    @endcan
                    <button id="cash_coupon_issued_export" class="btn btn-warning"> Export Cash Coupon Issued </button>
                {{-- <a class="btn btn-warning" href="{{ route('cash_coupon_issued_export') }}"></a> --}}
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="cash_coupon_issued_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('report.branch')}}</th>
                                <th>{{__('report.date')}}</th>
                                <th>{{__('report.code')}}</th>
                                <th>{{__('report.name')}}</th>
                                <th>{{__('report.customer_code')}}</th>
                                <th>{{__('report.customer_name')}}</th>
                                <th>{{__('report.qty')}}</th>
                                <th>{{__('report.coupon_amount')}}</th>
                                <th>{{__('report.total_amount')}}</th>
                                <th>{{__('report.invoice_no')}}</th>
                                <th>{{__('report.prize_type')}}</th>
                                <th>{{__('report.promotion_name')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                        </tbody>
                    </table>
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
     $('#lucky_draw_name').select2({
        width: '100%',
        height: '200%',
        allowClear: true,
    });
    $(document).ready(function() {
        var table = $('#cash_coupon_issued_list').DataTable({
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
                'url': "/reports/issued_result",
                'type': 'GET',
                'data': function(d) {
                    d.lucky_draw_name = $('#lucky_draw_name').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {
                    data: 'branch_id',
                    name: 'branch_id',
                    orderable: true,
                },
                {
                    data: 'prize_date',
                    name: 'prize_date',
                    orderable: true,
                },
                {
                    data: 'prize_code',
                    name: 'prize_code',
                    orderable: true,
                },
                {
                    data: 'customer_uuid',
                    name: 'customer_uuid',
                    orderable: true,
                },
                {
                    data: 'customer_no',
                    name: 'customer_no',
                    orderable: true,
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                    orderable: true,
                },
                {
                    data: 'prize_qty',
                    name: 'prize_qty',
                    orderable: true,
                },
                {
                    data: 'prize_amount',
                    name: 'prize_amount',
                    orderable: true,
                },
                {
                    data: 'total_amount',
                    name: 'total_amount',
                    orderable: true,
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no',
                    orderable: true,
                },
                {
                    data: 'prize_type',
                    name: 'prize_type',
                    orderable: true,
                },
                {
                    data: 'promotion_uuid',
                    name: 'promotion_uuid',
                    orderable: true,
                },
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        $('#search').on('click', function(e) {
            $('#cash_coupon_issued_list').DataTable().draw(true);
        })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
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
        $('#cash_coupon_issued_export').on('click', function(e) {
            var today = new Date().toISOString().slice(0, 10);
            var lucky_draw_name = $('#lucky_draw_name').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            alart(start_date);
            if(lucky_draw_name){
                var url = `/cash_coupon_issued_export/${lucky_draw_name}/${start_date}/${end_date}`;
                window.location = url;
            }else{
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.please_select_promotion') }}",
                    showCancelButton: true,
                    cancelButtonText: "{{ __('message.cancel') }}",
                    confirmButtonText: "{{ __('message.ok') }}"
                })
            }
        })
    });
</script>
@stop
