 <!-- Promotion Subpage -->
 <section data-id="summary" class="animated-section">
    <div class="section-content">
        <div class="page-title">
            <h2>{{__('new_promotion.summary')}}</h2>
        </div>
        <p> {{__('new_promotion.summary_description')}}</p>
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
                        @foreach($promotion->claim_histories as $claim_history)
                            @if($claim_history->valid_qty != 0 && $claim_history->claim_status == 2)
                            <figure class="item lbaudio mr-2" data-groups='["{{$promotion_type->name}}", "category_all"]'
                            style="text-align:center; cursor: default; border:solid rgb(162 220 253);border-radius: 20px;"
                            >
                                <div class="portfolio-item-img info_button" style="border-radius: 10px 10px 0px 0px;" data-promotion_uuid={{ $claim_history->promotion_uuid }}>
                                    <img src="{{ asset('images/promotion_images/'.$claim_history->promotion_uuid.'/'.$claim_history->sub_promotion_uuid.'/show_image/'.$claim_history->promotion_uuid.'.png')}}" alt="promotion_name" title="" />

                                </div>
                                <table class="mt-1 mb-2"style="margin: 0 auto;">
                                    <tr colspan="2"><h4 class="name">{{$promotion->name}} </h4></tr>

                                    @php $summary_controller = new  App\Http\Controllers\CreateTicket\SummaryController();
                                    $product_details = $summary_controller->search_claim_history_product_detail($claim_history->uuid);
                                @endphp
                                @foreach ($product_details as $product_detail)
                                    <tr>
                                        <td>{{ $product_detail['name'] }}</td>
                                        <td>x</td>
                                        <td><span style="color: #01a2e6;">{{ $product_detail['qty'] }}</span> {{(int)$product_detail['qty'] > 1 ? 'Tickets' : 'Ticket'}}</td>
                                    </tr>
                                @endforeach
                                </table>
                                <!-- <div class="row">
                                    <button class="print-promotion mr-3"
                                    @if ($claim_history->print_status == 2) choose_active @endif
                                        data-uuid={{ $claim_history->uuid }}
                                        data-print_status={{ $claim_history->print_status ? $claim_history->print_status : 1 }}
                                        data-remain_qty={{ $claim_history->choose_qty }} style="margin: 0 auto;"
                                    >@if ($claim_history->print_status == 2) {{__('new_layout.printed')}} @else {{__('new_layout.print')}}@endif </button>
                                </div>
                                @if (($claim_history->claim_status == 2) && ($claim_history->print_status == 1 || $claim_history->print_status == null))
                                    <iframe src={{ asset('tickets/' . $claim_history->uuid . '.pdf') }}
                                        id="{{ $claim_history->uuid }}" width="800" height="1200"
                                        style="position: absolute;width:0;height:0;border:0;"
                                        ></iframe>
                                @endif
                                <span class="category">{{$promotion_type->name}}</span> -->
                                <div class="row">
                                    <button class="print-promotion mr-3"
                                    @if ($claim_history->print_status == 2) choose_active @endif
                                        data-uuid={{ $claim_history->uuid }}
                                        data-print_status={{ $claim_history->print_status ? $claim_history->print_status : 1 }}
                                        data-remain_qty={{ $claim_history->choose_qty }} style="margin: 0 auto;"
                                    >@if ($claim_history->print_status == 2) {{__('new_layout.printed')}} @else {{__('new_layout.print')}} @endif </button>
                                </div>
                                @if (($claim_history->claim_status == 2) && ($claim_history->print_status == 1 || $claim_history->print_status == null))
                                    <iframe src={{ asset('tickets/' . $claim_history->uuid . '.pdf') }}
                                        id="{{ $claim_history->uuid }}" width="800" height="1200"
                                        style="position: absolute;width:0;height:0;border:0;"
                                        ></iframe>
                                @endif
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
    <script>
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
    </script>
