@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('lucky_draw.prize_list')}}</h4>
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
                    <label>{{__('lucky_draw.promotion')}} </label>
                    <select id="promotion_uuid" name="promotion_uuid" class="form-control ">
                        <option value="">Select Promotion</option>
                        @foreach($promotions as $promotion)
                        <option value="{{ $promotion->uuid }}"
                        {{ old('promotion_uuid') == $promotion->uuid ? 'selected' : '' }}>
                            {{ $promotion->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.branch')}} </label>
                    <select id="branch_id" name="branch_id" class="form-control ">
                        <option value="">Select Type</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->branch_id }}"
                        {{ old('branch_id') == $branch->branch_id ? 'selected' : '' }}>
                            {{ $branch->branches->branch_name_eng}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="prize_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('lucky_draw.branch')}}</th>
                                <th>{{__('lucky_draw.promotion')}}</th>
                                <th>{{__('lucky_draw.prize_name')}}</th>
                                <th>{{__('lucky_draw.total_qty')}}</th>
                                <th>{{__('lucky_draw.used_qty')}}</th>
                                <th>{{__('lucky_draw.remain_qty')}}</th>
                                <th>{{__('lucky_draw.action')}}</th>
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
    $(document).ready(function() {
        var table = $('#prize_list').DataTable({
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
                'url': "/prize_list",
                'type': 'GET',
                'data': function(d) {
                    d.promotion_uuid = $('#promotion_uuid').val();
                    d.branch_id = $('#branch_id').val();
                }
            },
            columns: [{
                    data: 'branch_name',
                    name: 'branch_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/edit_prize_check/${row.prize_c_c_uuid}/2" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'promotion_name',
                    name: 'promotion_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/edit_prize_check/${row.prize_c_c_uuid}/2" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'prize_name',
                    name: 'prize_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/edit_prize_check/${row.prize_c_c_uuid}/2" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/edit_prize_check/${row.prize_c_c_uuid}/2" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'used_qty',
                    name: 'used_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/edit_prize_check/${row.prize_c_c_uuid}/2" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'remain_qty',
                    name: 'remain_qty',
                    orderable: true,
                    render: function(data, type, row) {
                        return `<a href="/edit_prize_check/${row.prize_c_c_uuid}/2" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        href="/edit_prize_check/${row.prize_c_c_uuid}/2"><i class="ri-pencil-line mr-0"></i></a>
                                </div>`
                    }
                }
            ],
            "columnDefs": [{
                "searchable": false,
            }],
        })

        $('#search').on('click', function(e) {
            $('#prize_list').DataTable().draw(true);
        })
    });
</script>
@stop
