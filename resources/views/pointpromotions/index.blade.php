@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Point Promotion Lists</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <a href="{{route('pointpromos.create')}}" id="" class="btn btn-primary mr-2 mb-2">Create Point Promo</a>
            </div>
            <div class="col-lg-12">
                <form action="{{ route('pointpromos.search') }}" method="GET">
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="" class="form-control" name="name" placeholder="Enter Promotion Name"/>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
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
                    <table class="table mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>{{__('lucky_draw.name')}}</th>
                                <th>Point Per Amount</th>
                                <th>{{__('lucky_draw.start_date')}}</th>
                                <th>{{__('lucky_draw.end_date')}}</th>
                                <th>{{__('lucky_draw.status')}}</th>
                                <th>{{__('lucky_draw.action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($pointpromotions as $idx=>$pointpromotion)
                            <tr>
                                <td>{{$idx + $pointpromotions->firstItem()}}</td>
                                <td>{{ $pointpromotion->name  }}</td>
                                <td>{{ number_format($pointpromotion->pointperamount,0,'.',',') }} <span class="ms-4">MMK</span></td>

                                <td>{{ $pointpromotion->start_date  }}</td>
                                <td>{{ $pointpromotion->end_date  }}</td>
                                <td>
                                    {!! $pointpromotion->status == 1 ? '<span class="normal_status">Active</span>' : ($pointpromotion->status == 2 ? '<span class="reject_status">Inactive</span>' : ($pointpromotion->status == 3 ? '<span class="reject_status">Pending</span>' : '')) !!}
                                </td>
                                <td class="">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('pointpromos.edit',$pointpromotion->uuid) }}" class="mr-2"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('pointpromos.destroy',$pointpromotion->uuid) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a class="text-danger delete-btns"><i class="fas fa-trash"></i></a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $pointpromotions->appends(request()->all())->links('pagination::bootstrap-4') }}
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
        $('.delete-btns').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to remove point promotion?",
                text: "Your point promotion will be permanently deleted.",
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
@stop
