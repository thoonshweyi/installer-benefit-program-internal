@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Create Point Promotion</h4>
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
                    <form action="{{ route('pointpromos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Promotion Name<span class="cancel_status">*</sapn> </label>
                                    <input name="name" type="text" class="form-control" value="{{old('name')}}" placeholder="Enter Promotion Name"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pointperamount">Point Per Amount<span class="cancel_status">*</sapn> </label>
                                    <input type="number" name="pointperamount" id="pointperamount"  class="form-control pointperamount" value="{{old('pointperamount')}}" placeholder="Enter Amount for 1 point" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('lucky_draw.status')}} <span class="cancel_status">*</sapn> </label>
                                    <select id="status" name="status" class="form-control ">
                                        <option value="">Select Status</option>
                                        @can('approve-promotion')
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Inactive
                                        </option>
                                        @endcan
                                        <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mr-2">{{__('lucky_draw.branch')}} <span class="cancel_status">*
                                            </sapn> </label>
                                    <input type="checkbox" class="checkbox-input" name="select_all_branch"
                                        id="select_all_branch" {{old("select_all_branch") ? 'checked' : ''}}>
                                    <label for="select_all_branch">Select All Branch</label>

                                    <select name="branch_id[]" id="branch_id" class="form-control " multiple>
                                        @foreach($branches as $branch)
                                        <option value="{{ $branch->branch_id }}"
                                            {{ in_array($branch->branch_id, old("branch_id") ?: []) ? 'selected' : '' }}>
                                            {{ $branch->branch_name_eng}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('lucky_draw.start_date') }} <span class="cancel_status">*</sapn>
                                    </label>
                                    <input name="start_date" id="start_date" type="date" class="form-control"
                                        id="documentDate" onChange="check_start_date(this.value);"
                                        value="{{ old('start_date') ?? date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('lucky_draw.end_date') }} <span class="cancel_status">*</sapn>
                                    </label>
                                    <input name="end_date" id="end_date" type="date" class="form-control"
                                        id="documentDate" onChange="check_end_date(this.value);"
                                        value="{{ old('end_date') ?? date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="pointrules" class="form-group">
                                    <div class="d-flex justify-content-between">
                                        <label class="mr-2"> Specify Point Rules for each category </label>
                                        <button type="button" id="" class="btn btn-primary mb-2 text-center d-flex justify-content-center align-items-center ruleaddbtn"><i class="fas fa-plus"></i></button>

                                    </div>

                                    {{-- @if(old("category_id") === null) --}}
                                        <div class="row p-2 border rounded-lg">
                                            <div class="col-md-3 mb-2 mb-md-0">
                                                <select name="category_id[]" id="category_id" class="form-control category_id">
                                                    <option disabled selected>Choose Category</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->maincatid }}">
                                                        {{ $category->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-2 mb-md-0">

                                                <select name="group_id[0][]" id="group_id" class="form-control group_id" multiple>
                                                    {{-- <option disabled selected>Choose Group</option> --}}
                                                    {{-- @foreach($categories as $category)
                                                    <option value="{{ $category->maincatid }}">
                                                        {{ $category->name }}
                                                    </option>
                                                    @endforeach --}}
                                                </select>
                                                <input type="checkbox" class="checkbox-input select_all_groups" name=""
                                                    id="select_all_groups0">
                                                <label for="select_all_groups0">Select All Group</label>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number" name="redemption_value[]" id="redemption_value" class="redemption_value form-control" placeholder="Enter Redemption Value"/>
                                            </div>


                                            <div class="col">
                                                <div class="d-flex">
                                                    <button type="button" id="ruleremovebtn" class="ruleremovebtn btn btn-danger text-center d-flex justify-content-center align-items-center mr-1"><i class="fas fa-minus"></i></button>
                                                    <button type="button" id="" class="btn btn-primary mb-2 text-center d-flex justify-content-center align-items-center ruleaddbtn"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- @elseif (old("category_id") !== null)
                                        @foreach (old("category_id") as $idx=>$oldcategory_id)
                                            <div class="row p-2 border rounded-lg">
                                                <div class="col-md-6 mb-2 mb-md-0">
                                                    <select name="category_id[]" id="category_id" class="form-control">
                                                        <option disabled selected>Choose Category</option>
                                                        @foreach($categories as $category)
                                                        <option value="{{ $category->maincatid }}" {{ ($category->maincatid == $oldcategory_id) ? "selected" : "" }}>
                                                            {{ $category->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" name="redemption_value[]" id="redemption_value" class="redemption_value form-control" placeholder="Enter Redemption Value" value="{{ old('redemption_value')[$idx] }}"/>
                                                </div>
                                                <div class="col-md-2 d-flex justify-content-end">
                                                    <button type="button" id="ruleremovebtn" class="ruleremovebtn btn btn-danger text-center d-flex justify-content-center align-items-center"><i class="fas fa-minus"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif --}}
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mr-2">{{__('lucky_draw.category')}} <span class="cancel_status">*
                                            </sapn> </label>
                                    <input type="checkbox" class="checkbox-input" name="select_all_category"
                                        id="select_all_category" {{old("select_all_category") ? 'checked' : ''}}>
                                    <label for="select_all_category">Select All Category</label>

                                    <select name="category_id[]" id="category_id" class="form-control " multiple>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ in_array($category->id , old("category_id") ?: []) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mr-2">{{__('lucky_draw.brand')}} <span class="cancel_status">*
                                            </sapn> </label>
                                    <input type="checkbox" class="checkbox-input" name="select_all_brand"
                                        id="select_all_brand" {{old("select_all_brand") ? 'checked' : ''}}>
                                    <label for="select_all_brand">Select All Brand</label>

                                    <select name="brand_id[]" id="brand_id" class="form-control " multiple>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->product_brand_id }}"
                                            {{ in_array($brand->product_brand_id, old("brand_id") ?: []) ? 'selected' : '' }}>
                                            {{ $brand->product_brand_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mr-2">{{__('lucky_draw.discon_status')}} <span class="cancel_status">*
                                            </sapn> </label>
                                    <div class="radio d-inline-block mr-2">
                                        <input type="radio" name="discon_status" id="radio1" value='1' checked="">
                                        <label for="radio1">Include</label>
                                    </div>
                                    <div class="radio d-inline-block mr-2">
                                        <input type="radio" name="discon_status" id="radio2" value='2' >
                                        <label for="radio2">Exclude</label>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mr-2">{{__('lucky_draw.diposit_type')}} <span class="cancel_status">*
                                            </sapn> </label>
                                    <div class="radio d-inline-block mr-2">
                                        <input type="radio" name="diposit_type_id" id="diposit_type_radio1" value='1' checked="">
                                        <label for="radio1">All</label>
                                    </div>
                                    <div class="radio d-inline-block mr-2">
                                        <input type="radio" name="diposit_type_id" id="diposit_type_radio2" value='2' >
                                        <label for="radio2">Structure</label>
                                    </div>
                                    <div class="radio d-inline-block mr-2">
                                        <input type="radio" name="diposit_type_id" id="diposit_type_radio3" value='3' >
                                        <label for="radio2">HIP</label>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-md-12 mb-2">
                                <label for="remark">Remark</label>
                                <textarea name="remark" id="remark" class="form-control" rows="4" placeholder="Write Something....">

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
$('#branch_id').select2({
    width: '100%',
    allowClear: true,
});
{{-- $('#category_id').select2({
    width: '100%',
    minimumResultsForSearch: -1,
    allowClear: true,
}); --}}
$('.group_id').select2({
    width: '100%',
    placeholder: "Select a group",
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
    $('.edit').modal('show');
});
$(document).on("click", "#add", function() {
    $('.add').modal('show');
});
$(document).on("click", "#by-ticket", function() {
    $('.by-ticket').modal('show');
});
$(document).on("click", "#add-present-form", function() {
    $('.add-present-form').modal('show');
});
$(document).on("click", "#by-customer", function() {
    $('.by-customer').modal('show');
});
$(document).on("click", "#sub_category", function() {
    $('.sub_category').modal('show');
});

$(document).on("click", "#add-winning", function() {
    $('.add-winning').modal('show');
});
$('#warranty_status1').on('change', function(e) {
    $("#warranty_no").show();
    $("#add").hide();
    $("#add-list").hide();
    document.getElementById("warranty_no").required = true;
})

$('#warranty_status2').on('change', function(e) {
    $("#warranty_no").hide();
    $("#add").show();
    $("#add-list").show();
    document.getElementById("warranty_no").required = false;
})
$('#prize1').on('change', function(e) {
    $("#by-ticket").show();
    $("#by-customer").hide();
    $("#add-cash-coupon").hide();
    $("#by-fix-prize").hide();
    $("#by-present").hide();
    $("#add-present").hide();
    $("#add-present-list").hide();
    $("#add-present-form").hide();
    $("#add-winning").hide();
    $("#winning-chance").hide();
    document.getElementById("warranty_no").required = true;
})

$('#prize2').on('change', function(e) {
    $("#by-ticket").hide();
    $("#by-customer").show();
    $("#add-cash-coupon").show();
    $("#by-present").hide();
    $("#by-fix-prize").hide();
    $("#add-present").show();
    $("#add-present-list").show();
    $("#add-present-form").show();
    $("#add-winning").show();
    $("#winning-chance").show();
    document.getElementById("warranty_no").required = false;
})
$('#prize3').on('change', function(e) {
    $("#by-ticket").hide();
    $("#by-customer").hide();
    $("#add-cash-coupon").hide();
    $("#by-fix-prize").show();
    $("#by-present").show();
    $("#add-present").hide();
    $("#add-present-list").hide();
    $("#add-present-form").hide();
    $("#add-winning").hide();
    $("#winning-chance").hide();
    document.getElementById("warranty_no").required = false;
})

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

function check_start_date(start_date) {
    start_date = new Date(start_date);
    end_date = $('#end_date').val();
    var today = new Date();
    d_end_date = new Date(end_date);
    if (start_date > d_end_date) {
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.start_date_is_not_grether_than_end_date') }}",
            confirmButtonText: "{{ __('message.ok') }}",
        }).then(function() {
            $('#start_date').val(end_date);
            return false;

        });
    }
    if (today > start_date) {
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.start_date_is_not_last_than_today') }}",
            confirmButtonText: "{{ __('message.ok') }}",
        }).then(function() {
            $('#start_date').val(today);
            return false;

        });
    }
}

function check_end_date(end_date) {
    end_date = new Date(end_date);
    start_date = $('#start_date').val();
    var today = new Date();
    d_start_date = new Date(start_date);
    if (d_start_date > end_date) {
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.end_date_is_not_last_than_start_date') }}",
            confirmButtonText: "{{ __('message.ok') }}",
        }).then(function() {
            $('#end_date').val(start_date);
            return false;

        });
    }
    if (today > end_date) {
        Swal.fire({
            icon: 'warning',
            title: "{{ __('message.warning') }}",
            text: "{{ __('message.end_date_is_not_last_than_today') }}",
            confirmButtonText: "{{ __('message.ok') }}",
        }).then(function() {
            $('#start_date').val(today);
            return false;

        });
    }
}

$(document).ready(function() {
    $("#add").hide();
    $("#add-list").hide();
    $("#by-customer").hide();
    $("#add-cash-coupon").hide()
    $("#by-fix-prize").hide();
    $("#add-present").hide();
    $("#by-present").hide();
    $("#add-present-list").hide();
    $("#add-present-form").hide();
    $("#add-winning").hide();
    $("#winning-chance").hide();
    $("#amount_for_one_ticket").val() == "" ?? formatCurrency($("#amount_for_one_ticket"));

    function makeDisableForBranchSelectAll() {
        if ($("#select_all_branch").is(':checked')) {
            {{-- $("#branch_id").val(null).trigger("change"); --}}
            {{-- $('#branch_id').attr("disabled", true); --}}

            // Select all options in the branch dropdown (#branch_id)
            $("#branch_id option").prop("selected", true);
            $("#branch_id").trigger("change");
        } else {
            $("#branch_id option").prop("selected", false);
            {{-- ("#branch_id").trigger("change"); --}}
            $("#branch_id").val(null).trigger("change");

            {{-- $('#branch_id').attr("disabled", false); --}}
        }
    }
    $("#branch_id").trigger("change");
    {{-- makeDisableForBranchSelectAll(); --}}

    $(document).on("click", ".select_all_groups", function() {
        let selectElement = $(this).closest('.col-md-3').find('.group_id');

        if ($(this).is(':checked')) {
            selectElement.find('option').prop('selected', true); // Select all options
        } else {
            selectElement.find('option').prop('selected', false); // Deselect all options
        }

        selectElement.trigger('change');
    });

    {{-- function makeDisableForCategorySelectAll() {
        if ($("#select_all_category").is(':checked')) {
            // Select all options in the branch dropdown (#branch_id)
            $("#category_id option").prop("selected", true);
            $("#category_id").trigger("change");
        } else {
            $("#category_id option").prop("selected", false);
            $("#category_id").val(null).trigger("change");
        }
    } --}}
    {{-- makeDisableForCategorySelectAll(); --}}

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
    $("#branch_id").change(function(){
        selectedOptionCount = $('#branch_id option:selected').length;
        {{-- console.log($selectedOptionCount); --}}


        if(selectedOptionCount < {{ count($branches) }}){
            $('#select_all_branch').prop('checked',false);
        }
    });

    $(document).on("click", "#select_all_category", function() {
        makeDisableForCategorySelectAll();
    })
    $(document).on("click", "#select_all_brand", function() {
        makeDisableForBrandSelectAll();
    })

    ruleidx = 0;
    $(document).on("click", ".ruleaddbtn", function() {
        {{-- console.log('Rule Added'); --}}
        ++ruleidx;
        $("#pointrules").append(`
            <div class="row p-2 border rounded-lg">
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="category_id[]" id="category_id" class="form-control category_id">
                        <option selected disabled>Choose Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->maincatid }}">
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="group_id[${ruleidx}][]" id="" class="form-control group_id" multiple>
                        {{-- <option disabled selected>Choose Group</option> --}}
                        {{-- @foreach($categories as $category)
                        <option value="{{ $category->maincatid }}">
                            {{ $category->name }}
                        </option>
                        @endforeach --}}
                    </select>
                    <input type="checkbox" class="checkbox-input select_all_groups" name=""
                        id="select_all_groups${ruleidx}">
                    <label for="select_all_groups${ruleidx}">Select All Group</label>
                </div>
                <div class="col-md-3">
                    <input type="number" name="redemption_value[]" id="redemption_value" class="redemption_value form-control" placeholder="Enter Redemption Value"/>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <button type="button" id="ruleremovebtn" class="ruleremovebtn btn btn-danger text-center d-flex justify-content-center align-items-center mr-1"><i class="fas fa-minus"></i></button>
                        <button type="button" id="" class="btn btn-primary mb-2 text-center d-flex justify-content-center align-items-center ruleaddbtn"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        `);


        $('.group_id').select2({
            width: '100%',
            placeholder: "Select a group",
            allowClear: true,
        });
    });

    $(document).on('click','.ruleremovebtn',function(){
        Swal.fire({
            title: "Are you sure you want remove point rule?",
            text: "Your point rule will not be considered.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, remove it!"
        }).then((result) => {
            if (result.isConfirmed) {
                {{-- $(this).parent().parent().remove(); --}}
                {{-- console.log($(this).closest('.row')); --}}
                $(this).closest('.row').remove();
                Swal.fire({
                    title: "Deleted!",
                    text: "Your point rule has been removed",
                    icon: "success"
                });
            }
        });
    })
    {{-- $(".ruleremovebtn").click(function(){
        $(this).parent().parent().remove();
    }); --}}

    // Start Dynamic Select Option
    $(document).on('change','.category_id',function(){
        const maincatid = $(this).val();
        // console.log(getcountryid);
        const parentRow = $(this).closest('.row'); // Find the closest parent row

        let opforgroup = "";
        $.ajax({
            url: `/filter/groups/${maincatid}`,
            type: "GET",
            dataType:"json",
            success:function(response){
                    const relatedGroupSelect = parentRow.find('.group_id'); // Find the related group_id within the same row
                    relatedGroupSelect.empty(); // Clear the existing options
                    {{-- opforgroup += "<option selected disabled>Choose a group</option>"; --}}

                    console.log(response);
                    for(let x=0 ; x<response.groups.length; x++){
                        opforgroup += `<option value="${response.groups[x].product_group_id}">${response.groups[x].product_group_name}</option>`;
                    }

                    relatedGroupSelect.append(opforgroup);   // Append new options to the related group_id
            },
            error:function(response){
                    console.log("Error:",response);
            }
        });


    })
    // End Dynamic Select Option




});

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


{{-- @if(old("category_id") !== null)
showoldcategories();
@endif
function showoldcategories(){
    console.log("Old categofies");
} --}}
</script>
@endsection
