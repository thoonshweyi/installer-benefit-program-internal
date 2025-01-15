@extends('layouts.app')

@section('content')
<?php
    use App\Http\Controllers\HomeController;
    $homeContorllerConnection = new HomeController();
?>
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-transparent card-block card-stretch card-height border-none">
                    <div class="card-body p-0 mt-lg-2 mt-0">
                        <h3 class="mb-3">{{__('home.hi')}} , {{Auth::user()->name}}</h3>
                        <p class="mb-3"><strong>

                            </strong></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                <div class="card-body">
                    <h3>Active Promotion Status</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="bg-primary" scope="col">Promotion Name</th>
                                <th class="bg-primary" scope="col">Branch</th>
                                <th class="bg-primary" scope="col">Today Ticket</th>
                                <th class="bg-primary" scope="col">Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promotionBranchs as $promotionBranch)
                            <tr>

                                <th class="table-info">
                                    <a href="{{route('today_ticket_detail',['promotion_uuid'=>$promotionBranch->promotion_uuid,'branch_id'=>$promotionBranch->branch_id])}}">
                                        {{$promotionBranch->promotion_name}} <i class="fa fa-info-circle fa-lg exchange_deducted" data-lucky_draw_uuid={{$promotionBranch->promotion_uuid}} id="view_promotion_info"></i>
                                    </a>

                                </th>
                                <th class="table-success">{{$promotionBranch->branch_name_eng}}</th>
                                <th class="table-primary">

                                        {{getTodayTicket($promotionBranch->promotion_uuid,$promotionBranch->branch_id)}}
                                   

                                    {{-- {{$homeContorllerConnection->findTodayTicket($promotionBranch->promotion_uuid,$promotionBranch->branch_id)}} --}}
                                </th>
                                <th class="table-secondary">
                                    {{getTotalTicket($promotionBranch->promotion_uuid,$promotionBranch->branch_id)}}
                                    {{-- {{$homeContorllerConnection->findTotalTicket($promotionBranch->promotion_uuid,$promotionBranch->branch_id)}} --}}
                                </th>
                            </tr>
                            @endforeach
                        </tboday>
                    </table>
                    </div>
                    @can('view-dashboard-return-total')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">

                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-info-light">
                                        <img src="{{ asset('images/return_icon.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('documents.index','type=1')}}">
                                            <p class="mb-2">{{__('home.total_return_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($totalReturnDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-return-finish')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-success-light">
                                        <img src="{{ asset('images/return_icon.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('document_detail_listing','detail_type=1')}}">
                                            <p class="mb-2">{{__('home.finish_return_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($completeReturnDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-return-pending')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-danger-light">
                                        <img src="{{ asset('images/return_icon.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('document_detail_listing','detail_type=2')}}">
                                            <p class="mb-2">{{__('home.pending_return_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($totalReturnDoc - $completeReturnDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-exchange-total')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-info-light">
                                        <img src="{{ asset('images/exchange_icon.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('documents.index','type=2')}}">
                                            <p class="mb-2">{{__('home.total_exchange_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($totalExchangeDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-exchange-finish')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-success-light">
                                        <img src="{{ asset('images/exchange_icon.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('document_detail_listing','detail_type=3')}}">
                                            <p class="mb-2">{{__('home.finish_exchange_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($completeExchangeDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-exchange-pending')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-danger-light">
                                        <img src="{{ asset('images/exchange_icon.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('document_detail_listing','detail_type=4')}}">
                                            <p class="mb-2">{{__('home.pending_exchange_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($totalExchangeDoc - $completeExchangeDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-overdue-exchange-document')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-danger-light">
                                        <img src="{{ asset('images/overdue_exchange_document.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('document_detail_listing','detail_type=5')}}">
                                            <p class="mb-2">{{__('home.overdue_exchange_document')}}</p>
                                        </a>
                                        <h4>{{number_convert($overdueExchangeDoc)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-total-user')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-info-light">
                                        <img src="{{ asset('images/member_image.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{route('users.index')}}">
                                            <p class="mb-2">{{__('home.total_user')}}
                                        </a></p>
                                        <h4>{{number_convert($totalUser)}}</h4>
                                    </div>
                                </div>
                                <div class="iq-pFrogress-bar mt-2">
                                    <span class="bg-info iq-progress progress-1" data-percent="85">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-total-supplier')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-danger-light">
                                        <img src="{{ asset('images/department_image.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{ route('suppliers.index')}}">
                                            <p class="mb-2">{{__('home.total_supplier')}}</p>
                                        </a>
                                        <h4>{{number_convert($totalSupplier)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-total-branch')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-success-light">
                                        <img src="{{ asset('images/branch_image.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{ route('branches.index')}}">
                                            <p class="mb-2">{{__('home.total_branch')}}</p>
                                        </a>
                                        <h4>{{ number_convert($totalBranch) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('view-dashboard-total-role')
                    <div class="col-lg-4 col-md-4">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-success-light">
                                        <img src="{{ asset('images/role_image.png') }}" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <a href="{{ route('roles.index')}}">
                                            <p class="mb-2">{{__('home.total_role')}}</p>
                                        </a>
                                        <h4>{{number_convert($totalBranch)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>

        </div>
        <!-- Page end  -->
    </div>
</div>
<div class="modal fade show_promotion_info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="product_modal_title">Promotion Infomation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="product_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name :</label>
                                <label style="font-weight:bold" id="lucky_draw_name"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Type :</label>
                                <label style="font-weight:bold" id="lucky_draw_type_name"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Branches :</label>
                                <label style="font-weight:bold" id="lucky_draw_branches"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Categories :</label>
                                <label style="font-weight:bold"id="lucky_draw_categories"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Brands :</label>
                                <label style="font-weight:bold" id="lucky_draw_brands"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Discon Status :</label>
                                <label style="font-weight:bold" id="discon_status"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Amount For 1 Ticket :</label>
                                <label style="font-weight:bold" id="lucky_draw_promotion_amount"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Start Date :</label>
                                <label style="font-weight:bold" id="start_date"></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>End Date :</label>
                                <label style="font-weight:bold" id="end_date"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).on('click',"#view_promotion_info", function(){
            var lucky_draw_uuid = $(this).data('lucky_draw_uuid');

            var token = $("meta[name='csrf-token']").attr("content");
            if(lucky_draw_uuid){
                    $.ajax({
                    url: '../../lucky_draws/'+ lucky_draw_uuid,
                    type: 'get',
                    data: {
                        "_token": token,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {
                        $('#lucky_draw_name').text(response.lucky_draw);
                        $('#lucky_draw_type_name').text(response.lucky_draw_type);
                        $('#lucky_draw_branches').text(response.lucky_draw_branches);
                        $('#lucky_draw_categories').text(response.lucky_draw_categories);
                        $('#lucky_draw_brands').text(response.lucky_draw_brands);
                        $('#discon_status').text(response.lucky_draw_discon);
                        if(response.lucky_draw_promotion_amount){
                            $('#lucky_draw_promotion_amount').text(response.lucky_draw_promotion_amount.toLocaleString());
                        }
                        $('#start_date').text(response.lucky_draw_start_date);
                        $('#end_date').text(response.lucky_draw_end_date);
                        $('.show_promotion_info').modal('show');
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
</script>
@endsection
