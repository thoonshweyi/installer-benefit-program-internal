@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Sale Amount Checking</h4>
                    </div>
                </div>
            </div>
            {{-- {{ dd(request()->query() ) }} --}}
            <div class="col-lg-12 mb-2">
                <form action="{{ route('saleamountchecks.search') }}" method="GET">
                    <div class="row justify-content-end align-items-end">


                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="" class="form-control form-control-sm" name="primary_phone" placeholder="Enter Primary Phone" value="{{ request()->get('primary_phone') }}"/>
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>

                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light"><i class="fas fa-sync-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
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
            </div>


            <div class="col-lg-12">
                <div class="rounded mb-3 table-container">
                    <table class="table table-hover mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Primary Phone</th>
                                <th>Total Sale Amount</th>
                                <th>Branch</th>
                                <th>Check By</th>
                                <th>Created At</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach ($saleamountchecks as $idx=>$saleamountcheck)
                                <tr style="cursor: pointer" onclick="window.location.href='{{ route('saleamountchecks.show',$saleamountcheck->uuid) }}'" >
                                    <td>{{$idx + $saleamountchecks->firstItem()}}</td>
                                    <td class="text-underline" style="text-underline-offset: 5px;">{{ $saleamountcheck->primary_phone }}</td>
                                    <td>{{ number_format($saleamountcheck->total_sale_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>

                                    <td>{{ $saleamountcheck->branch->branch_name_eng }}</td>
                                    <td>{{ $saleamountcheck->user->name  }}</td>
                                    <td>{{ Carbon\Carbon::parse($saleamountcheck->created_at)->format('M d Y') }}</td>
                                    {{-- <td>
                                        @if($saleamountcheck->isDeleteAuthUser() && $saleamountcheck->allowDelete())
                                            <form action="{{ route('saleamountchecks.destroy',$saleamountcheck->uuid) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a class="text-danger delete-btns"><i class="fas fa-trash"></i></a>
                                            </form>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="myloader">
                        <div class="loader-item"></div>
                        <div class="loader-item"></div>
                        <div class="loader-item"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $saleamountchecks->appends(request()->all())->links('pagination::bootstrap-4') }}
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
        $('.delete-btns').click(function(e){
            e.stopPropagation();
            {{-- console.log('hay'); --}}

            Swal.fire({
                title: "Are you sure you want to delete collection transaction?",
                text: "All the collected will be removed recursively.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $(this).closest('form').submit();
                }
            });
        });

    });
    function changeHandler(input){
        if(input.value){
            input.type = 'date'
        }else{
            input.type = 'text'
            input.blur();
        }
    }

    // Start Clear btn
    document.getElementById("btn-clear").addEventListener("click",function(){
        window.location.href = window.location.href.split("?")[0];
   });
   // End Clear btn
</script>
@stop
