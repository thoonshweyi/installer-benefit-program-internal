@extends('layouts.app')

@section('content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lucky_draw.edit') }}</h4>
                        </div>
                    </div>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <form action="{{ route('new_promotion.update',$lucky_draw->uuid) }}" method="POST"
                            enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('lucky_draw.name')}} <span class="cancel_status">*</sapn> </label>
                                        <input name="name" id="name" type="text" class="form-control"
                                            value="{{$lucky_draw->name}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Promotion Type <span class="cancel_status">*</sapn> </label>
                                        <select id="lucky_draw_type" name="lucky_draw_type" class="form-control ">
                                            <option value="">Select Type</option>
                                            @foreach($lucky_draw_types as $lucky_draw_type)
                                            <option value="{{ $lucky_draw_type->uuid }}"
                                            {{ $lucky_draw->lucky_draw_type_uuid == $lucky_draw_type->uuid ? 'selected' : '' }}>
                                                {{ $lucky_draw_type->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{__('lucky_draw.status')}} <span class="cancel_status">*</sapn> </label>
                                        <select id="status" name="status" class="form-control ">
                                            @can('approve-promotion')
                                            <option value="1" {{$lucky_draw->status == 1 ? 'selected': '' }}>Active
                                            </option>
                                            <option value="2" {{$lucky_draw->status == 2 ? 'selected': '' }}>Inactive
                                            </option>
                                            <option value="3" {{$lucky_draw->status == 3 ? 'selected': '' }}>Pending
                                            </option>
                                            @endcan
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                                </sapn> </label>
                                        <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                            id="select_all_branch" {{$luckydraw_branches ? '' : 'checked' }}>
                                        <label for="select_all_branch">Select All Branch</label>

                                        <select name="branch_id[]" id="branch_id" class="form-control " multiple
                                            required>
                                            @foreach($branches as $branch)
                                            <option value="{{ $branch->branch_id }}"
                                                {{ in_array($branch->branch_id, $luckydraw_branches ?: []) ? 'selected' : '' }}>
                                                {{ $branch->branch_name_eng}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.category')}} <span class="cancel_status">*
                                                </sapn> </label>
                                        <input type="checkbox" class="checkbox-input" name="select_all_category"
                                            id="select_all_category" {{$luckydraw_categories ? '' : 'checked' }}>
                                        <label for="select_all_branch">Select All Category</label>

                                        <select name="category_id[]" id="category_id" class="form-control " multiple
                                            required>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $luckydraw_categories ?: []) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.brand')}} <span class="cancel_status">*
                                                </sapn> </label>
                                        <input type="checkbox" class="checkbox-input" name="select_all_brand"
                                            id="select_all_brand" {{ $luckydraw_brands ? '' : 'checked' }}>
                                        <label for="select_all_branch">Select All Brand</label>

                                        <select name="brand_id[]" id="brand_id" class="form-control " multiple required>
                                            @foreach($brands as $brand)
                                            <option value="{{ $brand->product_brand_id }}"
                                                {{ in_array($brand->product_brand_id, $luckydraw_brands ?: []) ? 'selected' : '' }}>
                                                {{ $brand->product_brand_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lucky_draw.start_date') }} <span class="cancel_status">*</sapn>
                                        </label>
                                        <input name="start_date" type="date" class="form-control" id="documentDate"
                                            value="{{$lucky_draw->start_date}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lucky_draw.end_date') }} <span class="cancel_status">*</sapn>
                                        </label>
                                        <input name="end_date" type="date" class="form-control" id="documentDate"
                                            value="{{$lucky_draw->end_date}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="mr-2">{{__('lucky_draw.discon_status')}} <span
                                                class="cancel_status">*</sapn> </label>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="discon_status" id="radio1" value='1'
                                                @if($lucky_draw->discon_status == 1) checked @endif>
                                            <label for="radio1">Include</label>
                                        </div>
                                        <div class="radio d-inline-block mr-2">
                                            <input type="radio" name="discon_status" id="radio2" value='2'
                                                @if($lucky_draw->discon_status == 2) checked @endif >
                                            <label for="radio2">Exclude</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mr-2">{{ __('button.update') }}</button>
                            <a href="#" class="btn btn-secondary mr-2"
                                id="add_sub_promoiton">{{__('button.add_sub_promotion')}}</a>
                            <a class="btn btn-light" href="{{ route('lucky_draws.index') }}">{{ __('button.back') }}</a>
                        </form>
                    </div>
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-3">Sub Promotion List</h4>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="sub_promotion_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>{{ __('price.price_name') }}</th>
                                            <th>{{ __('price.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Page end  -->
        <div class="modal fade add_sub_promoiton" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="price_modal_title">Add Sub Promotion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="price_form" action="{{ route('sub_promotion.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="promotion_uuid" id="promotion_uuid" value="{{$lucky_draw->uuid}}" />
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name<span class="cancel_status">*</sapn> </label>
                                        <select id="sub_promotion_name" name="sub_promotion_name" class="form-control">
                                            <option value="">Select Sub Promton</option>
                                            @foreach($sub_promotions as $sub_promotion)
                                                <option value="{{$sub_promotion->name}}">{{$sub_promotion->name}}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="price_modal_submit_button">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Check Invoice Process</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="padding: 20px;">
                <form action="{{ route('amount_check.store') }}" method="POST" enctype="multipart/form-data" id="invoice_check_amount"
                    >
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" name="invoice_check_status" id="check_invoice1" value="1"
                                checked="">
                            <label>By Amount</label>
                            <input type="radio" name="invoice_check_status" id="check_invoice2" value="2">
                            <label>By Product</label>
                        </div>
                    </div>
                    <div class="col-md-12" id="invoice_check_status">
                        <div class="form-group">
                            <label>Amount<span id="w_no" class="cancel_status">*</sapn></label>
                            <input type="number" id="amount" name="amount" class="form-control"
                                value="{{ isset($amount_checks->amount) ? $amount_checks->amount : '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-12" id="branch_amount">
                        <div class="form-group" style="margin: 0;">
                            <input type="hidden" name="sub_promotion_uuid" id="a_sub_promotion_uuid" value="">
                            <input type="hidden" name="promotion_uuid" id="a_promotion_uuid" value="">
                            <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                    </sapn>
                            </label>
                            <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                id="a_select_all_branch" {{$luckydraw_branches ? '' : 'checked' }}>

                            <label for="select_all_branch">Select All Branch</label>
                            <select name="branch_id[]" id="a_branch_id" class="form-control " multiple>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->branch_id }}">
                                    {{ $branch->branch_name_eng}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2" style="text_align:center;">
                        <button type="button" class="btn btn-primary" id="amount_save" onClick="return InvoiceCheckAmountValidateForm()">Process</button>
                    </div>
                </form>
                <div class="col-md-12" id="add-list">
                    <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                        <div class="mt-2">
                            <h4>Product List</h4>
                        </div>
                        <div class="mt-2">
                            <button id="add" type="button" class="btn btn-primary mr-2" onClick="return InvoiceCheckProductValidateForm()">Add Product</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive rounded mb-126">
                            <table class="table mb-0 tbl-server-info" id="check_product_list">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade edit_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Check Prize Process </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>
                <div>
                    <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid"
                    value="">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" name="prize_ticket_check_status" id="prize1" value="1" checked="">
                            <label>By Ticket</label>
                            <input type="radio" name="prize_ticket_check_status" id="prize2" value="2">
                            <label>By Grab the Chance</label>
                            <input type="radio" name="prize_ticket_check_status" id="prize3" value="3">
                            <label>By Fix Prize</label>
                        </div>
                    </div>
                    <form action="{{ route('prize_ticket_check.store') }}" method="POST" enctype="multipart/form-data" id="prize_check_ticket">
                        @csrf
                        <input type="hidden" name="sub_promotion_uuid" id="p_sub_promotion_uuid" value="">
                        <input type="hidden" name="promotion_uuid" id="p_promotion_uuid" value="">
                        <input type="hidden" name="prize_ticket_check_status" value="1">
                        <div class="col-md-12" id="by-ticket">
                            <div class="form-group">
                                <label> Ticket Image Upload<span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="file" name="ticket_prize_image" id="ticket_prize_image"
                                    class="form-control" data-errors="Please Enter Ticket Image."
                                    value="{{ isset($prize_ticket_check->ticket_prize_image) ? $prize_ticket_check->ticket_prize_image : '' }} "
                                    required>
                                </select>
                                <div class="form-group">
                                    <img src="" id="prize_ticket_image" class="img-fluid rounded-normal mt-3" alt="logo">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="ticket_amount">
                            <div class="form-group">
                                <label> Promotion Amount <span class="require_field" style="color:red">*</sapn></label>
                                <input type="number" name="ticket_prize_amount" id="ticket_prize_amount"
                                    class="form-control" data-errors="Please Enter Amount."
                                    value="{{ isset($prize_ticket_check->ticket_prize_amount) ? $prize_ticket_check->ticket_prize_amount : ''  }}"
                                    required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button  id="pirze_ticket_save" type="button" class="btn btn-primary" onClick="return PrizeCheckTicketValidateForm()">Save</button>
                        </div>
                    </form>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-danger mr-2" id="cash-coupon" name="coupon_type" onClick="return PrizeCheckGrab1ValidateForm()"> Add Cash Coupon
                            </button>
                            <button class="btn btn-warning mr-2" id="add-present-form" name="coupon_type" onClick="return PrizeCheckGrab2ValidateForm()"> Add
                                Present
                            </button>
                            <button class="btn btn-success mr-2" id="add-winning" name="coupon_type"> Add Winning
                                Chance %
                            </button>
                        </div>
                    </div>
                    <form id="fixed_prize_amount" action="{{ route('fixed_prize_amount_check.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="sub_promotion_uuid" id="sub_promotion_uuid" value="">
                        <div class="col-md-12" id="by-present">
                            <div class="form-group">
                                <label>Image Upload <span class="require_field" style="color:red">*</sapn></label>
                                <input type="file" name="fixed_prize_ticket_image" id="fixed_prize_ticket_image"
                                    class="form-control" placeholder="1" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="fixed_amount">
                            <div class="form-group">
                                <label> Ticket Amount <span class="require_field" style="color:red">*</sapn></label>
                                <input type="text" name="fixed_prize_ticket_amount" id="fixed_prize_ticket_amount"
                                    class="form-control" data-errors="Please Enter Amount." required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="fixed_prize_save" style="margin-bottom:15px;">
                            <button type="submit" class="btn btn-primary"
                                id="fixed_prize_amount_check_save">Save</button>
                        </div>
                    </form>
                    <!-- ///By Customer/// -->
                    <div class="col-lg-12 row" id="add-cash-coupon">
                        <div class="col-md-6">
                            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-3">Cash Coupon List</h4>
                                </div>
                            </div>
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="cash_coupon_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>Branch</th>
                                            <th>Name</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-3">Present List</h4>
                                </div>
                            </div>
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="present_result">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>Branch</th>
                                            <th>Name</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 row" id="winning-chance">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-3">Winning Chance List</h4>
                                </div>
                            </div>
                            <div class="table-responsive rounded mb-3">
                                <table class="table mb-0 tbl-server-info" id="winning_chance_list">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>Branch</th>
                                            <th>Minimum Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade by-ticket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Image Upload<span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="file" name="product_unit_name" id="product_unit_name" class="form-control"
                                    placeholder="1" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="product_modal_submit_button">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade cash-coupon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Cash Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form-group cart-jdk">
                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input type="hidden" name="type" id="type" value="{{ '1' }}">
                <input type="hidden" name="promotion_uuid" id="cpcc_promotion_uuid" value="">
                <input type="hidden" name="sub_promotion_uuid" id="cpcc_sub_promotion_uuid" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                        </sapn>
                                </label>
                                <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                    id="cpcc_select_all_branch" {{old("select_all_branch") ? 'checked' : ''}}>
                                <label for="select_all_branch">Select All Branch</label>

                                <select name="branch_id" id="cpcc_branch_id" class="form-control" multiple>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->branch_id }}"
                                        {{ in_array($branch->branch_id, old("branch_id") ?: []) ? 'selected' : '' }}>
                                        {{ $branch->branch_name_eng}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Name <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="cash_coupon_name" id="cash_coupon_name" class="form-control"
                                    data-errors="Please Enter Code.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Qty <span class="require_field" style="color:red">*</sapn></label>
                                <input type="text" name="cash_coupon_qty" id="cash_coupon_qty" class="form-control"
                                    placeholder="" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Image <span class="require_field" style="color:red">*</sapn></label>
                                <input type="file" name="ticket_image" id="ticket_image" class="form-control"
                                    placeholder="1" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="cash_coupon_save" name="reset">Save</button>
                </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group cart-jdk">
                    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                        </sapn>
                                </label>
                                <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                    id="p_select_all_branch" {{$luckydraw_branches ? '' : 'checked' }}>
                                <label for="select_all_branch">Select All Branch</label>
                                <select name="branch_id[]" id="p_branch_id" class="form-control " multiple>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->branch_id }}"
                                        {{ in_array($branch->branch_id, old("branch_id") ?: []) ? 'selected' : '' }}>
                                        {{ $branch->branch_name_eng}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Product Name <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="product_name" id="product_name" class="form-control"
                                    data-errors="Please Enter Code." placeholder="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Product Code <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="product_code" id="product_code" class="form-control"
                                    data-errors="Please Enter Code." placeholder="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Qty<span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="product_qty" id="product_qty" class="form-control"
                                    placeholder="" data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="check_product_save">Save</button>
                </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade sub_category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-ml">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Sub Promotion Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Name<span class="cancel_status">*</sapn> </label>
                        <input name="name" id="name" type="text" class="form-control" value="{{old('name')}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="product_modal_submit_button">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade add-present-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Present</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form-group cart-jdk">
                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input type="hidden" name="type" id="type" value="2">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                        </sapn>
                                </label>
                                <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                    id="cp_select_all_branch" {{old("select_all_branch") ? 'checked' : ''}}>
                                <label for="select_all_branch">Select All Branch</label>
                                <select name="branch_id[]" id="cp_branch_id" class="form-control " multiple>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->branch_id }}"
                                        {{ in_array($branch->branch_id, old("branch_id") ?: []) ? 'selected' : '' }}>
                                        {{ $branch->branch_name_eng}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Product Name <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="present_name" id="present_name" class="form-control"
                                    data-errors="Please Enter Code.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Qty <span class="require_field" style="color:red">*</sapn></label>
                                <input type="text" name="present_qty" id="present_qty" class="form-control"
                                    data-errors="Please Enter Unit." required>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="present_save">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade add-winning" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Winning Chance %</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form-group cart-jdk">
                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Minimum Amount <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="minimum_amount" id="minimum_amount" class="form-control"
                                    data-errors="Please Enter Code.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="winning_chance_save">Save</button>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade add-winning1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title">Add Winning Chance %</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form-group cart-jdk">
                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Minimum Amount <span class="require_field" style="color:red">*</sapn>
                                </label>
                                <input type="text" name="minimum_amount" id="minimum_amount" class="form-control"
                                    data-errors="Please Enter Code.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="winning_chance_save">Save</button>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade winning_chance_edit_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tool_modal_title"> Winning Chance For <span id="b_name"> </span> <span
                        id="c_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('winning_chance_percentage_store') }}" method="POST" name="winning_chance_form"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row" style="margin-left:122px;">
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Winning Chance (%)</th>
                            </tr>
                            <tr>
                                <tbody id="detail" name="detail"></tbody>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="margin-left:122px; margin-bottom:32px"
                        id="winning_chance_percentage_save">Save</button>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js')
<script type="text/javascript">
    $('#cash_coupon_branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    $('#branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    $('#a_branch_id').select2({
        width: '100%',
        allowClear: true,
    });

    function makeDisableForABranchSelectAll(){
        if($("#a_select_all_branch").is(':checked') ){
            $("#a_branch_id").val(null).trigger("change");
            $('#a_branch_id').attr("disabled", true);
        }else{
            $('#a_branch_id').attr("disabled", false);
        }
    }
    makeDisableForABranchSelectAll();
    $(document).on("click", "#a_select_all_branch", function() {
        makeDisableForABranchSelectAll();
    })

    $('#p_branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    function makeDisableForPBranchSelectAll(){
        if($("#p_select_all_branch").is(':checked') ){
            $("#p_branch_id").val(null).trigger("change");
            $('#p_branch_id').attr("disabled", true);
        }else{
            $('#p_branch_id').attr("disabled", false);
        }
    }
    makeDisableForPBranchSelectAll();
    $(document).on("click", "#p_select_all_branch", function() {
        makeDisableForPBranchSelectAll();
    })

    $('#cp_branch_id').select2({
        width: '100%',
        allowClear: true,
    });

    function makeDisableForCPBranchSelectAll(){
        if($("#cp_select_all_branch").is(':checked') ){
            $("#cp_branch_id").val(null).trigger("change");
            $('#cp_branch_id').attr("disabled", true);
        }else{
            $('#cp_branch_id').attr("disabled", false);
        }
    }
    makeDisableForCPBranchSelectAll();

    $(document).on("click", "#cp_select_all_branch", function() {
        makeDisableForCPBranchSelectAll();
    })

    //for check prize for cash coupon
    $('#cpcc_branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    function makeDisableForPBranchSelectAll(){
        if($("#cpcc_select_all_branch").is(':checked') ){
            $("#cpcc_branch_id").val(null).trigger("change");
            $('#cpcc_branch_id').attr("disabled", true);
        }else{
            $('#cpcc_branch_id').attr("disabled", false);
        }
    }
    makeDisableForPBranchSelectAll();
    $(document).on("click", "#cpcc_select_all_branch", function() {
        makeDisableForPBranchSelectAll();
    })

    $('#category_id').select2({
        width: '100%',
        allowClear: true,
    });
    $('#brand_id').select2({
        width: '100%',
        allowClear: true,
    });

    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        }
    });
    $(document).on("click", "#edit", function() {
        $('#check_product_list').DataTable().draw(true);
        $('.edit').modal('show');
    });
    $(document).on("click", "#edit_form", function() {
        $('.edit_form').modal('show');
    });

    $(document).on("click", "#sub_category", function() {
        $('.sub_category').modal('show');
    });

    $(document).on("click", "#winning_chance_percentage_save", function(event) {

        var ele = document.winning_chance_form.getElementsByTagName('input');

        var value = 0;
        // LOOP THROUGH EACH ELEMENT.
        for (i = 0; i < ele.length; i++) {

            // CHECK THE ELEMENT TYPE.
            if (ele[i].type == 'text') {
                value += parseInt(ele[i].value);
            }
        }
        if (value != 100) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_to_be_100') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
            event.preventDefault();
        }
    });

    $('#check_invoice1').on('change', function(e) {
        check_invoice1();
    })
    function check_invoice1(){
        $("#invoice_check_status").show();
        $("#amount_save").show();
        $("#branch_amount").show();
        $("#add").hide();
        $("#add-list").hide();
        document.getElementById("invoice_check_status").required = true;
    }

    $('#check_invoice2').on('change', function(e) {
        check_invoice2();
    })
    function check_invoice2(){
        $("#invoice_check_status").hide();
        $("#amount_save").hide();
        $("#branch_amount").hide();
        $("#add").show();
        $("#add-list").show();
        document.getElementById("invoice_check_status").required = false;
    }

    $('#prize1').on('change', function(e) {
        prize1();
    })
    function prize1(){
        $("#by-ticket").show();
        $("#pirze_ticket_save").show();
        $("#ticket_amount").show();
        $("#cash-coupon").hide();
        $("#add-cash-coupon").hide();
        $("#by-fix-prize").hide();
        $("#by-present").hide();
        $("#add-present").hide();
        $("#add-present-list").hide();
        $("#add-present-form").hide();
        $("#add-winning").hide();
        $("#winning-chance").hide();
        $("#amount_save").hide();
        $("#fixed_prize_save").hide();
        $('#fixed_amount').hide();
        document.getElementById("invoice_check_status").required = true;
    }

    $('#prize2').on('change', function(e) {
        prize2();
    })
    function prize2(){
        $("#by-ticket").hide();
        $("#pirze_ticket_save").hide();
        $("#ticket_amount").hide();
        $("#cash-coupon").show();
        $("#add-cash-coupon").show();
        $("#by-present").hide();
        $("#by-fix-prize").hide();
        $("#add-present").show();
        $("#add-present-list").show();
        $("#add-present-form").show();
        $("#add-winning").show();
        $("#winning-chance").show();
        $("#amount_save").hide();
        $("#fixed_prize_save").hide();
        $('#fixed_amount').hide();
        document.getElementById("invoice_check_status").required = false;
    }

    $('#prize3').on('change', function(e) {
        prize3();
    })
    function prize3(){
        $("#by-present").show();
        $("#pirze_ticket_save").hide();
        $("#ticket_amount").hide();
        $("#by-ticket").hide();
        $("#cash-coupon").hide();
        $("#add-cash-coupon").hide();
        $("#by-fix-prize").show();
        $("#add-present").hide();
        $("#add-present-list").hide();
        $("#add-present-form").hide();
        $("#add-winning").hide();
        $("#winning-chance").hide();
        $("#amount_save").hide();
        $("#fixed_prize_save").show();
        $('#fixed_amount').show();
        document.getElementById("invoice_check_status").required = false;
    }
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
    $(document).ready(function() {
        $("#add").hide();
        $("#add-list").hide();
        $("#cash-coupon").hide();
        $("#add-cash-coupon").hide()
        $("#by-fix-prize").hide();
        $("#add-present").hide();
        $("#by-present").hide();
        $("#add-present-list").hide();
        $("#add-present-form").hide();
        $("#add-winning").hide();
        $("#winning-chance").hide();
        $("#fixed_prize_save").hide();
        $('#fixed_amount').hide();


        //////cash coupon save//////
        $('#cash_coupon_save').on('click', function() {
            var name = $('#cash_coupon_name').val();
            var qty = $('#cash_coupon_qty').val();
            var promotion_uuid = $('#cpcc_promotion_uuid').val();
            var sub_promotion_uuid = $('#cpcc_sub_promotion_uuid').val();

            var checked = document.querySelectorAll('#branch_id :checked');
            var branch = [...checked].map(option => option.value);
            var type = $('#type').val();
            if (name != "") {
                $.ajax({
                    url: "/cash_coupon",
                    type: "POST",
                    data: {
                        _token: $("#csrf").val(),
                        type: 1,
                        name: name,
                        qty: qty,
                        promotion_uuid: promotion_uuid,
                        sub_promotion_uuid: sub_promotion_uuid,
                        branch_id: branch,
                    },
                    cache: false,
                    success: function(responseOutput) {
                        var responseOutput = JSON.parse(responseOutput);

                        if (responseOutput.statusCode == 200) {
                            $('#cash_coupon_list').DataTable().draw(true);
                            $('.cash-coupon').modal('hide');
                        } else if (responseOutput.statusCode == 201) {
                            alert("Error occured !");
                        }

                    }
                });

            } else {
                alert('Please fill all the field !');
            }
        });
        //////product save//////
        $('#check_product_save').on('click', function() {
            var product_name = $('#product_name').val();
            var product_code = $('#product_code').val();
            var product_qty = $('#product_qty').val();
            var promotion_uuid = $('#promotion_uuid').val();
            var sub_promotion_uuid = $('#a_sub_promotion_uuid').val();

            var checked = document.querySelectorAll('#branch_id :checked');
            var branch = [...checked].map(option => option.value);
            if (product_name != "") {
                $.ajax({
                    url: "/product_check",
                    type: "POST",
                    data: {
                        _token: $("#csrf").val(),
                        product_name: product_name,
                        product_code: product_code,
                        product_qty: product_qty,
                        promotion_uuid: promotion_uuid,
                        sub_promotion_uuid: sub_promotion_uuid,
                        branch_id: branch,
                        invoice_check_status: 2,
                    },
                    cache: false,
                    success: function(responseOutput) {
                        var responseOutput = JSON.parse(responseOutput);
                        if (responseOutput.statusCode == 200) {
                            $('#check_product_list').DataTable().draw(true);
                            $('.add').modal('hide');
                        } else if (responseOutput.statusCode == 201) {
                            alert("Error occured !");
                        }

                    }
                });

            } else {
                alert('Please fill all the field !');
            }
        });
        ////present save////
        $('#present_save').on('click', function() {

            var name = $('#present_name').val();
            var qty = $('#present_qty').val();
            var promotion_uuid = $('#promotion_uuid').val();
            var sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
            var type = $('#type').val();
            var checked = document.querySelectorAll('#branch_id :checked');
            var branch = [...checked].map(option => option.value);
            if (present_name != "") {
                $.ajax({
                    url: "/cash_coupon",
                    type: "POST",
                    data: {
                        _token: $("#csrf").val(),
                        type: 2,
                        name: name,
                        qty: qty,
                        promotion_uuid: promotion_uuid,
                        sub_promotion_uuid: sub_promotion_uuid,
                        branch_id: branch,
                    },
                    cache: false,
                    success: function(responseOutput) {
                        var responseOutput = JSON.parse(responseOutput);
                        if (responseOutput.statusCode == 200) {
                            $('#present_result').DataTable().draw(true);
                            $('.add-present-form').modal('hide');
                            $('.add-present-form').modal('clear');
                        } else if (responseOutput.statusCode == 201) {
                            alert("Error occured !");
                        }

                    }
                });

            } else {
                alert('Please fill all the field !');
            }
        });
        ////winning chance save////
        $('#winning_chance_save').on('click', function() {
            var minimum_amount = $('#minimum_amount').val();
            var promotion_uuid = $('#promotion_uuid').val();
            var sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
            var type = $('#type').val();

            if (minimum_amount != "") {
                $.ajax({
                    url: "/cash_coupon_winning_chance",
                    type: "POST",
                    data: {
                        _token: $("#csrf").val(),
                        type: 2,
                        minimum_amount: minimum_amount,
                        promotion_uuid: promotion_uuid,
                        sub_promotion_uuid: sub_promotion_uuid,
                    },
                    cache: false,
                    success: function(responseOutput) {
                        var responseOutput = JSON.parse(responseOutput);
                        if (responseOutput.statusCode == 200) {
                            $('#winning_chance_list').DataTable().draw(true);
                            $('.add-winning').modal('hide');
                            $('.add-present-form').modal('clear');
                        } else if (responseOutput.statusCode == 201) {
                            alert("Error occured !");
                        }

                    }
                });
            } else {
                alert('Please fill all the field !');
            }
        });

        function makeDisableForBranchSelectAll() {
            if ($("#select_all_branch").is(':checked')) {
                $("#branch_id").val(null).trigger("change");
                $('#branch_id').attr("disabled", true);
            } else {
                $('#branch_id').attr("disabled", false);
            }
        }
        makeDisableForBranchSelectAll();

        function makeDisableForCategorySelectAll() {
            if ($("#select_all_category").is(':checked')) {
                $("#category_id").val(null).trigger("change");
                $('#category_id').attr("disabled", true);
            } else {
                $('#category_id').attr("disabled", false);
            }
        }
        makeDisableForCategorySelectAll();

        function makeDisableForBrandSelectAll() {
            if ($("#select_all_brand").is(':checked')) {
                $("#brand_id").val(null).trigger("change");
                $('#brand_id').attr("disabled", true);
            } else {
                $('#brand_id').attr("disabled", false);
            }
        }
        makeDisableForBrandSelectAll();
        $(document).on("click", "#select_all_branch", function() {
            makeDisableForBranchSelectAll();
        })
        $(document).on("click", "#select_all_category", function() {
            makeDisableForCategorySelectAll();
        })
        $(document).on("click", "#select_all_brand", function() {
            makeDisableForBrandSelectAll();
        })
    })

    $(document).on("click", "#add_sub_promoiton", function() {
        $('#price_order').val('');
        $('#price_name').val('');
        $('#price_amount').val('');
        $('#price_quantity').val('');
        $('#price_description').val('');
        $('#price_modal_submit_button').text('Save');
        $('.add_sub_promoiton').modal('show');
    });
    $(document).on("click", "#price_modal_submit_button", function() {
        $('#price_form').submit();
    });


    //////cash coupon by branch/////
    $(document).on('change', "#cash_coupon_uuid", function() {
        $("#branch_name option").remove();
        var name = $('#cash_coupon_uuid').val();
        var token = $("meta[name='csrf-token']").attr("content");
        if (name) {
            $.ajax({
                url: '../../cash_coupon_branch_by_name',
                type: 'get',
                data: {
                    "_token": token,
                    "uuid": name,

                },
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {
                    jQuery("#loading").hide();
                },
                success: function(response) {

                    for (var i = 0; i < response.length; i++) {
                        $("#cash_coupon_branch_id").append('<option value=' + response[i].branch_id +
                            '>' +
                            response[i].branch_name_eng + '</option>');
                    }
                    $('#team_no').val('');
                    $('#team_no').val(response.team_no);
                },
                error: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: "{{ __('message.warning') }}",
                        text: `{{ __('message.validation_error') }}`,
                        confirmButtonText: "{{ __('message.ok') }}",
                    });
                }
            });

        }
    })
    var sub_promotion_table = $('#sub_promotion_list').DataTable({
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
            'url': "/search_sub_promotion_result",
            'type': 'GET',
            'data': function(d) {
                d.promotion_uuid = $('#promotion_uuid').val();
                d.name = $('#sub_promotion_name').val();

            }
        },
        columns: [{
                data: 'name',
                name: 'name',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center list-action">
                            <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="Check Invoice" data-original-title="Edit"
                                id="edit" href="#"" data-sub_promotion_uuid="${row.sub_promotion_uuid}"><i class="ri-checkbox-line"></i></a>
                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Check Prize" data-original-title="Edit"
                                id="edit_form" href="#"" data-sub_promotion_uuid="${row.sub_promotion_uuid}"><i class="ri-checkbox-line mr-0"></i></a>
                                <a class="badge bg-warning mr-2" data-sub_promotion_uuid="${row.sub_promotion_uuid}" title="Delete" id="sub_promotion_delete"
                                    href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                        </div>`
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
        }],
    })
    /////Check Product Listing//////
    var check_product_table = $('#check_product_list').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "autoWidth": true,
        "responsive": true,
        "pageLength": 10,
        "scrollY": "450px",
        "iDeferLoading": 0,
        "scrollCollapse": true,
        'ajax': {
            'url': "/product_result",
            'type': 'GET',
            'data': function(d) {
                d.sub_promotion_uuid = $('#sub_promotion_uuid').val();
                d.promotion_uuid = $('#promotion_uuid').val();
            }
        },
        columns: [{
                data: 'check_product_code',
                name: 'check_product_code',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'check_product_name',
                name: 'check_product_name',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'check_product_qty',
                name: 'check_product_qty',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center list-action" style="text-align:center">
                            <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="check_product_delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                        </div>`
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
        }],
    })
    //////Cash Coupon////
    var cash_coupon_table = $('#cash_coupon_list').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "autoWidth": true,
        "responsive": true,
        "pageLength": 10,
        "iDeferLoading": 0,
        "scrollY": "450px",
        "scrollCollapse": true,
        'ajax': {
            'url': "/cash_coupon_result",
            'type': 'GET',
            'data': function(d) {
                d.promotion_uuid = $('#cpcc_promotion_uuid').val();
                d.sub_promotion_uuid = $('#cpcc_sub_promotion_uuid').val();
            }
        },
        columns: [{
                data: 'branch',
                name: 'branch',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'name',
                name: 'name',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'qty',
                name: 'qty',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center list-action" style="text-align:center">
                            <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="check_cash_coupon_delete" href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                        </div>`
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
        }],
    })
    var present_result_table = $('#present_result').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "autoWidth": true,
        "responsive": true,
        "pageLength": 10,
        "scrollY": "450px",
        "iDeferLoading": 0,
        "scrollCollapse": true,
        'ajax': {
            'url': "/present_result",
            'type': 'GET',
            'data': function(d) {
                d.sub_promotion_uuid = $('#sub_promotion_uuid').val();
            }
        },
        columns: [{
                data: 'branch',
                name: 'branch',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'name',
                name: 'name',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'qty',
                name: 'qty',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center list-action" style="text-align:center">
                            <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="present_delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                        </div>`
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
        }],
    })

    var winning_chance_table = $('#winning_chance_list').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "autoWidth": true,
        "responsive": true,
        "pageLength": 10,
        "scrollY": "450px",
        "iDeferLoading": 0,
        "scrollCollapse": true,
        'ajax': {
            'url': "/winning_result",
            'type': 'GET',
            'data': function(d) {
                d.sub_promotion_uuid = $('#sub_promotion_uuid').val();
                d.uuid = $('uuid').val();
            }
        },
        columns: [{
                data: 'branch_id',
                name: 'branch_id',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'minimum_amount',
                name: 'minimum_amount',
                orderable: true,
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center list-action" style="text-align:center">
                            <a class="badge bg-warning mr-2" data-uuid="${row.uuid}" title="Delete" id="winning_chance_delete"  href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"
                                id="winning_chance_edit_form" href="#"" data-uuid="${row.uuid}"><i class="ri-pencil-line mr-0"></i></a>
                        </div>`
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
        }],
    })

    sub_promotion_table.on('click', '#edit', function(e) {
        e.preventDefault();
        var sub_promotion_uuid = $(this).data('sub_promotion_uuid');
        $('#a_sub_promotion_uuid').val(sub_promotion_uuid);
        var promotion_uuid = $('#promotion_uuid').val();
        $('#a_promotion_uuid').val(promotion_uuid);
        $.ajax({
            url: '../../get_check_invoice_info/'+ promotion_uuid + '/' +sub_promotion_uuid,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.invoice_check_type == null || response.invoice_check_type == 1){

                    $("#check_invoice1").prop("checked", true)
                    check_invoice1();
                    $('#amount').val('');
                    $('#amount').val(response.check_value);

                    $("#branch_id").empty();
                    $.each( response.checkbranch, function(k, v) {
                        $('#branch_id').append($('<option>', {value:k, text:v}));
                    });
                }
                if(response.invoice_check_type == 2){
                    $("#check_invoice2").prop("checked", true)
                    check_invoice2();
                }
            },
            error: function() {
                $('#price_order').val('');
                $('#price_name').val('');
                $('#price_amount').val('');
                $('#price_quantity').val('');
                $('#price_description').val('');
            }
        });
    })

    sub_promotion_table.on('click', '#edit_form', function(e) {
        e.preventDefault();
        var sub_promotion_uuid = $(this).data('sub_promotion_uuid');
        $('#a_sub_promotion_uuid').val(sub_promotion_uuid);
        var promotion_uuid = $('#promotion_uuid').val();
        $('#a_promotion_uuid').val(promotion_uuid);
        $.ajax({
            url: '../../get_check_prize_info/'+ promotion_uuid + '/' +sub_promotion_uuid,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.prize_check_type == null || response.prize_check_type == 1){

                    $("#prize1").prop("checked", true)
                    prize1();
                    $('#ticket_prize_amount').val('');
                    if(response.check){
                        $('#ticket_prize_amount').val(response.check.ticket_prize_amount);
                        var link = '/images/amount_image/' + response.check.ticket_prize_image;
                        $("#prize_ticket_image").attr("src",link);
                    }
                }
                if(response.prize_check_type == 2){
                    $("#prize2").prop("checked", true)
                    prize2();
                    $('#cash_coupon_list').DataTable().draw(true);
                    $('#present_result').DataTable().draw(true);
                    $('#winning_chance_list').DataTable().draw(true);

                }
                if(response.prize_check_type == 3){
                    $("#prize2").prop("checked", true)
                    prize2();
                    $('#cash_coupon_list').DataTable().draw(true);
                    $('#present_result').DataTable().draw(true);
                    $('#winning_chance_list').DataTable().draw(true);

                }
            },
            error: function() {
                $('#price_order').val('');
                $('#price_name').val('');
                $('#price_amount').val('');
                $('#price_quantity').val('');
                $('#price_description').val('');
            }
        });
    })

    sub_promotion_table.on('click', '#sub_promotion_delete', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.sub_promotion_delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                var sub_promotion_uuid = $(this).data('sub_promotion_uuid');
                var promotion_uuid = $('#promotion_uuid').val();
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../sub_promotion_destory/' + promotion_uuid + '/' + sub_promotion_uuid,
                    type: 'DELETE',
                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#name').val('');
                        $('#sub_promotion_list').DataTable().draw(true);
                    },
                    error: function() {
                        $('#name').val("");
                    }
                });
            } else {
                return false;
            }
        });
    })

    check_product_table.on('click', '#check_product_delete', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.check_product_delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                var uuid = $(this).data('uuid');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../check_products_destory/' + uuid,
                    type: 'DELETE',

                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#check_product_code').val('');
                        $('#check_product_name').val('');
                        $('#check_product_qty').val('');
                        $('#check_product_list').DataTable().draw(true);
                    },
                    error: function() {
                        $('#check_product_code').addClass('is-invalid');
                        $('#check_product_name').val("");
                        $('#check_product_qty').val("");
                    }
                });
            } else {
                return false;
            }
        });
    })

    cash_coupon_table.on('click', '#check_cash_coupon_delete', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.check_cash_coupon_delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                var uuid = $(this).data('uuid');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../cash_coupon_destory/' + uuid,
                    type: 'DELETE',

                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#check_product_code').val('');
                        $('#check_product_name').val('');
                        $('#check_product_qty').val('');
                        $('#cash_coupon_list').DataTable().draw(true);
                    },
                    error: function() {
                        $('#check_product_code').addClass('is-invalid');
                        $('#check_product_name').val("");
                        $('#cash_coupon_list').val("");
                    }
                });
            } else {
                return false;
            }
        });
    })

    present_result_table.on('click', '#present_delete', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.check_present_delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                var uuid = $(this).data('uuid');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../present_destory/' + uuid,
                    type: 'DELETE',

                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#check_product_code').val('');
                        $('#check_product_name').val('');
                        $('#check_product_qty').val('');
                        $('#present_result').DataTable().draw(true);
                    },
                    error: function() {

                        $('#check_product_code').addClass('is-invalid');
                        $('#check_product_name').val("");
                        $('#present_result').val("");
                    }
                });
            } else {
                return false;
            }
        });
    })
    winning_chance_table.on('click', '#winning_chance_delete', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.winning_chance_delete') }}",
            showCancelButton: true,
            cancelButtonText: "{{ __('message.cancel') }}",
            confirmButtonText: "{{ __('message.ok') }}"
        }).then((result) => {

            if (result.isConfirmed) {
                var uuid = $(this).data('uuid');

                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: '../../winning_chance_destory/' + uuid,
                    type: 'DELETE',

                    data: {
                        "_token": token,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#check_product_code').val('');
                        $('#check_product_name').val('');
                        $('#check_product_qty').val('');
                        $('#winning_chance_list').DataTable().draw(true);
                    },
                    error: function() {
                        $('#check_product_code').addClass('is-invalid');
                        $('#check_product_name').val("");
                        $('#winning_chance_list').val("");
                    }
                });
            } else {
                return false;
            }
        });
    })
    winning_chance_table.on('click', '#winning_chance_edit_form', function(e) {
        $('.edit_form').modal('hide');
        e.preventDefault();
        var id = $(this).data('uuid');

        $.ajax({
            url: '../../winning_chance_edit/' + id,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                $('.winning_chance_edit_form').modal('show');
                $('#b_name').html(response.infor.b_name);
                $('#c_name').html(response.infor.c_name);
                var templateString;
                var templateStringuuid;
                var data = response.data;
                $.each(data, function(i) {
                    var old_value = data[i].winning_percentage ?? 0;
                    templateString += '<tr><th>' + data[i].name + '</th>' +
                        '<td><input type="hidden" name=main_uuid[] value="' + data[i]
                        .main_uuid + '">' +
                        '<input type="text" name=winning_chance[] class="form-control" data-errors="Please Enter Amount." value="' +
                        old_value + '" required></td></tr>'
                })
                $('#detail').html("");
                $('#detail').append(templateString)
            },
            error: function() {

            }
        });
    })

    $('#sub_promotion_name').on('change', function () {
        if ((this.value) == 'other') {
            $(this).replaceWith($('<input/>',{'type':'text','value':'','class':'form-control','name':'sub_promotion_name'}));
        }
    });

    function InvoiceCheckAmountValidateForm(){

        sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();

        $.ajax({
            url: '../../../check_invoice_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/1',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.data){
                    if(response.data == 'same_type'){
                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('message.success') }}",
                            text: "{{ __('message.successfully_added_invoice_check_amount') }}",
                            confirmButtonText: "{{ __('message.ok') }}",
                        }).then((result) => {
                            $('#invoice_check_amount').submit();
                        });
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.this_subpromotion_is_used_by_product') }}",
                            showCancelButton: true,
                            cancelButtonText: "{{ __('message.cancel') }}",
                            confirmButtonText: "{{ __('message.ok') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#invoice_check_amount').submit();
                            } else {
                                return false;
                            }
                        });
                    }
                }
            },
            error: function() {

            }
        });

    }
    function InvoiceCheckProductValidateForm(){

        sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();

        $.ajax({
            url: '../../../check_invoice_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/2',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.data){
                    if(response.data == 'first_time'){
                        clearInvoiceCheckOldData();
                        $('.add').modal('show');
                    }
                    if(response.data == 'same_type'){
                        clearInvoiceCheckOldData();
                        $('.add').modal('show');
                    }
                    if(response.data == 'different_type'){
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.this_subpromotion_is_used_by_amount') }}",
                            showCancelButton: true,
                            cancelButtonText: "{{ __('message.cancel') }}",
                            confirmButtonText: "{{ __('message.ok') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.add').modal('show');
                            } else {
                                return false;
                            }
                        });
                    }
                }
            },
            error: function() {

            }
        });

    }

    function clearInvoiceCheckOldData(){
        $('#amount').val("");
        $('#product_name').val("");
        $('#product_code').val("");
        $('#product_qty').val("");

    }

    function PrizeCheckTicketValidateForm(){
        sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        $('#p_sub_promotion_uuid').val(sub_promotion_uuid);
        $('#p_promotion_uuid').val(promotion_uuid);
        if ($('#ticket_prize_amount').val() == "") {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: `{{ __('message.need_ticket_prize_amount') }}`,
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if (!$('#ticket_prize_image').val()) {
            if($('#prize_ticket_image').src == ''){
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: `{{ __('message.need_ticket_prize_image') }}`,
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
        }
        $.ajax({
            url: '../../../check_prize_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/1',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.data){
                    if(response.data == 'first_time'){
                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('message.success') }}",
                            text: "{{ __('message.successfully_added_prize_check_ticket') }}",
                            confirmButtonText: "{{ __('message.ok') }}",
                        }).then((result) => {
                            $('#prize_check_ticket').submit();
                        });
                    }
                    if(response.data == 'same_type'){
                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('message.success') }}",
                            text: "{{ __('message.successfully_added_prize_check_ticket') }}",
                            confirmButtonText: "{{ __('message.ok') }}",
                        }).then((result) => {
                            $('#prize_check_ticket').submit();
                        });
                    }
                    if(response.data == 'different_type'){
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.other_prized_check_is_used') }}",
                            showCancelButton: true,
                            cancelButtonText: "{{ __('message.cancel') }}",
                            confirmButtonText: "{{ __('message.ok') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#prize_check_ticket').submit();
                            } else {
                                return false;
                            }
                        });
                    }
                }
            },
            error: function() {

            }
        });

    }

    function PrizeCheckGrab1ValidateForm(){
        sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        jQuery("#load").fadeOut();
        jQuery("#loading").show();

        $('#cpcc_sub_promotion_uuid').val(sub_promotion_uuid);
        $('#cpcc_promotion_uuid').val(promotion_uuid);
        $.ajax({
            url: '../../../check_prize_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/2',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.data){
                    if(response.data == 'different_type'){
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.other_prized_check_is_used') }}",
                            showCancelButton: true,
                            cancelButtonText: "{{ __('message.cancel') }}",
                            confirmButtonText: "{{ __('message.ok') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.cash-coupon').modal('show');

                            } else {
                                return false;
                            }
                        });
                    }else{
                        $('.cash-coupon').modal('show');

                    }
                }
            },
            error: function() {

            }
           
        });

    }

    function PrizeCheckGrab2ValidateForm(){
        sub_promotion_uuid = $('#a_sub_promotion_uuid').val();
        promotion_uuid = $('#promotion_uuid').val();
        $('.edit_form').modal('hide');

        $.ajax({
            url: '../../../check_prize_check_type/' + promotion_uuid + '/' + sub_promotion_uuid +'/2',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                jQuery("#load").fadeOut();
                jQuery("#loading").show();
            },
            complete: function() {
                jQuery("#loading").hide();
            },
            success: function(response) {
                if(response.data){

                    if(response.data == 'different_type'){
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.other_prized_check_is_used') }}",
                            showCancelButton: true,
                            cancelButtonText: "{{ __('message.cancel') }}",
                            confirmButtonText: "{{ __('message.ok') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.add-present-form').modal('show');
                            } else {
                                return false;
                            }
                        });
                    }else{

                        $('.add-present-form').modal('show');
                    }
                }
            },
            error: function() {

            }
        });

    }

</script>
@endsection
