 <!-- Promotion Subpage -->
 <section data-id="my_promotions" class="animated-section">
    <div class="section-content">
        <div class="page-title">
            <h2>{{__('new_promotion.my_promotions')}}</h2>
        </div>
        <p> {{__('new_promotion.my_promotions_description')}}</p>
        <div class="row">
        <div class="col-xs-12 col-sm-12">
            <!-- Portfolio Content -->
            <div class="portfolio-content">

            <ul class="portfolio-filters">
                <li class="active">
                <a class="filter btn btn-sm btn-link" data-group="category_all" style="font-size: 15px;">All</a>
                </li>
                <li>
                <a class="filter btn btn-sm btn-link" data-group="Main Promotion" style="font-size: 15px;">Main Promotion</a>
                </li>
                <li>
                <a class="filter btn btn-sm btn-link" data-group="Category Promotion" style="font-size: 15px;">Category Promotion</a>
                </li>
                <li>
                <a class="filter btn btn-sm btn-link" data-group="Event Promotion" style="font-size: 15px;">Event Promotion</a>
                </li>
            </ul>

            <!-- Portfolio Grid -->
            <div class="portfolio-grid three-columns">
                @foreach($promotion_types as $promotion_type)
                    @foreach($promotion_type->promotions as $promotion)
                    @if($promotion->claim_histories->count() > 0)
                        @foreach($promotion->claim_histories as $claim_history)
                            @if($claim_history->valid_qty != 0 && $claim_history->choose_status == 2)
                            <figure class="item lbaudio mr-1" data-groups='["{{$promotion_type->name}}", "category_all"]'
                            style="text-align:center; cursor: default; border:solid rgb(162 220 253);border-radius: 20px;"
                            >
                                <div class="portfolio-item-img info_button" style="border-radius: 10px 10px 0px 0px;" data-promotion_uuid={{ $claim_history->promotion_uuid }}>
                                    <img src="{{ asset('images/promotion_images/'.$claim_history->promotion_uuid.'/'.$claim_history->sub_promotion_uuid.'/show_image/'.$claim_history->promotion_uuid.'.png')}}" alt="promotion_name" title="" />

                                </div>
                                <table class="mt-1 mb-2" style="margin: 0 auto;">
                                    <tr colspan="2"><h4 class="name">{{ $claim_history->sub_promotion->name}} </h4></tr>
                                    <tr>
                                        <td>{{__('new_layout.choosed_qty')}}</td>
                                        <td>:</td>
                                        <td><span style="color: #01a2e6;">{{$claim_history->choose_qty ?? 0}} {{(int)$claim_history->choose_qty > 1 ? 'Tickets' : 'Ticket'}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('new_layout.remain_qty')}}</td>
                                        <td>:</td>
                                        <td><span style="color: #01a2e6;">{{$claim_history->remain_claim_qty ?? 0}} {{(int)$claim_history->remain_claim_qty > 1 ? 'Tickets' : 'Ticket'}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('new_layout.claimed_qty')}}</td>
                                        <td>:</td>
                                        <td><span style="color: #01a2e6;">{{$claim_history->choose_qty - $claim_history->remain_claim_qty ?? 0}} {{(int)($claim_history->choose_qty - $claim_history->remain_claim_qty) > 1 ? 'Tickets' : 'Ticket'}}</span></td>
                                    </tr>
                                </table>
                                <div class="row" style="margin: 0 auto;">
                                    <button class="claim-promotion mr-3 @if ($claim_history->claim_status == 2) choose_active @endif"
                                        @if ($claim_history->claim_status == 2) style="cursor: not-allowed !important; background-color: #fff !important;color:
                                        red !important; border: 0px !important;box-shadow: 0px 0px 0px #fff !important;" @endif
                                    data-uuid={{ $claim_history->uuid }}
                                    data-claim_status={{ $claim_history->claim_status }}
                                    data-sub_promotion_name={{ $claim_history->sub_promotion->name }}
                                    data-prize_check_type='{{ $claim_history->prize_check_type }}'
                                    data-claimed_qty='{{ $claim_history->claimed_qty }}'

                                    data-remain_claim_qty='{{ $claim_history->choose_qty ?? 0 }}'
                                    >@if ($claim_history->claim_status == 2) {{__('new_layout.claimed')}} @else {{__('new_layout.claim')}} @endif </button>
                                </div>
                                <span class="category">{{$promotion_type->name}}</span>
                            </figure>

                            @endif
                        @endforeach
                    @endif
                    @endforeach
                @endforeach
            </div>
            </div>
            <!-- End of Portfolio Content -->
        </div>
        </div>
    </div>
    </section>
    <!-- End of Portfolio Subpage -->
    <div class="modal fade try_grab_the_chance_form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content grab_the_chance_form_content">
                <div class="modal-box grab_the_chance_form_box">
                    <div class="modal-body">
                        <button type="button" class="close"><span id="close_btn"
                                aria-hidden="true">&times;</span></button>
                        <div class="grab_the_chance_form_time">
                            <h4 id="remain_time">4 </h4>
                        </div>
                        <input id="stoped_div" type="hidden" value="0">
                        <input id="winning_div" type="hidden" value="0">
                        <input id="div_length" type="hidden" value="0">
                        <input id="claim_history_uuid" type="hidden" value="0">
                        <input id="remain_claim_qty" type="hidden" value="0">
                        <input id="prize_item_uuid" type="hidden" value="0">
                        <div class="row grab_the_chance_form_image_div" id="grab_the_chance_form_image_div">
                        </div>
                        <div class="col-md-12" id="start_buttons" style="margin-top: 20px;">
                            <button type="button" style="background: #04b4e0; color:white;"
                                id="start_button">{{__('new_promotion.start')}}</button>
                            <button type="button"
                                id="all_start_button">{{__('new_promotion.all_start')}}</button>
                        </div>
                        <div class="col-md-12" id="done_button">
                            <button type="button"
                                id="d_button">{{__('new_promotion.done')}}</button>
                        </div>
                        <div class="claimed_prize_div" id="claimed_prize_div">
                            <h5 id="claimed_prize"> Product Name x 80</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
         $(document).ready(function() {
            $('#done_button').hide();
         });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        $('#close_btn').on('click', function(e){
            location.reload();
        })
        $('#d_button').on('click', function(e){
            location.reload();
        })
        $('.claim-promotion').on('click', function(e) {
            var uuid = jQuery(this).attr("data-uuid");
            var claim_status = jQuery(this).attr("data-claim_status");
            var remain_claim_qty = jQuery(this).attr("data-remain_claim_qty");
            if (claim_status == 1 && remain_claim_qty > 0) {
                    var prize_check_type = jQuery(this).attr("data-prize_check_type");
                    if (prize_check_type == 1) {

                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('message.success') }}",
                            text: `{{ __('message.successfully_created') }}`,
                            timer:1000
                        }).then(function () {
                            let url = `../../new_claim_ticket/{{ $ticket_header_uuid ?? null }}/` + uuid;
                            document.location.href = url;
                        });
                    }
                    if (prize_check_type == 2) {
                        var sub_promotion_name = jQuery(this).attr("data-sub_promotion_name");
                        $('#claim_history_uuid').val(uuid);
                        var token = $("meta[name='csrf-token']").attr("content");
                        $.ajax({
                            url: '../../../get_price_cc_products',
                            type: 'post',
                            data: {
                                "_token": token,
                                "uuid": uuid,
                            },
                            beforeSend: function() {
                                jQuery("#load").fadeOut();
                                jQuery("#loading").show();
                            },
                            complete: function() {
                                jQuery("#loading").hide();
                            },
                            success: function(response) {
                                $('#div_length').val(response.prizeCCCheck.length);
                                $('#grab_the_chance_form_image_div').html("");
                                for (var i = 0; i < response.prizeCCCheck.length; i++) {
                                    $('#grab_the_chance_form_image_div').append(`
                                    <div class="grab_the_chance_form_image" id="` + i + `">
                                        <input id="${response.prizeCCCheck[i].uuid}" type="hidden" value="0">
                                        <img class="sub-promotion-image grab_the_chance_form_image_size" style="margin:0 auto; padding: 5px; width: 200px !important; height: 200px !important;" src="{{ asset('images/promotion_images/${response.promotion_uuid}/${response.sub_promotion_uuid}/promotion_image/${response.prizeCCCheck[i].ticket_image}') }}" />
                                    </div>`)
                                }
                                $('#claimed_prize_div').html("");
                                for (var i = 0; i < response.claim_history_detail.length; i++) {
                                    $('#claimed_prize_div').append(`
                                    <h5>${response.claim_history_detail[i].name} x ${response.claim_history_detail[i].total}</h5>`)
                                }
                                if(response.claim_history.choose_qt != response.claim_history.remain_claim_qty){
                                    $('#remain_time').html('{{__('new_promotion.times')}}:' + response.claim_history.remain_claim_qty)
                                    $('#remain_claim_qty').val(response.claim_history.remain_claim_qty);
                                }
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
                        $('.try_grab_the_chance_form').appendTo("body").modal('show');
                    }
                    if (prize_check_type == 3) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });
                        var token = $("meta[name='csrf-token']").attr("content");
                        var ticket_header_uuid = $('#ticket_header_uuid').val();
                        if (ticket_header_uuid) {
                            $.ajax({
                                url: `../../new_claim_fixed_amount/${ticket_header_uuid}/${uuid}`,
                                type: 'post',
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
                                success: function (response) {
                                    if (response.data != null) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: "{{ __('message.success') }}",
                                            text: `{{ __('message.successfully_created') }}`,
                                            confirmButtonText: "{{ __('message.ok') }}",
                                        }).then(function () {
                                            location.reload();
                                        });
                                    } else {
                                        if (response.error == 'invoice_is_not_format') {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: "{{ __('message.warning') }}",
                                                text: `{{ __('message.invoice_is_not_format') }}`,
                                                confirmButtonText: "{{ __('message.ok') }}",
                                            });
                                        }
                                    }
                                },
                                error: function () {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: "{{ __('message.warning') }}",
                                        text: `{{ __('message.validation_error') }}`,
                                        confirmButtonText: "{{ __('message.ok') }}",
                                    });
                                }
                            });
                        }
                    }
                }
            // Swal.fire({
            //     icon: 'info',
            //     title: "{{ __('message.info') }}",
            //     text: "{{ __('message.want_to_claim') }}",
            //     showCancelButton: true,
            //     cancelButtonText: "{{ __('message.cancel') }}",
            //     confirmButtonText: "{{ __('message.ok') }}"
            // }).then((result) => {
            //     if (claim_status == 1 && remain_claim_qty > 0) {
            //         var prize_check_type = jQuery(this).attr("data-prize_check_type");
            //         if (prize_check_type == 1) {

            //             Swal.fire({
            //                 icon: 'success',
            //                 title: "{{ __('message.success') }}",
            //                 text: `{{ __('message.successfully_created') }}`,
            //                 timer:1000
            //             }).then(function () {
            //                 let url = `../../new_claim_ticket/{{ $ticket_header_uuid ?? null }}/` + uuid;
            //                 document.location.href = url;
            //             });
            //         }
            //         if (prize_check_type == 2) {
            //             var sub_promotion_name = jQuery(this).attr("data-sub_promotion_name");
            //             $('#claim_history_uuid').val(uuid);
            //             var token = $("meta[name='csrf-token']").attr("content");
            //             $.ajax({
            //                 url: '../../../get_price_cc_products',
            //                 type: 'post',
            //                 data: {
            //                     "_token": token,
            //                     "uuid": uuid,
            //                 },
            //                 beforeSend: function() {
            //                     jQuery("#load").fadeOut();
            //                     jQuery("#loading").show();
            //                 },
            //                 complete: function() {
            //                     jQuery("#loading").hide();
            //                 },
            //                 success: function(response) {
            //                     $('#div_length').val(response.prizeCCCheck.length);
            //                     $('#grab_the_chance_form_image_div').html("");
            //                     for (var i = 0; i < response.prizeCCCheck.length; i++) {
            //                         $('#grab_the_chance_form_image_div').append(`
            //                         <div class="grab_the_chance_form_image" id="` + i + `">
            //                             <input id="${response.prizeCCCheck[i].uuid}" type="hidden" value="0">
            //                             <img class="sub-promotion-image grab_the_chance_form_image_size" style="margin:0 auto; padding: 5px; width: 200px !important; height: 200px !important;" src="{{ asset('images/promotion_images/${response.promotion_uuid}/${response.sub_promotion_uuid}/promotion_image/${response.prizeCCCheck[i].ticket_image}') }}" />
            //                         </div>`)
            //                     }
            //                     $('#claimed_prize_div').html("");
            //                     for (var i = 0; i < response.claim_history_detail.length; i++) {
            //                         $('#claimed_prize_div').append(`
            //                         <h5>${response.claim_history_detail[i].name} x ${response.claim_history_detail[i].total}</h5>`)
            //                     }
            //                     if(response.claim_history.choose_qt != response.claim_history.remain_claim_qty){
            //                         $('#remain_time').html('{{__('new_promotion.times')}}:' + response.claim_history.remain_claim_qty)
            //                         $('#remain_claim_qty').val(response.claim_history.remain_claim_qty);
            //                     }
            //                 },
            //                 error: function() {
            //                     Swal.fire({
            //                         icon: 'warning',
            //                         title: "{{ __('message.warning') }}",
            //                         text: `{{ __('message.validation_error') }}`,
            //                         confirmButtonText: "{{ __('message.ok') }}",
            //                     });
            //                 }
            //             });
            //             $('.try_grab_the_chance_form').appendTo("body").modal('show');
            //         }
            //         if (prize_check_type == 3) {
            //             $.ajaxSetup({
            //                 headers: {
            //                     'X-CSRF-TOKEN': "{{ csrf_token() }}"
            //                 }
            //             });
            //             var token = $("meta[name='csrf-token']").attr("content");
            //             var ticket_header_uuid = $('#ticket_header_uuid').val();
            //             if (ticket_header_uuid) {
            //                 $.ajax({
            //                     url: `../../new_claim_fixed_amount/${ticket_header_uuid}/${uuid}`,
            //                     type: 'post',
            //                     data: {
            //                         "_token": token,
            //                     },
            //                     beforeSend: function() {
            //                         jQuery("#load").fadeOut();
            //                         jQuery("#loading").show();
            //                     },
            //                     complete: function() {
            //                         jQuery("#loading").hide();
            //                     },
            //                     success: function (response) {
            //                         if (response.data != null) {
            //                             Swal.fire({
            //                                 icon: 'success',
            //                                 title: "{{ __('message.success') }}",
            //                                 text: `{{ __('message.successfully_created') }}`,
            //                                 confirmButtonText: "{{ __('message.ok') }}",
            //                             }).then(function () {
            //                                 location.reload();
            //                             });
            //                         } else {
            //                             if (response.error == 'invoice_is_not_format') {
            //                                 Swal.fire({
            //                                     icon: 'warning',
            //                                     title: "{{ __('message.warning') }}",
            //                                     text: `{{ __('message.invoice_is_not_format') }}`,
            //                                     confirmButtonText: "{{ __('message.ok') }}",
            //                                 });
            //                             }
            //                         }
            //                     },
            //                     error: function () {
            //                         Swal.fire({
            //                             icon: 'warning',
            //                             title: "{{ __('message.warning') }}",
            //                             text: `{{ __('message.validation_error') }}`,
            //                             confirmButtonText: "{{ __('message.ok') }}",
            //                         });
            //                     }
            //                 });
            //             }
            //         }
            //     }
            // })
        })

        function pickALLPrize(i) {
            const grab_the_chance_form_images = document.querySelectorAll('.grab_the_chance_form_image');
            return grab_the_chance_form_images[i];
            return grab_the_chance_form_images[Math.floor(Math.random() * grab_the_chance_form_images.length)]
        }

        function activePrize(currentPrize) {
            currentPrize.classList.add('grab_active_image')
        }

        function inactivePrize(currentPrize) {
            currentPrize.classList.remove('grab_active_image')
        }
        function stopMusic(){
            var audio = document.getElementById('music');
            audio.pause();
        };
        function randomSelect(winning_number,all_status) {
            var audio = $('#music');
            audio.trigger('pause');
            audio.trigger('play');
            var audio_status = 1;

            const times = 50;
            var i = $('#stoped_div').val();
            var k = $('#div_length').val() - 1;
            const interval = setInterval(() => {

                const currentPrize = pickALLPrize(i);
                activePrize(currentPrize)

                setTimeout(() => {
                    inactivePrize(currentPrize);
                }, 100);
                if (i == k) {
                    i = 0;
                } else {
                    i++;
                }
            }, 100);

            setTimeout(() => {
                clearInterval(interval)
                setTimeout(() => {
                    i = 0;
                    const final_interval = setInterval(() => {
                        var j = winning_number;
                        const currentPrize = pickALLPrize(i);
                        activePrize(currentPrize)

                        if (j != i) {
                            setTimeout(() => {
                                inactivePrize(currentPrize);
                            }, 100);
                            const fcurrentPrize = pickALLPrize(k);
                            inactivePrize(fcurrentPrize);
                        } else {
                            activePrize(currentPrize);
                            $('#stoped_div').val(i);
                            //Save Winning Product
                            var token = $("meta[name='csrf-token']").attr("content");
                            let price_cc_check_uuid = currentPrize.firstElementChild.id;
                            var claim_history_uuid = $('#claim_history_uuid').val();
                            var branch_id = $('#branch_id').val();
                            var prize_item_uuid = $('#prize_item_uuid').val();

                            var remain_claim_qty = $('#remain_claim_qty').val();
                            if(!all_status){
                                $.ajax({
                                    url: '../../../save_claim_detail',
                                    type: 'post',
                                    data: {
                                        "_token": token,
                                        "price_cc_check_uuid": price_cc_check_uuid,
                                        "claim_history_uuid": claim_history_uuid,
                                        "remain_claim_qty": remain_claim_qty,
                                        "branch_id": branch_id,
                                        "prize_item_uuid": prize_item_uuid,
                                    },
                                    beforeSend: function() {
                                        jQuery("#load").fadeOut();
                                        jQuery("#loading").show();
                                    },
                                    complete: function() {
                                        jQuery("#loading").hide();
                                    },
                                    success: function(response) {
                                        $('#claimed_prize_div').html("");
                                        for (var i = 0; i < response.claim_history_detail.length; i++) {
                                            $('#claimed_prize_div').append(`
                                            <h5>${response.claim_history_detail[i].name} x ${response.claim_history_detail[i].total}</h5>`)
                                        }
                                        $('#remain_time').html('{{__('new_promotion.times')}}:' + response.remain_times);
                                        Swal.fire({
                                            icon: 'success',
                                            title: "{{ __('message.you_claimed') }}",
                                            text: response.claimed_name,
                                            confirmButtonText: "{{ __('message.ok') }}",
                                        });
                                        $('#remain_claim_qty').val(response.remain_times);
                                        $('.sub-promotion-qty').html(response.remain_times);
                                        if(response.remain_times == 0){
                                            $('#claim-promotion').className += " choose_active";
                                            $('#start_buttons').hide();
                                            $('#done_button').show();
                                        }

                                        return false;
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
                            }else{

                                $('#claimed_prize_div').show();
                                Swal.fire({
                                    icon: 'success',
                                    title: "{{ __('message.you_claimed_for_all_times') }}",
                                    text: "",
                                    confirmButtonText: "{{ __('message.ok') }}",
                                });
                                $('#done_button').show();
                            }
                            clearInterval(interval)
                            clearInterval(final_interval)
                            if(audio_status == 2){
                                audio.trigger('pause');
                            }
                            return false;
                        }
                        if (i == k) {
                            i = 0;
                        } else {

                            i++;
                        }
                    }, 100);
                }, 100);
                audio_status = 2;
            }, times * 100);

        }


        $('#start_button').on('click', function(e) {
            //Find Winning No
            var token = $("meta[name='csrf-token']").attr("content");
            var uuid = $('#claim_history_uuid').val();
            var remain_claim_qty = $('#remain_claim_qty').val();
            var playing = 1;
            if (remain_claim_qty > 0) {

                $.ajax({
                    url: '../../../get_winning_div',
                    type: 'post',
                    data: {
                        "_token": token,
                        "uuid": uuid,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {

                        //we got uuid
                        if (response == 'all_prize_qty_is_low') {
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.all_prize_qty_is_low') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                        } else {

                            var winning_number = document.getElementById(response).parentElement.id;
                            $('#prize_item_uuid').val(response);
                            //find g_no
                            randomSelect(winning_number,false);
                        }
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
        $('#all_start_button').on('click', function(e) {
            //Find Winning No
            var token = $("meta[name='csrf-token']").attr("content");
            var uuid = $('#claim_history_uuid').val();
            var remain_claim_qty = $('#remain_claim_qty').val();
            var claim_history_uuid = $('#claim_history_uuid').val();
            var branch_id = $('#branch_id').val();
            var playing = 1;
            if (remain_claim_qty > 0) {

                $.ajax({
                    url: '../../../get_winning_div_by_all_start',
                    type: 'post',
                    data: {
                        "_token": token,
                        "uuid": uuid,
                        "remain_qty": remain_claim_qty,
                        "claim_history_uuid": claim_history_uuid,
                        "branch_id": branch_id,
                    },
                    beforeSend: function() {
                        jQuery("#load").fadeOut();
                        jQuery("#loading").show();
                    },
                    complete: function() {
                        jQuery("#loading").hide();
                    },
                    success: function(response) {

                        //we got uuid
                        if (response == 'all_prize_qty_is_low') {
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.all_prize_qty_is_low') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                        } else {
                            $('#claimed_prize_div').hide();
                            var winning_number = document.getElementById(response.winning_number).parentElement.id;
                            $('#prize_item_uuid').val(response.winning_number);
                            //find g_no
                            randomSelect(winning_number,true);

                            $('#claimed_prize_div').html("");
                            for (var i = 0; i < response.detail.claim_history_detail.length; i++) {
                                $('#claimed_prize_div').append(`
                                <h5>${response.detail.claim_history_detail[i].name} x ${response.detail.claim_history_detail[i].total}</h5>`)
                            }
                            $('#remain_time').html('{{__('new_promotion.times')}}:' + response.detail.remain_times);

                            $('#remain_claim_qty').val(response.detail.remain_times);
                            $('.sub-promotion-qty').html(response.detail.remain_times);
                            if(response.detail.remain_times == 0){
                                $('#claim-promotion').className += " choose_active";

                                $('#start_buttons').hide();

                            }
                            return false;
                        }
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
