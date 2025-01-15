@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid security-areas">
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <h4 class="mb-3">Installer Checking</h4>
                </div>
                <form action="" method="">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-info">Scan Installer Card</h5>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" name="inscardnumber" id="inscardnumber" class="form-control inscardnumber"  readonly/>
                        </div>
                        <button type="button" id="check-btn" class="btn btn-primary document_search mr-2" style="opacity: 0">Check</button>
                    </div>
                </form>
            </div>
            @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif


        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>


<!-- START MODAL AREA -->
    <!-- start create modal -->
    <div id="showmodal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h6 class="modal-title">Installer Card Modal</h6>
                        <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                            <h1 class="text-center" id="foundinscardnumber"></h1>
                              <h5 class="card-title">Installer Information</h5>
                              <ul class="list-group list-group-flush">

                              </ul>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="#" id="verifiedbtn" class="btn btn-success text-right">Verified</a>
                        </div>
                    </div>

                    <div class="modal-footer">

                    </div>
                </div>
        </div>
    </div>
    <!-- end create modal -->

<!-- END MODAL AREA -->
@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $("#check-btn").click(function(){
            {{-- console.log("hay"); --}}
            const inscardnumber = $("#inscardnumber").val();

            $.ajax({
                url:"{{route('installercards.check')}}",
                method:"GET",
                data:{"inscardnumber":inscardnumber},
                success:function(response){
                     console.log(response);

                     if(response.installercard){
                        {{-- console.log('found'); --}}
                        {{-- let prevmonths_sale_amount_str = $('#prevmonths_sale_amount').val() || '0';
                        let prevmonths_sale_amount = parseFloat(prevmonths_sale_amount_str.replace(/,/g, ''));
                        console.log(prevmonths_sale_amount); --}}

                        let htmlview="";
                        htmlview = `
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Name:</strong>
                                <span>${response.installercard.fullname}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Phone:</strong>
                                <span>${response.installercard.phone}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>NRC:</strong>
                                <span>${response.installercard.nrc}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Customer Type: </strong>
                                <span> ${response.installercard.identification_card != null && response.installercard.member_active && response.installercard.customer_active && response.installercard.customer_rank_id == 1013  ? "Member" : "Old"}</span>


                            </li>
                            {{-- <li class="list-group-item d-flex justify-content-between">
                                <strong>Identification Card:</strong>
                                <span>${response.installercard.identification_card}</span>
                            </li> --}}
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Total Point:</strong>
                                <span>${response.installercard.totalpoints}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Total Amount:</strong>
                                <span>${Number(response.installercard.totalamount).toLocaleString()} MMK</span>
                            </li>

                        `;
                        $("#showmodal .modal-body ul.list-group").html(htmlview);
                        $("#verifiedbtn").attr('href',`{{ url('/installercardpoints/detail') }}/${response.installercard.card_number}`)
                        $(foundinscardnumber).text(response.installercard.card_number);
                        $("#showmodal").modal('show');
                     }else{
                        {{-- console.log('Not found'); --}}
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: "error"
                        });
                     }
                },
                error:function(response){
                    console.log(response);
                }
            });

        });

        $("#verifiedbtn").click(function(e){
            {{-- sessionStorage.setItem('cardlock', 'open') --}}
            e.preventDefault();
            $.ajax({
                url: "{{route('installercards.storecardlock')}}",  //
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"  // CSRF token for Laravel
                },
                success: function(response) {
                    console.log('Data sent to server successfully');
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });

            window.location.href = $(this).attr('href');
        });


        var inputField = $('#inscardnumber');
        var lastKeyTime = 0;
        $(document).keypress(function(event) {

            // Check if the input is readonly and prevent manual typing
            if (inputField.prop('readonly')) {
                // Append the scanned character to the input field value
                if (event.key !== 'Enter') {

                    var currentTime = new Date().getTime();
                    if(inputField.val() != '' && !(currentTime - lastKeyTime <= 50)){
                        inputField.val('');
                    }

                    if (currentTime - lastKeyTime <= 50 || inputField.val() === '') {
                        inputField.val(inputField.val() + event.key);
                    } else {
                        inputField.val('');
                    }
                    lastKeyTime = currentTime;
                }

                // Prevent form submission when 'Enter' key is pressed by the scanner
                if (event.key === 'Enter') {
                    event.preventDefault();  // Prevent form submission

                    console.log('Scanned QR Code:', inputField.val());
                    $( "#check-btn" ).trigger( "click" );
                }
            }
        });



        $('#showmodal').on('hide.bs.modal', function (e) {
            $('#inscardnumber').val('');
        });

    });

</script>
@endsection
