@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('reports.customer_tickets_by_area')}} </h4>
                    </div>
                </div>
            </div>
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
            <div class="col-lg-12 d-flex mb-2">
                <div class="form-row col-md-2">
                    <label>{{__('reports.branch')}} </label>
                    <select id="branch_id" class="form-control ">
                        <option value="0">Choose Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branches->branch_id }}" {{ $branch->branches->branch_id == $branch_id ? 'selected' : '' }}>
                                {{ $branch->branches->branch_name_eng}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('reports.promotion_name')}} </label>
                    <select id="lucky_draw_id" class="form-control ">
                        <option value="0">Choose Promotion</option>
                        @foreach($promotions as $promotion)
                            <option value="{{ $promotion->promotion_uuid }}"  {{ $promotion->promotion_uuid == $promotion_uuid ? 'selected' : '' }}>
                                {{ $promotion->promotions->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
               
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
            </div>
            <div class="col-lg-6">
                @if($amphurs)
                <table class="table mb-0 table-striped table-bordered nowrapo" style="width:100%" id="lucky_draw_list">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>{{__('report.amphurs')}}</th>
                            <th>{{__('report.count')}}</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach($amphurs as $a)
                        <tr class="ligth ligth-data">
                            <td>{{$a['amphur_id']}}</td>
                            <td>{{$a['total']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <div class="col-lg-6">
                @if($provinces)
                <table class="table mb-0 table-striped table-bordered nowrapo" style="width:100%" id="lucky_draw_list">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>{{__('report.provinces')}}</th>
                            <th>{{__('report.count')}}</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach($provinces as $p)
                        <tr class="ligth-data">
                            <td>{{$p['province_id']}}</td>
                            <td>{{$p['total']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>
    $(document).on('change',"#branch_id", function(){
        $("#lucky_draw_id option").remove();
        var branch_id = this.value;
        var token = $("meta[name='csrf-token']").attr("content");
            if (branch_id) {
                $.ajax({
                    url: '/promotion_by_branch',
                    type: 'get',
                    data: {
                        "_token": token,
                        "branch_id" : branch_id,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $.each( response, function(k, v) {
                            $('#lucky_draw_id').append($('<option>', {value:k, text:v}));
                        });
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

    $(document).on('click',"#search",function(){
        var branch_id = $('#branch_id').val();
        var lucky_draw_id = $('#lucky_draw_id').val();
        if(branch_id == 0){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: `{{ __('message.choose_branch') }}`,
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        if(lucky_draw_id == null ||lucky_draw_id == 0){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: `{{ __('message.choose_promotion') }}`,
                confirmButtonText: "{{ __('message.ok') }}",
            });
            return false;
        }
        var url = `/reports/customer_tickets_by_area/${branch_id}/${lucky_draw_id}`;
            window.location = url;
    })
</script>
@stop