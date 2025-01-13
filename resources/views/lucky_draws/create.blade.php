@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('lucky_draw.create') }}</h4>
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
                            <form action="{{ route('lucky_draws.store') }}" method="POST" enctype="multipart/form-data"  onsubmit="return validateForm()">
                            @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('lucky_draw.name')}}<span class="cancel_status">*</sapn> </label>
                                            <input name="name" type="text" class="form-control" value="{{old('name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>{{__('lucky_draw.status')}} <span class="cancel_status">*</sapn>  </label>
                                        <select id="status" name="status" class="form-control ">
                                            @can('approve-promotion')
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Inactive</option>
                                            @endcan
                                            <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>{{__('lucky_draw.lucky_draw_type')}} <span class="cancel_status">*</sapn>  </label>
                                        <select id="lucky_draw_type" name="lucky_draw_type" class="form-control ">
                                            <option value="">Normal Type</option>
                                            @foreach($lucky_draw_types as $lucky_draw_type)
                                            <option value="{{ $lucky_draw_type->uuid }}" {{ $lucky_draw_type->uuid == old("lucky_draw_type") ? 'selected' : '' }}>
                                                {{ $lucky_draw_type->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.product_type')}} <span class="cancel_status">*</sapn> </label>
                                                <div class="radio d-inline-block mr-2">
                                                    <input type="radio" name="diposit_type" id="diposit1" value='' checked="">
                                                    <label for="radio2">Both</label>
                                                </div>
                                            <div class="radio d-inline-block mr-2">
                                                <input type="radio" name="diposit_type" id="diposit2" value='3'>
                                                <label for="radio1">HIP</label>
                                            </div>
                                            <div class="radio d-inline-block mr-2">
                                                <input type="radio" name="diposit_type" id="diposit3" value='2'>
                                                <label for="radio2">Structure</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*</sapn>  </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_branch" id="select_all_branch" {{old("select_all_branch") ? 'checked' : ''}}>
                                            <label for="select_all_branch">Select All Branch</label>

                                            <select name="branch_id[]" id="branch_id" class="form-control " multiple >
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->branch_id }}" {{ in_array($branch->branch_id, old("branch_id") ?: []) ? 'selected' : '' }}>
                                                        {{ $branch->branch_name_eng}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.category')}} <span class="cancel_status">*</sapn> </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_category" id="select_all_category"  {{old("select_all_category") ? 'checked' : ''}}>
                                            <label for="select_all_branch">Select All Category</label>

                                            <select name="category_id[]" id="category_id" class="form-control " multiple >
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ in_array($category->id , old("category_id") ?: []) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.brand')}} <span class="cancel_status">*</sapn>  </label>
                                            <input type="checkbox" class="checkbox-input" name="select_all_brand" id="select_all_brand"  {{old("select_all_brand") ? 'checked' : ''}}>
                                            <label for="select_all_branch">Select All Brand</label>

                                            <select name="brand_id[]" id="brand_id" class="form-control " multiple >
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->product_brand_id }}" {{ in_array($brand->product_brand_id, old("brand_id") ?: []) ? 'selected' : '' }}>
                                                        {{ $brand->product_brand_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.start_date') }} <span class="cancel_status">*</sapn>  </label>
                                            <input name="start_date" id="start_date" type="date" class="form-control" id="documentDate" onChange="check_start_date(this.value);"  value="{{ old('document_date') ?? date('Y-m-d')}}"
                                           >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.end_date') }} <span class="cancel_status">*</sapn>  </label>
                                            <input name="end_date" id="end_date" type="date" class="form-control" id="documentDate" onChange="check_end_date(this.value);" value="{{ old('document_date') ?? date('Y-m-d')}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('lucky_draw.amount_for_one_ticket')}} <span class="cancel_status">*</sapn>  </label>
                                            <input id="amount_for_one_ticket" name="amount_for_one_ticket" type="text" class="form-control" value="{{old('amount_for_one_ticket')}}" value="" data-type="currency">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mr-2">{{__('lucky_draw.discon_status')}} <span class="cancel_status">*</sapn> </label>
                                            <div class="radio d-inline-block mr-2">
                                                <input type="radio" name="discon_status" id="radio1" value='1'>
                                                <label for="radio1">Include</label>
                                            </div>
                                            <div class="radio d-inline-block mr-2">
                                                <input type="radio" name="discon_status" id="radio2" value='2' checked="">
                                                <label for="radio2">Exclude</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.remark') }}</label>
                                            <textarea name="remark" class="form-control" rows="3">{{old('remark')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('lucky_draw.image') }} (560px x 140px) <span class="cancel_status">*</sapn></label>
                                            <input name="promotion_image" type="file" class="form-control image-file">
                                        </div>
                                    </div>

                                </div>
                                <button class="btn btn-primary mr-2">{{ __('button.save') }}</button>

                                <a class="btn btn-light" href="{{ route('lucky_draws.index') }}">{{ __('button.back') }}</a>

                            </form>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-3">{{ __('lucky_draw.added_prizes') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive rounded mb-3">
                                    <table class="table mb-0 tbl-server-info" id="product_list_by_document">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th>{{ __('price.price_order') }}</th>
                                                <th>{{ __('price.price_name') }}</th>
                                                <th>{{ __('price.price_amount') }}</th>
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

        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
    $('#branch_id').select2({
        width: '100%',
        allowClear: true,
    });
    $('#category_id').select2({
        width: '100%',
        minimumResultsForSearch: -1,
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
        if (input_val === "") { return; }

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
            input_val =  left_side + "." + right_side;

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

    function check_start_date(start_date){
        start_date = new Date(start_date);
        end_date = $('#end_date').val();
        var today = new Date();
        d_end_date = new Date(end_date);
        if(start_date > d_end_date ){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.start_date_is_not_grether_than_end_date') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            }).then(function(){
                alert(end_date)
                $('#start_date').val(end_date);
                return false;

                }
            );
        }
        if(today > start_date ){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.start_date_is_not_last_than_today') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            }).then(function(){
                $('#start_date').val(today);
                return false;

                }
            );
        }
    }

    function check_end_date(end_date){
        end_date = new Date(end_date);
        start_date = $('#start_date').val();
        var today = new Date();
        d_start_date = new Date(start_date);
        if(d_start_date > end_date ){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.end_date_is_not_last_than_start_date') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            }).then(function(){
                $('#end_date').val(start_date);
                return false;

                }
            );
        }
        if(today > end_date ){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.end_date_is_not_last_than_today') }}",
                confirmButtonText: "{{ __('message.ok') }}",
            }).then(function(){
                $('#start_date').val(today);
                return false;

                }
            );
        }
    }

    $(document).ready(function() {
        $("#amount_for_one_ticket").val() == "" ?? formatCurrency($("#amount_for_one_ticket"));
        function makeDisableForBranchSelectAll(){
            if($("#select_all_branch").is(':checked') ){
                $("#branch_id").val(null).trigger("change");
                $('#branch_id').attr("disabled", true);
            }else{
                $('#branch_id').attr("disabled", false);
            }
        }
        makeDisableForBranchSelectAll();
        function makeDisableForCategorySelectAll(){
            if($("#select_all_category").is(':checked') ){
                $("#category_id").val(null).trigger("change");
                $('#category_id').attr("disabled", true);
            }else{
                $('#category_id').attr("disabled", false);
            }
        }
        makeDisableForCategorySelectAll();
        function makeDisableForBrandSelectAll(){
            if($("#select_all_brand").is(':checked') ){
                $("#brand_id").val(null).trigger("change");
                $('#brand_id').attr("disabled", true);
            }else{
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
