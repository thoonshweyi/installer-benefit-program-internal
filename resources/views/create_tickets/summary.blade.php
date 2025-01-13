@extends('create_tickets.layout')

@section('content')
    <div class="main-area row col-md-9">
        <div class="column col-md-12">
            <button type="button" class="previous" id="previous_to_claim_prize">{{__('new_promotion.previous')}}</button>
            <h4 class="card-second-title">{{__('new_promotion.summary')}} :</h4>
            @foreach($promotion_types as $promotion_type)
            @if($promotion_type->promotions->count() > 0)
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
                                    <div class="sub-promotion-box row print-promotion
                                        @if ($claim_history->print_status == 2) choose_active @endif"
                                        data-uuid={{ $claim_history->uuid }}
                                        data-print_status={{ $claim_history->print_status ? $claim_history->print_status : 1 }}
                                        data-remain_qty={{ $claim_history->choose_qty }}
                                        >
                                        <div class="sub-promotion-button
                                        @if(strlen($claim_history->sub_promotion->name) > 16)
                                            pedding_top_15px
                                        @endif
                                        ">{{ $claim_history->sub_promotion->name}}
                                        </div>
                                        <div class="summary_product">
                                            @php $summary_controller = new  App\Http\Controllers\CreateTicket\SummaryController();
                                                $product_details = $summary_controller->search_claim_history_product_detail($claim_history->uuid);
                                            @endphp
                                            @foreach ($product_details as $product_detail)

                                            <h5>{{ $product_detail['name'] }} x {{ $product_detail['qty'] }}<h5>
                                            @endforeach
                                        </div>

                                        <div class="sub-promotion-qty-button">
                                                <div class="sub-promotion-qty">
                                                    {{ $claim_history->choose_qty }}
                                                </div>
                                        </div>
                                        <div class="row" class='ml-100'>
                                            <div class="col-md-12">
                                                @if ($claim_history->print_status == 1 || $claim_history->print_status == null)
                                                    <iframe src={{ asset('tickets/' . $claim_history->uuid . '.pdf') }}
                                                        id="{{ $claim_history->uuid }}" width="800" height="1200"
                                                        style="position: absolute;width:0;height:0;border:0;"
                                                        ></iframe>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    
                            @endforeach
                            </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                @php $i++ @endphp
                @endif
                @endforeach

        </div>
    </div>
@endsection
@section('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}" }
        });
        $('.print-promotion').on('click', function(e) {

            var uuid = jQuery(this).attr("data-uuid");
            var print_status = jQuery(this).attr("data-print_status");
            var remain_qty = jQuery(this).attr("data-remain_qty");

            if (print_status == 1 && remain_qty > 0) {
                var frame = document.getElementById(uuid);
                frame.contentWindow.focus();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    url: '../new_remove_ticket_file',
                    type: 'get',
                    data: {
                        "claim_history_uuid": uuid,
                    },
                    success: function(response) {
                        // var url =
                        //     `/ticket/summary/${response}/edit`;
                        // window.location = url;
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: "{{ __('message.warning') }}",
                            text: "{{ __('message.validation_error') }}",
                            confirmButtonText: "{{ __('message.ok') }}",
                        });
                    }
                });

                frame.contentWindow.print();
                this.classList.add("choose_active");
                $(this).attr("data-print_status", 2);
            }
        })

        $('#previous_to_claim_prize').on('click', function(e) {
            jQuery("#loading").show();
            let url = "{{ route('tickets.claim_prize', $ticket_header_uuid ?? '') }}";
            document.location.href = url;
        })
        $('#print_pdf').on('click', function(e) {
            window.print();
        })


    </script>
@endsection

</html>
