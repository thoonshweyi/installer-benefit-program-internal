@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Installer Criteria Matching</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-2">
                <form id="match-form" action="" method="POST">
                    <div class="row justify-content-start align-items-center">
                        <div class="col-md-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <label for="">Multiple Phone Numbers</label>
                                <button type="button" class="btn btn-primary rounded phoneadd-btns"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="col-md-10"></div>

                        <div class="col-md-2 mb-md-0 mb-2">
                            <div class="multiphones">
                                <div class="d-flex">
                                    <input type="text" name="match_phone[]" id="matchphone1" class="form-control form-control-sm rounded-0 mb-2" placeholder="Enter Primary Number" value=""/>
                                    {{-- <button type="button" class="phone-remove-btn"><i class="fas fa-minus"></i></button> --}}
                                </div>
                            </div>
                        </div>


                        <div class="col-12 mt-4">
                            <button type="button" class="btn btn-primary rounded match-btn">
                                Match
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <hr/>
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



@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        maxphonelimit = 3
        phonecount= 1
        $('.phoneadd-btns').click(function(){
            if(phonecount < maxphonelimit){
                phonecount++;
                $('.multiphones').append(`
                    <div class="d-flex">
                        <input type="text" name="match_phone[]" id="" class="form-control form-control-sm rounded-0 mb-2" placeholder="Enter Secondary Number" value=""/>
                        <button type="button" class="phone-remove-btn"><i class="fas fa-minus"></i></button>
                    </div>
                `);
            }
        });

        $(document).on('click','.phone-remove-btn',function(){
            $(this).parent().remove();
            phonecount--;
        });


        $('.match-btn').click(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers:{
                     "X-CSRF-TOKEN": '{{ csrf_token() }}'
                }
           });
            $.ajax({
                url:"{{ route('installercards.test') }}",
                type:"POST",
                dataType: "json",
                data: $('#match-form').serialize(),
                success:function(response){



                },
                error:function(response){
                    console.log("Error: ",response);
                    Swal.fire({
                        icon: "error",
                        title: "Customer Not Found!!",
                        text: "There is no customer with this phone number",
                    });
                }
            });
        })
    });
</script>
@endsection
