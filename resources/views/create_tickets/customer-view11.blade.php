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
                <button href="#" class="iq-sub-card" id="ip_address_link">192.168.5.96</button>

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
        var ip_address = $('#ip_address').val();
        alert(ip_address);
        if (ip_address != "") {
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
                        var loop_status;
                        if(route_name){
                            document.getElementById('ip_iframe').style.display = "block";
                            if(current_route_name !== route_name){
                                document.getElementById('ip_iframe').src = route_name;
                                document.getElementById('current_route_name').value =route_name;
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
                            getIPAddress(); //this will send request again and again in every 5s;
                        }, 5000);
                    }
                }
            });
        } else {
            alert('Please fill all the field !');[]
        }
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
                                    <strong>IP Address <span class="require_field">*</sapn></strong>
                                    <input type="text" name="ip_address" id="ip_address" class="form-control"
                                        data-errors="Please Enter Product Code." value="192.168.5.96" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="ip_address_save">Save</button>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>
