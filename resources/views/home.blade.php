@extends('layouts.app')

@section('content')
<?php
    use App\Http\Controllers\HomeController;
    $homeContorllerConnection = new HomeController();
?>
<div class="content-page">
    <div class="container-fluid dashboard-container">
        <h1>Approval Requests</h1>

        <div class="row">
            @can('notified-redemption-transaction')
            <div class="col-lg-3">
                <a href="{{route('redemptiontransactions.approvalnotifications')}}">
                    <div class="card bg-white border-primary position-relative">
                        <div class="card-body text-center">
                            <h5 class="">
                                <i class="fas fa-comments-dollar"></i>
                                Redemption Transactions
                            </h5>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="dashboard-notis">{{ Auth::user()->unreadNotifications->count() }}+</div>
                            @endif
                        </div>
                    </div>
                </a>

            </div>
            @endcan


            <div class="col-lg-3">
                <a href="{{ $installer_card_search_url ? url($installer_card_search_url) : 'javascript:void(0)' }}">
                    <div class="card bg-white border-primary position-relative">
                        <div class="card-body text-center">
                            <h5 class="">
                                <i class="fas fa-id-card"></i>
                                Installer Card
                            </h5>
                            @if($installer_card_noti_count != 0)
                            <div class="dashboard-notis">{{ $installer_card_noti_count }}+</div>
                            @endif
                        </div>
                    </div>
                </a>

            </div>

            <div class="col-lg-3">
                <a href="{{ $card_number_generator_search_url ? url($card_number_generator_search_url) : 'javascript:void(0)' }}">
                    <div class="card bg-white border-primary position-relative">
                        <div class="card-body text-center">
                            <h5 class="">
                                <i class="fas fa-id-card"></i>
                                Card Number Generator
                            </h5>
                            @if($card_number_generator_noti_count != 0)
                            <div class="dashboard-notis">{{ $card_number_generator_noti_count }}+</div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-lg-3">
                <a href="{{ $credit_point_search_url ? url($credit_point_search_url) : 'javascript:void(0)' }}">
                    <div class="card bg-white border-primary position-relative">
                        <div class="card-body text-center">
                            <h5 class="">
                                <i class="fas fa-sliders-h"></i>
                                Credit Point Adjust
                            </h5>
                            @if($credit_point_adjust_noti_count != 0)
                            <div class="dashboard-notis">{{ $credit_point_adjust_noti_count }}+</div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>



        </div>
        <!-- Page end  -->
    </div>
</div>

@endsection
@section('js')
<script type="text/javascript">

</script>
@endsection


