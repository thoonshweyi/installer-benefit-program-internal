@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add New Customer IP Address</h4>
                        </div>
                    </div>

                    @if ($errors->any())
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
                        <form action="{{ route('customer_ips.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Branch Name :</strong>
                                        <select class="form-control" id="branch_name" name="branch_id" required focus>
                                            <option value="" disabled selected>-- Please select branch --</option>
                                            @foreach($branches as $branch)
                                            <option value="{{$branch->id}}" {{ old('branch_id') == $branch->id ? 'selected': '' }}>{{ $branch->branch_name_eng }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>No:</strong>
                                        <input type="text" name="no" class="form-control" value="{{ old('no') }}" placeholder="No">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        <input type="text" name="ip_address" class="form-control" value="{{ old('ip_address') }}" placeholder="IP Address">
                                    </div>
                                </div>
                                <div class="pull-right col-xs-12 col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a class="btn btn-light" href="{{ route('customer_ips.index') }}"> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
