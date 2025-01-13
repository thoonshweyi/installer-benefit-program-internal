@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('lucky_draw_type.edit') }}</h4>
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
                        <div class="card-body">
                            <form action="{{ route('lucky_draw_types.update',$luckyDrawType->id) }}" method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
                            @csrf
                            @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('lucky_draw_type.name')}} * </label>
                                            <input name="name" type="text" class="form-control" value="{{ $luckyDrawType->name}}" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('lucky_draw_type.status')}} </label>
                                            <select id="status" name="status" class="form-control ">
                                                <option value="1" {{ $luckyDrawType->status == old('status') ? 'selected' : '' }}>Active</option>
                                                <option value="2" {{ $luckyDrawType->status == old('status') ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw_type.description') }} </label>
                                            <textarea name="description" class="form-control" rows="3">{{ $luckyDrawType->description}}</textarea>
                                        </div>
                                    </div>
                                  
                                </div>        
                                <button type="submit" class="btn btn-primary mr-2">{{ __('button.update') }}</button>
                               
                                <a class="btn btn-light" href="{{ route('lucky_draw_types.index') }}">{{ __('button.back') }}</a>
                               
                            </form>
                        </div>
                        
                    </div>
                </div>

            </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
    function validateForm() {
        if ($('#document_type').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_type') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if ($('#document_remark').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_document_remark') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
    }
</script>
@endsection