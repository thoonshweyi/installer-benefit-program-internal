<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRO 1 : Lucky Draw System</title>
    <link rel="stylesheet" href="{{ asset('css/backend-plugin.min.css') }}">
    @include('sweetalert::alert')
</head>
<body>
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Customer View</h4>
                </div>
                <div class="header-title mr-4" >
                <button href="#" class="iq-sub-card" id="ip_address_link">Select IP</button>
                <a class="iq-sub-card" href="{{ route('home') }}" ><img src="" class="mr-2">Back</a>
            </div>
        </div>
    </div>
    <input type="hidden" id="current_route_name" value="">
    <iframe  id="ip_iframe" src="" width="100%" target="content"
        height="750px">
    </iframe>

    <script src="{{ asset('js/backend-bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/backend-plugin.min.css') }}">
    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    var keepGoing = true;

    function getIPAddress(){
        var ip_address = $('#no').val();

            $.ajax({
                url: "/route_view",
                type: "POST",
                data: {
                    _token: $("#csrf").val(),
                    ip_address: ip_address,
                },
                beforeSend: function() {
                    jQuery("#load").fadeOut();
                    jQuery("#loading").show();
                },
                complete: function() {
                    jQuery("#loading").hide();
                },
                cache: false,
                success: function(response) {
                    if(keepGoing){
                        var current_route_name =  $('#current_route_name').val();
                        var route_name = response.route_name;
                        if(route_name){
                            document.getElementById('ip_iframe').style.display = "block";
                            if(current_route_name !== route_name ){
                                document.getElementById('ip_iframe').src = route_name;
                                document.getElementById('current_route_name').value = route_name;
                            }
                        }else{
                            document.getElementById('ip_iframe').style.display = "none";
                            Swal.fire({
                                icon: 'warning',
                                title: "{{ __('message.warning') }}",
                                text: `{{ __('message.validation_error') }}`,
                                confirmButtonText: "{{ __('message.ok') }}",
                            });
                            keepGoing = false;
                        }

                        $('.ip_address').modal('hide');
                        oneTimeout = setTimeout(function(){
                            getIPAddress(); //this will send request again and again in every 3s;
                        }, 3000);
                    }
                }
            });

    }
    $(document).ready(function() {
        getIPAddress();
        $(document).on("click", "#ip_address_link", function() {
            var ip_link = this.textContent || this.innerText;
            $('#ip_address').val(ip_link);
            $('.ip_address').modal('show');
            keepGoing = false;
        });
        $('#ip_address_save').on('click', function() {
            keepGoing = true;
            $('#ip_address_link').html($('#ip_address').val());
            getIPAddress();
        });

    });

    $(document).on('change', "#no", function() {
        getIPAddress();
        keepGoing = true;
     });

    </script>
    <div class="modal fade ip_address" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tool_modal_title"> Update IP Address </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>No <span class="require_field">*</sapn></strong>
                                    <select class="form-control" name="no" id="no"
                                            class="selectpicker form-control" required focus>
                                            <option value="">Select No</option>
                                @foreach ($check_ip_addresses as $check_ip_address )
                                    <option value="{{ $check_ip_address->ip_address }}" data-id="{{ $check_ip_address->ip_address }}"
                                        {{ $check_ip_address->no == old('no') ? 'selected' : '' }}>
                                        {{ $check_ip_address->no }}
                                    </option>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
