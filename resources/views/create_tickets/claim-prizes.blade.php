@extends('create_tickets.layout')

@section('content')
    <div class="main-area row col-md-9">
        <div class="column col-md-12">
            <button type="button" class="previous" id="previous_to_choose_promotion">{{__('new_promotion.previous')}}</button>
            <button type="button" id="go_to_summary">{{__('new_promotion.next')}}</button>
            <h4 class="card-second-title">{{__('new_promotion.claim_prize')}}:</h4>
            <audio src="{{ asset('music/30Seconds_ForGrabTheChance_v2.mp3') }}" id="music" class="hidden"></audio>
            <input id="branch_id" type="hidden" value="{{ $branch_id }}">
            @foreach($promotion_types as $promotion_type)
            <div class="promotion-box">
                <div class="row">
                    @php $i = 0 @endphp
                    <div class=" col-md-12">
                    <h4 class="choose-promotion-label card-label-small">
                        @if($i==0)
                        {{$promotion_type->name}}
                        @else
                        {{$promotion_type->name}}
                        @endif
                    </h4>
                    </div>
                    @foreach($promotion_type->promotions as $promotion)
                    @if($promotion->claim_histories->count() > 0)
                        <div class="row mr-3 choose-promotion-box">
                            @foreach ($promotion->claim_histories as $claim_history)
                            @if($claim_history->valid_qty != 0)
                                <div id="claim-promotion" class="sub-promotion-box row claim-promotion
                                @if ($claim_history->claim_status == 2) choose_active @endif"
                                    data-uuid={{ $claim_history->uuid }}
                                    data-claim_status={{ $claim_history->claim_status }}
                                    data-sub_promotion_name={{ $claim_history->sub_promotion->name }}
                                    data-prize_check_type='{{ $claim_history->prize_check_type }}'
                                    data-claimed_qty='{{ $claim_history->claimed_qty }}'

                                    data-remain_claim_qty='{{ $claim_history->choose_qty ?? 0 }}'
                                    >
                                    <div class="sub-promotion-button
                                    @if(strlen($claim_history->sub_promotion->name) > 16)
                                        pedding_top_15px
                                    @endif
                                    ">{{ $claim_history->sub_promotion->name}} </div>
                                    <img class="sub-promotion-image"
                                        src="{{ asset('images/promotion_icons/' . $claim_history->promotion_uuid . '/' . $claim_history->sub_promotion_uuid . '/0.png') }}" />
                                    <img class="sub-promotion-image"
                                        src="{{ asset('images/promotion_icons/' . $claim_history->promotion_uuid . '/' . $claim_history->sub_promotion_uuid . '/1.png') }}" />
                                    <img class="sub-promotion-image"
                                        src="{{ asset('images/promotion_icons/' . $claim_history->promotion_uuid . '/' . $claim_history->sub_promotion_uuid . '/2.png') }}" />
                                    <div class="sub-promotion-qty-button">
                                        <div class="column">
                                            <div class="sub-promotion-qty">{{ $claim_history->choose_qty ?? 0}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                                </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @php $i++ @endphp
            @endforeach
        </div>
    </div>
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
                        <div class="col-md-12" id="start_buttons">
                            <button type="button" class="modal-btn-primary grab_the_chance_form_start_button"
                                id="start_button">{{__('new_promotion.start')}}</button>
                            <button type="button" class="modal-btn-success grab_the_chance_form_start_button"
                                id="all_start_button">{{__('new_promotion.all_start')}}</button>
                        </div>
                        <div class="col-md-12" id="done_button">
                            <button type="button" class="modal-btn-primary grab_the_chance_form_start_button"
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
@endsection
@section('js')
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
                    let url = `../../claim_ticket/{{ $ticket_header_uuid }}/` + uuid;
                    document.location.href = url;
                }
                if (prize_check_type == 2) {
                    var sub_promotion_name = jQuery(this).attr("data-sub_promotion_name");
                    $('#claim_history_uuid').val(uuid);
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: '../../../tickets/get_price_cc_products',
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
                                    <img class="sub-promotion-image grab_the_chance_form_image_size" style="width: 200px !important; height: 100px !important;" src="{{ asset('images/prize_items/${response.prizeCCCheck[i].ticket_image}') }}" />
                                </div>`)
                            }
                            $('#claimed_prize_div').html("");
                            for (var i = 0; i < response.claim_history_detail.length; i++) {
                                $('#claimed_prize_div').append(`
                                <h5>${response.claim_history_detail[i].name} x ${response.claim_history_detail[i].total}</h5>`)
                            }
                            if(response.claim_history.choose_qt != response.claim_history.remain_claim_qty){
                                $('#remain_time').html('{{__('new_promotion.times')}}:' + response.claim_history.choose_qty)
                                $('#remain_claim_qty').val(response.claim_history.choose_qty);
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
                    let url = `../../claim_fixed_amount/{{ $ticket_header_uuid }}/` + uuid;
                    document.location.href = url;
                }
            }
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
                                    url: '../../../tickets/save_claim_detail',
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
                    url: '../../../tickets/get_winning_div',
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
                    url: '../../../tickets/get_winning_div_by_all_start',
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
        $('#previous_to_choose_promotion').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.choose_promotion', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
        })
        $('#go_to_summary').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.summary', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
        })
    </script>
@endsection


</html>
