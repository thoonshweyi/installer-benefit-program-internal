@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{$document_type_title}}</h4>
                    </div>
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
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <div class="col-lg-12 d-flex mb-4">
                <input type="hidden" name="document_type" id="document_type" value="{{$document_type}}">
                <input type="hidden" name="detail_type" id="detail_type" value="{{$detail_type}}">
                <div class="form-row col-md-2">
                    <label>{{__('document.document_no')}}</label>
                    <input type="text" class="form-control" id="document_no" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('document.from_date')}}</label>
                    <input type="date" class="form-control" id="document_from_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('document.to_date')}}</label>
                    <input type="date" class="form-control" id="document_to_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('document.branch')}} </label>
                    <select id="document_branch" class="form-control ">
                        <option value="">All Branch</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->branch_id }}" {{ $branch->branch_id == old('document_branch') ? 'selected' : '' }}>
                            {{ $branch->branches->branch_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('document.category')}} </label>
                    <select id="category" class="form-control ">
                        <option value="0">All Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->product_category_id }}" {{ $category->product_category_id == old('category') ? 'selected' : '' }}>
                            {{ $category->remark }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row col-md-2 pt-4">
                    <button id="document_search" class="btn btn-primary document_search">{{__('button.search')}}</button>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info" id="document_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>{{__('document.document_no')}}</th>
                                <th>{{__('document.category')}}</th>
                                <th>{{__('document.status')}}</th>
                                <th>Operation</th>
                                <th>Br. Manager</th>
                                <th>Cat. Head</th>
                                <th>Mer. Manager</th>
                                <th>RG Out</th>
                                <th>CN</th>
                                <th>RG In</th>
                                <th>DB</th>
                                <th>{{__('document.action')}}</th>
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
        var document_type = $('#document_type').val() == 1 ? false : true;
        $('#document_list').DataTable({
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
                'url': "/documents/document_detail_search_result",
                'type': 'GET',
                'data': function(d) {
                    d.document_no = $('#document_no').val();
                    d.document_from_date = $('#document_from_date').val();
                    d.document_to_date = $('#document_to_date').val();
                    d.detail_type = $('#detail_type').val();
                    d.document_branch = $('#document_branch').val();
                    d.document_status = $('#document_status').val();
                    d.category = $('#category').val();
                }
            },
            columns: [{
                    data: 'document_no',
                    name: 'document_no',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'category',
                    name: 'category',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'doc_status',
                    name: 'doc_status',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'operation_updated_datetime',
                    name: 'operation_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'branch_manager_updated_datetime',
                    name: 'branch_manager_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'category_head_updated_datetime',
                    name: 'category_head_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'merchandising_manager_updated_datetime',
                    name: 'merchandising_manager_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'operation_rg_out_updated_datetime',
                    name: 'operation_rg_out_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'accounting_cn_updated_datetime',
                    name: 'accounting_cn_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    }
                },
                {
                    data: 'operation_rg_in_updated_datetime',
                    name: 'operation_rg_in_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    },
                    visible: document_type
                },
                {
                    data: 'accounting_db_updated_datetime',
                    name: 'accounting_db_updated_datetime',
                    orderable: true,
                    render: function(data, type, row) {
                        if (row.document_status == 9 && row.document_type == 1 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if (row.document_status == 11 && row.document_type == 2 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="complete_status">${data}</a>`;
                        }
                        if ((row.document_status == 3 || row.document_status == 5 || row.document_status == 7) && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="cancel_status">${data}</a>`;
                        }
                        if (row.document_status == 12 && row.exchange_to_return == null) {
                            return `<a href="/documents/${row.id}/edit" class="supplier_cancel_status">${data}</a>`;
                        }
                        if (row.exchange_to_return != null) {
                            return `<a href="/documents/${row.id}/edit" class="ex_to_rt_status">${data}</a>`;
                        }
                        return `<a href="/documents/${row.id}/edit" class="normal_status">${data}</a>`;
                    },
                    visible: document_type
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
                        if (data == 'OperationPerson' || data == 'BranchManager') {
                            return `  
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                        href="/documents/${row.id}/edit"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete"
                                        href="/documents/${row.id}/delete" onclick="return confirm('Do You want to Delete?');"><i class="ri-delete-bin-line mr-0"></i></a>
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

    });
</script>
@stop