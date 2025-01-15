@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{__('report.customer_number_compare')}}</h4>
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
            <div class="card-body">
                <form action="{{route('report.calculate_customer_number_compare')}}" method="get" enctype="multipart/form-data"  onsubmit="return validateForm()">
                <div class="row">
                    @csrf
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('report.compare_name')}} </label>
                                @if(isset($compare_name))
                                <select name="compare_name" id="compare_name" class="form-control" required>
                                        <option value="1" {{$compare_name == 1 ? 'selected' : ''}}>Customer Compare</option>
                                        <option value="2" {{$compare_name == 2 ? 'selected' : ''}}>Coupon Compare</option>
                                </select>
                                @else
                                <select name="compare_name" id="compare_name" class="form-control" required>
                                        <option value="1">Customer Compare</option>
                                        <option value="2">Coupon Compare</option>
                                </select>
                                @endif
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('report.promotion')}} </label>
                                @if(isset($compare_name))
                                <select name="promotion_uuid[]" id="promotion_uuid" class="form-control" multiple required>
                                    @foreach($promotions as $promotion)
                                        <option value="{{ $promotion->uuid }}" {{ in_array($promotion->uuid, $used_promotions->pluck('id')->toarray() ?: []) ? 'selected' : '' }}>
                                            {{ $promotion->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @else
                                <select name="promotion_uuid[]" id="promotion_uuid" class="form-control" multiple required>
                                    @foreach($promotions as $promotion)
                                        <option value="{{ $promotion->uuid }}" {{ ($promotion->uuid == old("promotion_uuid")) ? 'selected' : '' }}>
                                            {{ $promotion->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        <button class="btn btn-primary col-md-1 mr-2" type="submit" id="search_report">{{ __('button.search') }}</button>
                     
                    </div>  
                </form>
            </div>
            
        </div>
        
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>
    function validateForm() {
        if ($('#promotion_uuid :selected').length < 2) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: "{{ __('message.need_to_choose_two_promotion') }}",
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
    $(document).ready(function() {
        $('#promotion_uuid').select2({
            width: '100%',
            allowClear: true,
        });
      
        $('#export_report').on('click', function(e) {
            var compare_name = $('#compare_name').val();
            var promotion_uuid = $('#promotion_uuid').val();
           
            if ($('#promotion_uuid').val() == ""  ) {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ __('message.warning') }}",
                    text: "{{ __('message.need_promotions') }}",
                    confirmButtonText: "{{ __('message.ok') }}",
                });
                return false;
            }
            var url = `/reports/customer_number_compare_export/${compare_name}/${promotion_uuid}`;
            window.location = url;
        })
    });
</script>
@stop
