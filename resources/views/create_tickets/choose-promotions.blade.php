@extends('create_tickets.layout')

@section('content')
    <div class="main-area row col-md-9">
        <div class="column col-md-12">
            <button type="button" class="previous" id="previous_to_check_customer_info">{{__('new_promotion.previous')}}</button>
            <button type="button" id="go_to_claim_prize">{{__('new_promotion.next')}}</button>
            <h4 class="card-second-title">{{__('new_promotion.choose_promotion')}}:</h4>
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
                                    @foreach($promotion->claim_histories as $claim_history)
                                    @if($claim_history->valid_qty != 0)
                                    <div class="sub-promotion-box row choose-promotion
                                        @if($claim_history->choose_status == 2) choose_active @endif"
                                        data-uuid={{$claim_history->uuid}}
                                        data-claim_status= {{ $claim_history->claim_status }}
                                        data-choose_status= {{ $claim_history->choose_status }}

                                        data-sub_promotion_name="{{ $claim_history->sub_promotion->name }}"

                                        data-remain_choose_qty={{ $claim_history->remain_choose_qty }}
                                    >
                                        <div class="sub-promotion-button
                                        @if(strlen($claim_history->sub_promotion->name) > 16)
                                            pedding_top_15px
                                        @endif
                                        ">{{ $claim_history->sub_promotion->name}} </div>
                                        <img class="sub-promotion-image"
                                            src="{{asset('images/promotion_icons/'.$claim_history->promotion_uuid.'/'.$claim_history->sub_promotion_uuid.'/0.png')}}" />
                                        <img class="sub-promotion-image"
                                            src="{{asset('images/promotion_icons/'.$claim_history->promotion_uuid.'/'.$claim_history->sub_promotion_uuid.'/1.png')}}" />
                                        <img class="sub-promotion-image"
                                            src="{{asset('images/promotion_icons/'.$claim_history->promotion_uuid.'/'.$claim_history->sub_promotion_uuid.'/2.png')}}" />
                                        <div class="sub-promotion-qty-button">
                                            <div class="column">
                                                <div class="sub-promotion-qty">{{$claim_history->remain_choose_qty}} </div>
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
    <input type="hidden" id="choose_status" value="">
    <!-- Modal -->
<div class="modal fade choose_sub_promoiton_qty" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
      <button type="button" class="close"><span data-dismiss="modal" aria-hidden="true">&times;</span></button>
        <form id="sub_promotion_form" action="{{ route('tickets.update_claim_record') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="uuid" id="uuid" value="" />

            <input type="hidden" name="ticket_header_uuid" id="ticket_header_uuid" value="{{$ticket_header_uuid}}" />

            <label class="modal-title">{{__('new_promotion.you_choosed_promotion')}} <br> <span id="sub_promotion_name"></span></label><br>

            <input class="modal-input" onkeyup="checkInputNumber()" type="number" name="choose_qty" id="choose_qty" value="" />
            <div class="modal-btns">
                <button type="button" class="modal-btn-secondary" data-dismiss="modal">{{__('new_promotion.close')}}</button>
                <button type="button" id="save_sub_promotion_form" class="modal-btn-primary">{{__('new_promotion.ok')}}</button>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
@section('js')
    <script>
        $('.choose-promotion').on('click', function(e) {
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
        })
        $('#previous_to_check_customer_info').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.customer_info', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
        })
        $('#go_to_claim_prize').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.claim_prize', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
        })
        $('#save_sub_promotion_form').on('click', function(e) {
            $('#sub_promotion_form').submit();
            jQuery("#loading").show();
        })
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
    </script>
@endsection


</html>
