@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Prize Check Fix Amount</h4>
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
                            <input type="hidden" name="prize_check_type" value="3">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <label> Name <span class="require_field" style="color:red">*</sapn></label>
                                    {{-- <input type="text" name="fixed_prize_name" id="fixed_prize_name"
                                        class="form-control" data-errors="Please Enter Amount." value="{{isset($fixedPrizeAmountCheck) ? $fixedPrizeAmountCheck->fixed_prize_name : ''}}" required> --}}
                                    <select name="fixed_prize_type" id="fixed_prize_type"
                                    class="selectpicker form-control" data-style="py-0">
                                        <option value="">Select Name</option>
                                        @if(isset($fixedPrizeAmountCheck))
                                            <option value='1' {{ $fixedPrizeAmountCheck->fixed_prize_type == 1 ? 'selected' :'' }}>Gold Ring
                                            </option>
                                            <option value='2' {{ $fixedPrizeAmountCheck->fixed_prize_type == 2 ? 'selected' :'' }}>Gold Coin
                                            </option>
                                            <option value='3' {{ $fixedPrizeAmountCheck->fixed_prize_type == 3 ? 'selected' :'' }}>Other
                                            </option>
                                        @else
                                            <option value='1' {{ old('fixed_prize_type') == 1 ? 'selected' :''}}>Gold Ring
                                            </option>
                                            <option value='2' {{ old('fixed_prize_type') == 2 ? 'selected' :''}}>Gold Coin
                                            </option>
                                            <option value='3' {{ old('fixed_prize_type') == 3 ? 'selected' :''}}>Other
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label> GP Code <span class="require_field" style="color:red">*</sapn></label>
                                    <input type="text" name="fixed_prize_gp_code" id="fixed_prize_gp_code"
                                        class="form-control" data-errors="Please Enter Amount." value="{{ isset($fixedPrizeAmountCheck) ? $fixedPrizeAmountCheck->fixed_prize_gp_code : ''}}" required>
                                </div>
                                <div class="col-md-2">
                                    <label> Name <span class="require_field" style="color:red">*</sapn></label>
                                    <input type="text" name="fixed_prize_name" id="fixed_prize_name"
                                        class="form-control" data-errors="Please Enter Amount." value="{{ isset($fixedPrizeAmountCheck) ? $fixedPrizeAmountCheck->fixed_prize_name : ''}}" required>
                                </div>
                                <div class="col-md-2">
                                    <label> Ticket Amount <span class="require_field" style="color:red">*</sapn></label>
                                    <input type="number" name="fixed_prize_ticket_amount" id="fixed_prize_ticket_amount"
                                        class="form-control" data-errors="Please Enter Amount." value="{{ isset($fixedPrizeAmountCheck) ? $fixedPrizeAmountCheck->fixed_prize_ticket_amount : ''}}" required>
                                </div>
                                <div class="col-md-2">
                                    <label> Qty <span class="require_field" style="color:red">*</sapn></label>
                                    <input type="number" name="fixed_prize_qty" id="fixed_prize_qty"
                                        class="form-control" data-errors="Please Enter Amount." value="{{ isset($fixedPrizeAmountCheck) ? $fixedPrizeAmountCheck->fixed_prize_qty : ''}}" required>
                                </div>
                                <div class="col-md-2">
                                    <label> Image <span class="require_field" style="color:red">*</sapn></label>
                                    <input type="file" name="fixed_prize_ticket_image" id="fixed_prize_ticket_image" class="form-control"
                                        placeholder="1" data-errors="Please Enter Unit." required>
                                </div>
                            </div>
                            <div class="row m-2">
                                <button type="submit" class="btn btn-primary mr-2" id="amount_save">Save</button>
                                <a class="btn btn-light" class="btn btn-primary mr-2" href="{{ route('new_promotion.edit',$promotion_sub_promotion->promotion_uuid) }}"> Back</a>
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
