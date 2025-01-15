 <!-- Promotion Subpage -->
 <section data-id="available_promotions" class="animated-section">
    <div class="section-content">
        <div class="page-title">
            <h2>{{__('new_promotion.available_promotions')}}</h2>
        </div>
        <p> {{__('new_promotion.available_promotions_description')}}</p>
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
           
            <!-- Portfolio{} Grid -->
            <div class="portfolio-grid three-columns">
                @foreach($promotion_types as $promotion_type)
                    @foreach($promotion_type->promotions as $promotion)
                        @foreach($promotion->claim_histories as $claim_history)
                            @if($claim_history->valid_qty != 0)
                            <figure class="item lbaudio mr-1" data-groups='["{{$promotion_type->name}}", "category_all"]'
                            style="text-align:center; cursor: default; border:solid rgb(162 220 253);border-radius: 20px;"
                            >
                                <div class="portfolio-item-img info_button" style="border-radius: 10px 10px 0px 0px;" data-promotion_uuid={{ $claim_history->promotion_uuid }}>
                                    <img src="{{ asset('images/promotion_images/'.$claim_history->promotion_uuid.'/'.$claim_history->sub_promotion_uuid.'/show_image/'.$claim_history->promotion_uuid.'.png')}}" alt="promotion_name" title=""

                                    />

                                </div>
                                <table class="mt-1 mb-2" style="margin: 0 auto;">
                                    <tr colspan="2"><h4 class="name">{{$claim_history->sub_promotion->name}} </h4></tr>
                                    <tr>
                                        <td>{{__('new_layout.valid_qty')}}</td>
                                        <td>:</td>
                                        <td><span style="color: #01a2e6;">{{$claim_history->valid_qty ?? 0}} {{(int)$claim_history->valid_qty > 1 ? 'Tickets' : 'Ticket'}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('new_layout.remain_qty')}}</td>
                                        <td>:</td>
                                        <td><span style="color: #01a2e6;">{{$claim_history->remain_choose_qty ?? 0}} {{(int)$claim_history->remain_choose_qty > 1 ? 'Tickets' : 'Ticket'}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('new_layout.choosed_qty')}}</td>
                                        <td>:</td>
                                        <td><span style="color: #01a2e6;">{{$claim_history->choose_qty ?? 0}} {{(int)$claim_history->choose_qty > 1 ? 'Tickets' : 'Ticket'}}</span></td>
                                    </tr>
                                </table>
                                <div class="row" style="margin: 0 auto;">
                                    <button class="choose_promotion mr-3 @if($claim_history->choose_status == 2) choose_active @endif "
                                        @if($claim_history->choose_status == 2) style="cursor: not-allowed !important; background-color: #fff !important;color:
                                         red !important; border: 0px !important;box-shadow: 0px 0px 0px #fff !important;" @endif

                                                    data-uuid={{$claim_history->uuid}}
                                                    data-claim_status= {{ $claim_history->claim_status }}
                                                    data-choose_status= {{ $claim_history->choose_status }}

                                                    data-sub_promotion_name="{{ $claim_history->sub_promotion->name }}"

                                                    data-remain_choose_qty={{ $claim_history->remain_choose_qty }}
                                    >
                                    @if($claim_history->choose_status == 2) {{__('new_layout.choosed')}} @else {{__('new_layout.choose')}}  @endif</button>
                                </div>
                                <span class="category">{{$promotion_type->name}}</span>
                            </figure>
                            @endif
                        @endforeach
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

    <input type="hidden" id="choose_status" value="">
    <!-- Modal -->
    <div class="modal fade choose_sub_promoiton_qty" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-body">
            <button type="button" class="close"><span data-dismiss="modal" aria-hidden="true"> <i class="fa fa-times"></i></span></button>
                <form id="sub_promotion_form" action="{{ route('update_claim_record') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uuid" id="uuid" value="" />

                    <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{$ticket_header_uuid ?? ''}}" />

                    <label class="modal-title">{{__('new_promotion.you_choosed_promotion')}} <br> <span id="sub_promotion_name"></span></label><br>
                    <div style="margin-top: 20px;">
                        <input class="modal-input keyboardType1 jQKeyboard" onkeyup="checkInputNumber()" type="text" name="choose_qty" id="choose_qty" value="" style="width: 200px;text-align: center;margin: 0 auto;padding: 10p;"/>
                    </div>
                    <div class="modal-btns">
                        <button type="button" class="btn-secondary" data-dismiss="modal">{{__('new_promotion.close')}}</button>
                        <button type="button" id="save_sub_promotion_form">{{__('new_promotion.ok')}}</button>
                    </div>
                </form>
            </div>

            </div>
        </div>
    </div>
<script>
$(document).ready(function() {
    $(document).on("click", ".choose_promotion", function () {
        var uuid = jQuery(this).attr("data-uuid");
        var sub_promotion_name = jQuery(this).attr("data-sub_promotion_name");
        var choose_status = jQuery(this).attr("data-choose_status");
        var claim_status = jQuery(this).attr("data-claim_status");
        var remain_choose_qty = jQuery(this).attr("data-remain_choose_qty");
        $('#sub_promotion_name').text('');
        $('#choose_qty').val('');
        if(claim_status == 1 && remain_choose_qty > 0){
            $('#uuid').val(uuid);
            $('#sub_promotion_name').text(sub_promotion_name);
            $('#choose_qty').val(remain_choose_qty);
            $("#choose_qty").attr({
                "max" : remain_choose_qty,
                "min" : 0
                });
            $('.choose_sub_promoiton_qty').appendTo("body").modal('show');
        }
    });
    cqtime = 0;
    $(document).on("click", "#choose_qty", function (){
        $('input.keyboardType1').initKeypad({'keyboardType': '1'});
        $('.onScreen').show();
        if(cqtime == 1){
            $('#choose_qty').addClass("focus");
            cqtime = 0;
        }else{
            $('#choose_qty').removeClass("focus");
            cqtime++;
        }
    })

    $('#save_sub_promotion_form').on('click', function(e) {
        max_value = document.getElementById("choose_qty").max;
        if(parseInt(max_value) < parseInt($('#choose_qty').val())){
            Swal.fire({
                icon: 'warning',
                title: "{{ __('message.warning') }}",
                text: `{{ __('message.unacceptable_qty') }}`,
                confirmButtonText: "{{ __('message.ok') }}",

            });
        }
        else{
            $('#sub_promotion_form').submit();
            jQuery("#loading").show();
        }

    });

})
</script>
