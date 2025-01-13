


@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="pull-left">
                    <h4>{{ __('customer_ip.edit_customer_ip')}}</h4>
                </div>
            </div>

            @if (count($errors) > 0)
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
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
            <div class="card-body">
                <form action="{{ route('customer_ips.update',$customer_ip->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><strong>Branch Name:</strong></label>
                                <select class="form-control" id="branch_name" name="branch_id" required focus>
                                    <option value="" disabled selected>-- Please select branch --</option>
                                    @foreach($branches as $branch)
                                    <option value="{{$branch->id}}" {{ ($branch->id == $customer_ip->branch_id  ? 'selected="selected"' : '') }}>{{ $branch->branch_name_eng }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><strong>Name:</strong></label>
                                <input type="text" name="no" value="{{ $customer_ip->no }}" class="form-control" placeholder="No">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><strong>IP Address:</strong></label>
                                <input type="text" name="ip_address" value="{{ $customer_ip->ip_address }}" class="form-control" placeholder="IP Address">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('customer_ips.index') }}"> Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('js')
<script type="text/javascript">
    $('#branch_name').select2({
        width: '100%',
        allowClear: true,
    });
    $(document).ready(function() {
        var url = window.location.origin;
        $('#branch_name').click(function() {
            var id = $(this).val();
            $('#dept_id').find('option').remove();
            $.ajax({
                // url: url + '/dept/'+id,
                url: url + '/users/branch/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response.data != null) {
                        len = response.data.length;
                    }
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response.data[i].id;
                            var name = response.data[i].name;
                            var option = "<option value='" + id + "'>" + name + "</option>";
                            $("#dept_id").append(option);
                        }
                    }
                }
            })
        });
    });
</script>
@endsection
