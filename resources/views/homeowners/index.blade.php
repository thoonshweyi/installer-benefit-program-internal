@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Home Owner List</h4>
                    </div>
                </div>
            </div>
            @can('create-installer-card')
            <div class="col-lg-12">
                <a id="createmodal-btn" href="{{ route('homeowners.create') }}" class="btn btn-primary document_search mr-2 mb-2">Create New Home Owner</a>
            </div>
            @endcan
            <div class="col-lg-12 mb-2">
                <form action="{{ route('homeowners.search') }}" method="GET">
                    <div class="row justify-content-end">

                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="querynrc" id="querynrc" class="form-control form-control-sm" placeholder="Enter NRC Number" value="{{ request()->get('querynrc') }}"/>
                        </div>
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="queryphone" id="queryphone" class="form-control form-control-sm" placeholder="Enter Phone Number" value="{{ request()->get('queryphone') }}"/>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="search-btn" class="btn btn-primary rounded">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light" onclick="window.location.href = window.location.href.split('?')[0];"><i class="fas fa-sync-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <hr/>
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
            {{-- <div class="col-lg-12 d-flex mb-2">
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.name')}} </label>
                    <input type="text" class="form-control" id="lucky_draw_name" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.start_date')}} </label>
                    <input type="date" class="form-control" id="start_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.end_date')}} </label>
                    <input type="date" class="form-control" id="end_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.status')}} </label>
                    <select id="lucky_draw_status" class="form-control ">
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="3">Pending</option>
                        <option value="0">All Status</option>
                    </select>
                </div>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
                @can('export-document-admin')
                <button id="document_export" class="btn btn-success">{{__('button.product_excel_export')}}</button>
                @endcan
            </div> --}}
            <div class="col-lg-12 loader-container">
                <div class="rounded mb-3 table-container">
                    <table class="table mb-0 tbl-server-info" id="">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                @canany(['edit-installer-card', 'delete-installer-card', 'transfer-installer-card'])
                                    <th>Action</th>
                                @endcan

                                <th>By Branch</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>NRC</th>
                                <th>Identification Card</th>
                                <th>By</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach($homeowners as $idx=>$homeowner)
                                <tr>
                                    <td>
                                        {{ $idx + $homeowners->firstItem() }}
                                    </td>
                                        {{-- <td><input type="checkbox" name="singlechecks" class="form-check-input singlechecks" value="{{$homeowner->id}}" /></td> --}}
                                        @canany(['edit-installer-card', 'delete-installer-card', 'transfer-installer-card'])
                                        <td class="">
                                            <div class="d-flex justify-content-start">
                                                @can('edit-installer-card')
                                                    <a href="{{ route('homeowners.edit',$homeowner->uuid) }}" class="mr-2" title="Edit"><i class="fas fa-edit"></i></a>
                                                @endcan

                                                @can('delete-installer-card')
                                                <form action="{{ route('homeowners.destroy',$homeowner->uuid) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="text-danger delete-btns" title="Delete"><i class="fas fa-trash"></i></a>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                        @endcan
                                        <td>{{ $homeowner->branch->branch_name_eng }}</td>
                                        <td>{{ $homeowner->fullname }}</td>
                                        <td>{{ $homeowner->phone }}</td>
                                        <td>{{ $homeowner->nrc }}</td>
                                        <td>{{ $homeowner->identification_card }}</td>
                                        <td>{{ $homeowner->users->name }}</td>
                                        <td>{{  \Carbon\Carbon::parse($homeowner->created_at)->format('d-m-Y') }}</td>


                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $homeowners->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                {{-- {{ dd($homeowners) }} --}}
                <div class="myloader">
                    <div class="loader-item"></div>
                    <div class="loader-item"></div>
                    <div class="loader-item"></div>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>



@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-btns').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to remove an installer card?",
                text: "Home Owner will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    {{-- console.log($(this).closest('form')); --}}
                    $(this).closest('form').submit();
                }
              });

        });





    });
</script>
@endsection
