<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lucky Draw</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="BreezyCV - Resume / CV / vCard Template" />
    <meta name="keywords" content="vcard, resposnive, retina, resume, jquery, css3, bootstrap, Material CV, portfolio" />
    <meta name="author" content="lmpixels" />
    <meta
     name='viewport'
     content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'
/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/animations.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css')}}" type="text/css">


    <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/linear-icons-font/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/new_promotion.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/new-backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/backend-bundle.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <audio src="{{ asset('music/30Seconds_ForGrabTheChance_v2.mp3') }}" id="music" class="hidden"></audio>
  </head>
  <style>
    /* Custom styles for the input box with focus */
    .custom-input:focus {
      border-color: #007bff; /* Change this to your desired border color */
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Optional: Add a box shadow for visual effect */
    }
    .text-red-500{
        color: red;
    }
    .custom-form-select {
        /*  */
        border: 2px solid #d5d6d7;
        border-radius: 4px;
        height: 45px;
        padding: 10px;
        background-color: white;
        color: inherit;
    }
    .select-width
    {
        width: 320px;
    }
  </style>

  <body>
    <!-- Animated Background -->
    <!-- <div class="lm-animated-bg" style="background-image: url(img/main_bg.png);"></div> -->
    <div class="lm-animated-bg"><img src="{{ asset('img/main_bg.png')}}" style="background-image"></div>
    <!-- /Animated Background -->

    <!-- Loading animation -->
    <div id="loading">
          <div id="loading-center">
            <h4 id="laoding_word" style="padding: 35% 0px;">{{__('new_layout.loading')}}</h4>
        </div>
    </div>
    <!-- /Loading animation -->

    <div class="page">
      <div class="page-content" style="right: 40px;">
          <header id="site_header" class="header mobile-menu-hide">
            <div class="header-content">
              <div class="">
                <a onclick="window.location.reload();">
                  <img src="{{ asset('images/new_design/best-practices.gif') }}" alt="Alex Smith">
                </a>
              </div>
              <div class="header-titles">
                <h2>LUCKY DRAW</h2>
                <h4>{{__('new_layout.try_it')}}</h4>
              </div>


            </div>

            <ul class="main-menu">
              <li class="active">
                <a href="#advertisement" class="nav-anim">
                  <span class="menu-icon lnr lnr-home"></span>
                  <span class="link-text">{{__('new_layout.home')}}</span>
                </a>
              </li>
              <li>
                <a href="#new_invoice" class="nav-anim1" id="new_invoice_link">
                  <span class="menu-icon lnr lnr-file-add"></span>
                  <span class="link-text">{{__('new_layout.new_ticket')}}</span>
                </a>
              </li>
              <li>
                <a href="#invoices" class="nav-anim" id="invoices_link"
                {{ isset($ticket_header_uuid) ? ''  : 'disabled'}}
                >
                  <span class="menu-icon lnr lnr-book"></span>
                  <span class="link-text">{{__('new_layout.invoices')}}</span>
                </a>
              </li>
              <li>
                <a href="#information" class="nav-anim" id="information_link"
                {{ isset($ticket_header_uuid) ? ''  : 'disabled'}}
                >
                  <span class="menu-icon lnr lnr-user"></span>
                  <span class="link-text">{{__('new_layout.information')}}</span>
                </a>
              </li>
              <li>
                <a href="#available_promotions" class="nav-anim"
                {{ isset($ticket_header_uuid) ? ''  : 'disabled'}}
                >
                  <span class="menu-icon lnr lnr-select"></span>
                  <span class="link-text">{{__('new_layout.available_promotions')}}</span>
                </a>
              </li>
              <li>
                <a href="#my_promotions" class="nav-anim"
                {{ isset($ticket_header_uuid) ? ''  : 'disabled'}}
                >
                  <span class="menu-icon lnr lnr-star"></span>
                  <span class="link-text">{{__('new_layout.my_promotions')}}</span>
                </a>
              </li>
              <li>
                <a href="#summary" class="nav-anim"
                {{ isset($ticket_header_uuid) ? ''  : 'disabled'}}
                >
                  <span class="menu-icon lnr lnr-list"></span>
                  <span class="link-text">{{__('new_layout.summary')}}</span>
                </a>
              </li>
            </ul>
            <div style="position: absolute;top: 400px;right: -85px;">
                @php $locale = session()->get('locale') == 'en' ? 'en' : 'mm';
                @endphp
                  <a href="{{ route('lang',$locale)}}">
                @switch($locale)
                    @case('en')
                        <img src="{{asset('images/languages/en.png')}}" alt="img-flag" class="img-fluid image-flag mr-2" style="
                        width: 45px;
                    ">
                    @break
                    @case('mm')
                        <img src="{{asset('images/languages/mm.png')}}" alt="img-flag" class="img-fluid image-flag mr-2" style="
                        width: 45px;
                    ">
                    @break
                    @default
                        <img src="{{asset('images/languages/mm.png')}}" alt="img-flag" class="img-fluid image-flag mr-2" style="
                        width: 45px;
                    ">
                @endswitch
                </a>
            </div>
            <div class="copyrights"><a onclick="openCloseFullscreen();"><p style="color:white; cursor: pointer;">PRO1 GLOBAL HOME CENTER</p></a></div>
          </header>

          <!-- Mobile Navigation -->
          <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <!-- End Mobile Navigation -->

          <!-- Arrows Nav -->
          <div class="lmpixels-arrows-nav row" id="next_button">
            <div class="lmpixels-arrow-right"  style="margin: auto !important;">
              <h3 style="color: white;">{{__('new_layout.next')}}</h3>
            </div>
          </div>
          <div class="lmpixels-arrows-nav row" id="new_button">
            <div class=""  style="margin: auto !important;">
              <h3 style="color: white;">{{__('new_layout.new_one')}}</h3>
            </div>
          </div>
          <div class="lmpixels-arrows-nav row" id="previous_button" style="right: 170px !important;">
            <div class="lmpixels-arrow-left"  style="margin: auto !important;">
              <h3 style="color: white;">{{__('new_layout.previous')}}</h3>
            </div>
          </div>
          <!-- End Arrows Nav -->

          <div class="content-area">
            <div class="animated-sections">
            <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{ isset($ticket_header_uuid) ? $ticket_header_uuid : ''}}">
            <input id="branch_id" type="hidden" value="{{ isset($branch_id) ? $branch_id : ''}}">
            <input type="hidden" name="gbh_customer_id" id="gbh_customer_id" value="{{ isset($customer) ? $customer->customer_id : ''}}">

            @includeIf('new_create_tickets.advertisement')
            @includeIf('new_create_tickets.new_invoice')
            @includeIf('new_create_tickets.invoices')
            @includeIf('new_create_tickets.information')
            @includeIf('new_create_tickets.available_promotions')
            @includeIf('new_create_tickets.my_promotions')
            @includeIf('new_create_tickets.summary')
            </div>
        </div>

        </div>
    </div>
    <div class="onScreen">
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
                    <div class="modal-body1">
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

<!-- /////New Desing/// -->

    <script src="{{ asset('js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('js/animating.js') }}"></script>

    <script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>


    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/jquery.shuffle.min.js') }}"></script>
    <script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>


    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    {{-- <link rel="stylesheet" href="{{ asset('onScreen/jQKeyboard.css')}}" /> --}}
    {{-- <script src="{{ asset('onScreen/jQKeyboard.js') }}"></script> --}}
    <script type="text/javascript">
        $(document).on("click", ".keyboardType0", function () {
            $('input.keyboardType0').initKeypad({'keyboardType': '0'});
        })


    </script>
    <script type="text/javascript">
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
        var idleTime = 0;
        // $(document).ready(function () {
        //     //Increment the idle time counter every minute.
        //     idleInterval = setInterval(timerIncrement, 120000); // 60000 = 1 minute

        //     //Zero the idle timer on mouse movement.
        //     $('body').mousemove(function (e) {
        //         //alert("mouse moved" + idleTime);
        //         idleTime = 0;
        //     });

        //     $('body').keypress(function (e) {
        //         //alert("keypressed"  + idleTime);
        //         idleTime = 0;
        //     });

        //     $('body').click(function() {
        //         //alert("mouse moved" + idleTime);
        //         idleTime = 0;
        //     });
        // });

        // function timerIncrement() {
        //     idleTime = idleTime + 1;
        //     if (idleTime > 1) {
        //         $('.choose_sub_promoiton_qty').modal('hide');
        //         $('.try_grab_the_chance_form').modal('hide');
        //         $('#new_button').hide();
        //         $('#next_button').hide();
        //         $('#previous_button').hide();
        //         var url =`#advertisement`;
        //         window.location = url;
        //     }
        // }


        function checkInputNumber(){
            var max= parseInt($('#choose_qty').attr('max'));
            var curr= parseInt($('#choose_qty').val());
            if(curr > max){
                $('#choose_qty').val(max);
            }
            if(curr < 0){
                $('#choose_qty').val(1);
            }
        }
       $('#new_button').hide();
       $('#next_button').hide();
       $('#previous_button').hide();

       $(document).on("click", "#new_invoice_link", function (e) {
        confirmNewInvoice();
       });
       $(document).on("click", ".page", function (e) {
       });
       $(document).on("click", "#new_button", function (e) {
        confirmNewInvoice();
       });
       function confirmNewInvoice(){
          var ticket_header_uuid = $('#ticket_header_uuid').val();
          if(ticket_header_uuid != '' || ticket_header_uuid != null){
            Swal.fire({
                icon: 'warning',
                title: `{{ __('message.confirm_new_invoice') }}`,
                showDenyButton: true,
                reverseButtons : true,
                denyButtonText: "{{ __('message.cancel') }}",
                confirmButtonText:"{{ __('message.ok') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                  var url =`/create_ticket#new_invoice`;
                  window.location = url;
                } else if (result.isDenied) {
                  return false;
                }

            })
          }
       };
       $('.info_button').on('click', function(e) {
            var promotion_uuid = jQuery(this).attr("data-promotion_uuid");
            var token = $("meta[name='csrf-token']").attr("content");
            if(promotion_uuid){
                    $.ajax({
                    url: '/promotion_info/'+ promotion_uuid,
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
        $(document).on("click", "#clear_button", function (e) {
            $('#invoice_no').val('');
            $('#invoice_no_2').val('');
       });
        clear_button
       $(document).on("click", ".nav-anim", function (e) {
        showButton();
       });
       showButton();
       function showButton(){
        if(!window.location.href.includes("#")){
            $('#next_button').hide();
            $('#previous_button').hide();
            $('#new_button').hide();
        }else if(window.location.href.includes("advertisement")){
            $('#next_button').hide();
            $('#previous_button').hide();
            $('#new_button').hide();

        }else if(window.location.href.includes("new_invoice")){
            $('#next_button').hide();
            $('#previous_button').hide();
            $('#new_button').hide();
        }else if(window.location.href.includes("information")){
            $('#next_button').addClass('customer_update');
            $('#next_button').show();
            $('#previous_button').show();
        }
        else if(window.location.href.includes("summary")){
            $('#next_button').hide();
            $('#new_button').show();
            $('#previous_button').show();
        }else{
            $('#new_button').hide();
            $('#next_button').show();
            $('#previous_button').show();
        }
       }
    </script>


  </body>


</html>
