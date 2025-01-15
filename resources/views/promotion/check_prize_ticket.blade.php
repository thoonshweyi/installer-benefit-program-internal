@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Prize Check Ticket</h4>
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
                        <form action="{{ route('store_prize_check',[$promotion_uuid,$promotion_sub_promotion->sub_promotion_uuid]) }}" method="POST" enctype="multipart/form-data" id="invoice_check_amount"
                            >
                            @csrf
                            <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$promotion_uuid}}">
                            <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid" value="{{$promotion_sub_promotion->sub_promotion_uuid}}">
                            <input type="hidden" name="prize_check_type" value="1">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="row">

                                <div class="col-md-4">
                                    <label> Qty <span class="require_field" style="color:red">*</sapn></label>
                                    <input type="text" name="ticket_prize_qty" id="ticket_prize_qty"
                                        class="form-control" data-errors="Please Enter Amount." value="{{isset($prizeTicketCheck) ? $prizeTicketCheck->ticket_prize_qty : ''}}" required>
                                    </select>
                                </div>
                            </div>
                                

                            <div class="row m-2">
                                <button type="submit" class="btn btn-primary mr-2" id="amount_save">Save</button>
                                <a class="btn btn-light" href="{{ route('new_promotion.edit',$promotion_sub_promotion->promotion_uuid) }}"> Back</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
@section('js')
<script>
      $('#branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    function makeDisableForBranchSelectAll(){
        if($("#select_all_branch").is(':checked') ){

            $("#branch_id").val(null).trigger("change");
            $('#branch_id').attr("disabled", true);
        }else{
            $('#branch_id').attr("disabled", false);
        }
    }

    $(document).ready(function() {

    });
</script>
@endsection
