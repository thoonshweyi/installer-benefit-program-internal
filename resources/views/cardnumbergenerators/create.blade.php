@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Create Card Number Generator</h4>
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

                        <form action="{{ route('cardnumbergenerators.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="to_branch_id">To Branch</label>
                                        <select name="to_branch_id" id="to_branch_id" class="form-control @error('to_branch_id') is-invalid @enderror">
                                            <option selected disabled>Choose Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->branch_id }}">{{ $branch->branch_name_eng }}</option>
                                            @endforeach
                                        </select>
                                        @error("to_branch_id")
                                            <span class="text-danger">{{ $message }}<span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label>Quantity<span class="cancel_status">*</sapn> </label>
                                        <input type="number" id="quantity" name="quantity" class="form-control quantity" value="{{old('quantity')}}" placeholder="Enter Quantity to generate"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mr-2 d-block">Random <span class="cancel_status">*
                                                </sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="random" id="radio1" value='1'  @if(old('random',1) == 1) checked @endif>
                                            <label for="radio1">Yes</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="random" id="radio2" value='2' @if(old('random') == 2) checked @endif>
                                            <label for="radio2">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="4" placeholder="Write Something....">{{ old("remark") }}
                                    </textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" id="">Save</button>
                                </div></br>
                            </div>
                        </form>

                    </div>
                </div>
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

    $(document).ready(function(){

    });



</script>
@endsection
