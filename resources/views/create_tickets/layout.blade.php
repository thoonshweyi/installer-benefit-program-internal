<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>


    <link rel="stylesheet" href="{{ asset('css/promotion.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/remixicon/fonts/remixicon.css') }}">
</head>

<body>
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <div class="card">
        <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{ isset($ticket_header) ? $ticket_header->uuid : ''}}">
        <input type="hidden" name="ticket_header_print_status" id="ticket_header_print_status" value="{{ isset($ticket_header) ? $ticket_header->printed_at ? 1 : 2 : 2}}">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title"> {{__('new_promotion.create_tickets')}}</h4>

            </div>
            <div class="">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger" style="margin-bottom: 0 !important;">
                        <p style="margin-bottom: 0 !important;">{{ $message }}</p>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success" style="margin-bottom: 0 !important;">
                        <p style="margin-bottom: 0 !important;">{{ $message }}</p>
                    </div>
                @endif
            </div>
            <div class="header-title mr-4" >
                <a class="mr-3" href="{{route('tickets.collect_invoice_view')}}" style="font-size: 25px;"><i class="ri-file-add-line" style="color: gray;"></i></a>
                <a class="mr-3" href="{{ route('home') }}" style="font-size: 20px;"><i class="ri-home-2-line" style="color: gray;"></i></a>
                <a class="mr-3" onclick="openCloseFullscreen();" class="mr-2"><i class="ri-fullscreen-line"></i></a>
                <a href="#" class="search-toggle dropdown-toggle btn border add-btn" id="dropdownMenuButton02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @php $locale = session()->get('locale'); @endphp
                    @switch($locale)
                        @case('en')
                            <img src="{{asset('images/small/flag-01.png')}}" alt="img-flag" class="img-fluid image-flag mr-2">
                        @break
                        @case('mm')
                            <img src="{{asset('images/small/flag-02.png')}}" alt="img-flag" class="img-fluid image-flag mr-2">
                        @break
                        @default
                            <img src="{{asset('images/small/flag-02.png')}}" alt="img-flag" class="img-fluid image-flag mr-2">
                    @endswitch
                </a>
                <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    <div class="shadow-none m-0">
                        <div class="p-3">
                            <a class="iq-sub-card" href="{{ route('lang','mm')}}"><img src="{{asset('images/small/flag-02.png')}}" alt="img-flag" class="img-fluid mr-2">Myanmar</a>
                        </div>
                        <div class="p-3">
                            <a class="iq-sub-card" href="{{ route('lang','en')}}"><img src="{{asset('images/small/flag-01.png')}}" alt="img-flag" class="img-fluid mr-2">English</a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="card-body">
            <div class="row">
                <div class="side-area col-md-3">
                    <button class="{{ (strpos(Route::currentRouteName(), 'tickets.collect_invoice_view') === 0 || strpos(Route::currentRouteName(), 'tickets.edit_collect_invoice') === 0 ) ? 'button-active' : ''}}"
                    id="collect_invoices"
                    ><img
                            src="{{asset('images/promotion_icons/collect_invoice.png')}}" />{{__('new_promotion.collect_invoices')}}</button>

                    <button  class="{{ (strpos(Route::currentRouteName(), 'tickets.customer_info') === 0) ? 'button-active' : ''}}"
                    @if(isset($ticket_header_uuid))
                        id="check_customer_info"
                    @endif
                    ><img src="{{asset('images/promotion_icons/check_customer_info.png')}}" />{{__('new_promotion.check_customer_info')}}</button>

                    <button class="{{ (strpos(Route::currentRouteName(), 'tickets.choose_promotion') === 0) ? 'button-active' : ''}}"
                    @if(isset($ticket_header_uuid))
                        id="choose_promotion"
                        @endif
                    ><img src="{{asset('images/promotion_icons/choose_promotion.png')}}" />{{__('new_promotion.choose_promotion')}}</button>
                    <button class="{{ (strpos(Route::currentRouteName(), 'tickets.claim_prize') === 0) ? 'button-active' : ''}}"
                    @if(isset($ticket_header_uuid))
                        id="claim_prizes"
                        @endif
                    ><img src="{{asset('images/promotion_icons/claim_prizes.png')}}" />{{__('new_promotion.claim_prize')}}</button>

                    <button class="{{ (strpos(Route::currentRouteName(), 'tickets.summary') === 0) ? 'button-active' : ''}}"
                    @if(isset($ticket_header_uuid))
                        id="summary"
                    @endif
                    ><img src="{{strpos(Route::currentRouteName(), 'tickets.summary') === 0 ? asset('images/promotion_icons/summary1.png') : asset('images/promotion_icons/summary.png')}}" />{{__('new_promotion.summary')}}</button>


                </div>
                <div class="vhr">
                </div>
                @yield('content')

            </div>
        </div>
    <script src="{{ asset('js/backend-bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>

        function openCloseFullscreen() {
            var isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
                (document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
                (document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
                (document.msFullscreenElement && document.msFullscreenElement !== null);

            var docElm = document.documentElement;
            if (!isInFullScreen) {
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                } else if (docElm.mozRequestFullScreen) {
                    docElm.mozRequestFullScreen();
                } else if (docElm.webkitRequestFullScreen) {
                    docElm.webkitRequestFullScreen();
                } else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }
        $(document).ready(function(){
            jQuery("#loading").hide();
        });
        $('#collect_invoices').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{  isset($ticket_header_uuid) ? route('tickets.edit_collect_invoice',$ticket_header_uuid) : route('tickets.collect_invoice_view') }}";
            document.location.href = url;
        })
        $('#check_customer_info').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.customer_info', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
        })

        $('#choose_promotion').on('click', function(e) {
            jQuery("#loading").show();
            @if(isset($ticket_header_uuid))
            let url = "{{ route('tickets.choose_promotion', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
            @endif
        })
        $('#claim_prizes').on('click', function(e) {
            var claim_status = $(".choose-promotion").hasClass("choose_active");
            if(claim_status){
                jQuery("#loading").show();
                let url = "{{ route('tickets.claim_prize', $ticket_header_uuid ?? '') }}";
                document.location.href = url;
            }else{
                var current_url = window.location.href;
                if(current_url.includes("summary")){
                    jQuery("#loading").show();
                    let url = "{{ route('tickets.claim_prize', $ticket_header_uuid ?? '') }}";
                    document.location.href = url;
                }else{
                    Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: `{{ __('message.need_to_choose_one_promotion') }}`,
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                }
            }
        })
        $('#summary').on('click', function(e) {
            var claim_status = $(".claim-promotion").hasClass("choose_active");
            if(claim_status){
                var current_url = window.location.href;
                if(current_url.includes("claim_prize") || current_url.includes("collect_invoice")){
                    jQuery("#loading").show();
                    let url = "{{ route('tickets.summary', $ticket_header_uuid ?? '') }}";
                    document.location.href = url;
                }
            }else{
                if($('#ticket_header_print_status').val() != 2){
                    jQuery("#loading").show();
                    let url = "{{ route('tickets.summary', $ticket_header_uuid ?? '') }}";
                    document.location.href = url;
                }
            }
        })
    </script>
    @yield('js')
    </body>
</html>
