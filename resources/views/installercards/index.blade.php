@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Installer Card List</h4>
                    </div>
                </div>
            </div>
            @can('create-installer-card')
            <div class="col-lg-12">
                <a id="createmodal-btn" href="{{ route('installercards.create') }}" class="btn btn-primary document_search mr-2 mb-2">Create New Card</a>
            </div>
            @endcan
            @can('register-installer-card')
            <div class="col-lg-12">
                <a id="createmodal-btn" href="{{ route('installercards.register') }}" class="btn btn-primary document_search mr-2 mb-2">Register New Card</a>
            </div>
            @endcan
            <div class="col-lg-12 mb-2">
                <form action="{{ route('installercards.search') }}" method="GET">
                    <div class="row justify-content-end">
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="querycard_number" id="inscardnumber" class="form-control form-control-sm" placeholder="Enter Installer Card Number" value="{{ request()->get('querycard_number') }}">
                        </div>
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="querynrc" id="querynrc" class="form-control form-control-sm" placeholder="Enter NRC Number" value="{{ request()->get('querynrc') }}"/>
                        </div>
                        <div class="col-md-2 mb-md-0 mb-2">
                            <input type="text" name="queryphone" id="queryphone" class="form-control form-control-sm" placeholder="Enter Phone Number" value="{{ request()->get('queryphone') }}"/>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="search-btn" class="btn btn-primary rounded">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light" onclick="window.location.href = window.location.href.split('?')[0];"><i class="fas fa-sync-alt"></i></button>
                            @endif
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
            {{-- <div class="col-lg-12 d-flex mb-2">
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.name')}} </label>
                    <input type="text" class="form-control" id="lucky_draw_name" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.start_date')}} </label>
                    <input type="date" class="form-control" id="start_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.end_date')}} </label>
                    <input type="date" class="form-control" id="end_date" value="">
                </div>
                <div class="form-row col-md-2">
                    <label>{{__('lucky_draw.status')}} </label>
                    <select id="lucky_draw_status" class="form-control ">
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="3">Pending</option>
                        <option value="0">All Status</option>
                    </select>
                </div>
                <button id="search" class="btn btn-primary document_search mr-2">{{__('button.search')}}</button>
                @can('export-document-admin')
                <button id="document_export" class="btn btn-success">{{__('button.product_excel_export')}}</button>
                @endcan
            </div> --}}
            <div class="col-lg-12 loader-container">
                <div class="rounded mb-3 table-container">
                    <table class="table mb-0 tbl-server-info" id="">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                @canany(['edit-installer-card', 'delete-installer-card', 'transfer-installer-card'])
                                    <th>Action</th>
                                @endcan
                                <th>Card Number</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Stage</th>
                                <th>Issued Date</th>
                                <th>Issued Branch</th>
                                <th>Issued By</th>

                                @can('edit-installer-card')
                                <th class="text-left">Status</th>
                                @endcan
                                <th>Upated At</th>


                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach($installercards as $idx=>$installercard)
                                <tr>
                                    <td>
                                        {{ $idx + $installercards->firstItem() }}
                                    </td>
                                        {{-- <td><input type="checkbox" name="singlechecks" class="form-check-input singlechecks" value="{{$installercard->id}}" /></td> --}}
                                        @canany(['edit-installer-card', 'delete-installer-card', 'transfer-installer-card'])
                                        <td class="">
                                            <div class="d-flex justify-content-start">
                                                @can('edit-installer-card')
                                                    <a href="{{ route('installercards.edit',$installercard->card_number) }}" class="mr-2" title="Edit"><i class="fas fa-edit"></i></a>
                                                @endcan

                                                @can('transfer-installer-card')
                                                    <a href="javascript:void(0);" class="ml-2 transfer-btns" data-old_installer_card_card_number="{{ $installercard->card_number }}"><i class="fas fa-exchange-alt"></i></a>
                                                @endcan

                                                @can('delete-installer-card')
                                                <form action="{{ route('installercards.destroy',$installercard->card_number) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="text-danger delete-btns" title="Delete"><i class="fas fa-trash"></i></a>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                        @endcan

                                        <td>{{ $installercard->card_number }}
                                            {!! $installercard->stage == 'pending' ? '<i class="fas fa-comment-dots text-warning ml-2"></i>' : '' !!}

                                        </td>
                                        <td>{{ $installercard->fullname }}</td>
                                        <td>{{ $installercard->phone }}</td>
                                        <td>
                                            {!! $installercard->stage == "pending" ?"<span class='badge bg-warning'>$installercard->stage</span>" :
                                            ($installercard->stage == "approved" ? "<span class='badge bg-success'>$installercard->stage</span>" :
                                            ($installercard->stage == "rejected"? "<span class='badge bg-danger'>$installercard->stage</span>" :
                                            ($installercard->stage == "issued"? "<span class='badge bg-secondary'>$installercard->stage</span>" : ""
                                            ))) !!}
                                        </td>
                                        <td>{{  \Carbon\Carbon::parse($installercard->issued_at)->format('d-m-Y h:m:s A') }}</td>
                                        <td>{{ $installercard->branch->branch_name_eng }}</td>
                                        <td>{{ $installercard->user->name }}</td>
                                        @can('edit-installer-card')
                                        <td>
                                            <div class="custom-switch p-0">
                                                <!-- The actual checkbox that controls the switch -->
                                                <input type="checkbox" id="customSwitch-{{ $idx + $installercards->firstItem() }}" class="custom-switch-input statuschange-btn" {{ $installercard->status === 1 ? "checked" : "" }} data-id="{{ $installercard->id }}" data-card_number="{{ $installercard->card_number }}" disabled />
                                                <!-- The label is used to style the switch, and clicking it toggles the checkbox -->
                                                <label class="custom-switch-label" for="customSwitch-{{ $idx + $installercards->firstItem() }}" style="cursor: not-allowed"></label>
                                                <!-- Optional label text next to the switch -->
                                            </div>
                                        </td>
                                        @endcan
                                        <td>{{  \Carbon\Carbon::parse($installercard->updated_at)->format('d-m-Y h:m:s A') }}</td>



                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $installercards->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                {{-- {{ dd($installercards) }} --}}
                <div class="myloader">
                    <div class="loader-item"></div>
                    <div class="loader-item"></div>
                    <div class="loader-item"></div>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>



 <!-- START MODAL AREA -->
          <!-- start edit modal -->
        <div id="transfermodel" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                      <div class="modal-header">
                           <h6 class="modal-title">Transfer Form</h6>
                           <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>

                      <div class="modal-body">
                           <form id="transferform" action="" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row align-items-end">

                                        <div class="col-md-12 form-group mb-3">
                                            <label for="transfer_type">Transfer Type<span class="text-danger">*</span></label>
                                            <select name="transfer_type" id="transfer_type" class="form-control form-control-sm rounded-0">
                                                <option value="" selected disabled>Choose Transfer Type</option>
                                                <option value="change" {{ old("transfer_type") == "change" ? 'selected' : "" }}>Change</option>
                                                <option value="lost" {{ old("transfer_type") == "lost" ? 'selected' : "" }}>Lost</option>
                                            </select>
                                            @error("transfer_type")
                                            <b class="text-danger">{{ $message }}</b>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="old_installer_card_card_number">Old Card Number<span class="text-danger">*</span></label>
                                            <input type="text" name="old_installer_card_card_number" id="old_installer_card_card_number" class="form-control form-control-sm rounded-0" value="{{ old('old_installer_card_card_number') }}" readonly/>
                                            @error("old_installer_card_card_number")
                                            <b class="text-danger">{{ $message }}</b>
                                            @enderror
                                        </div>

                                     <div class="col-md-12 form-group mb-3">
                                          <label for="new_installer_card_card_number">New Card Number<span class="text-danger">*</span></label>
                                          <input type="text" name="new_installer_card_card_number" id="new_installer_card_card_number" class="form-control form-control-sm rounded-0" placeholder="Scan New Card" value="{{ old('new_installer_card_card_number') }}" readonly/>
                                          @error("new_installer_card_card_number")
                                          <b class="text-danger">{{ $message }}</b>
                                          @enderror
                                    </div>

                                     <div class="col-md-12">
                                        <label for="images" class="gallery @error('images') is-invalid @enderror"><span>Choose Images</span></label>
                                        <input type="file" name="images[]" id="images" class="form-control form-control-sm rounded-0" value="" multiple hidden/>
                                        @error("images")
                                            <b class="text-danger">{{ $message }}</b>
                                        @enderror
                                    </div>


                                     <div class="col-md-12 text-sm-end text-start mb-3">
                                          <button type="submit" class="btn btn-primary btn-sm rounded-0">Transfer</button>
                                     </div>
                                </div>
                           </form>
                      </div>

                      <div class="modal-footer">

                      </div>
                 </div>
            </div>
       </div>

  <!-- end edit modal -->
<!-- END MODAL AREA -->

@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-btns').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to remove an installer card?",
                text: "Installer Card will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    {{-- console.log($(this).closest('form')); --}}
                    $(this).closest('form').submit();
                }
              });

        });

        //Start change-btn
        {{-- $(document).on("change",".statuschange-btn",function(){

             var getid = $(this).data("id");
             // console.log(getid);
             const getcard_number = $(this).data("card_number");
             console.log(getcard_number);

             var setstatus = $(this).prop("checked") === true ? 1 : 0;
             console.log(setstatus);

             $.ajax({
                  url:"/installercardsstatus",
                  type:"POST",
                  dataType:"json",
                  data:{
                        "card_number":getcard_number,
                        "status":setstatus,
                        "_token": '{{ csrf_token()}}'
                    },
                  success:function(response){
                       console.log(response); // {success: 'Status Change Successfully'}
                       console.log(response.success); // Status Change Successfully

                       Swal.fire({
                            title: "Updated!",
                            text: "Status Updated Successfully",
                            icon: "success"
                       });
                  },
                  error:function(response){
                    console.log(response);
                  }
             });
        }); --}}
        // End change btn


        {{-- Start Transfer btn --}}
        $('.transfer-btns').click(function(){
            $('#transferform').trigger('reset');
            getold_installer_card_card_number = $(this).data('old_installer_card_card_number');
            {{-- console.log(getold_installer_card_card_number); --}}
            $('#old_installer_card_card_number').val(getold_installer_card_card_number);
            $('#transferform').attr('action',`/installercards/transfer/${getold_installer_card_card_number}`);

            $('#transfermodel').modal('show');
        });
        {{-- End Transfer btn --}}


            {{-- Start Scanner Field --}}
            var lastKeyTime = 0;
            $(document).keypress(function(event) {
                {{-- console.log(event.target); --}}
                if(event.target.name == 'new_installer_card_card_number'){
                    var inputField = $('#new_installer_card_card_number');

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
                            {{-- $( "#check-btn" ).trigger( "click" ); --}}

                            {{-- $('#transferform').submit(); --}}
                        }
                    }
                }

            });
            {{-- End Scanner Field --}}

            $('#transfer_type').change(function(){
                $('#new_installer_card_card_number').focus();
            });


            {{-- Start Preview Image --}}

            var previewimages = function(input,output){

                // console.log(input.files);

                if(input.files){
                     var totalfiles = input.files.length;
                     // console.log(totalfiles);
                     if(totalfiles > 0){
                          $('.gallery').addClass('removetxt');
                     }else{
                          $('.gallery').removeClass('removetxt');
                     }
                     console.log(input.files);

                     for(var i = 0 ; i < totalfiles ; i++){
                          var filereader = new FileReader();


                          filereader.onload = function(e){
                               // $(output).html("");
                               $($.parseHTML('<img>')).attr('src',e.target.result).appendTo(output);
                          }

                          filereader.readAsDataURL(input.files[i]);

                     }
                }

           };

            $('#images').change(function(){
                    previewimages(this,'.gallery');
            });
            {{-- End Preview Image --}}
    });
</script>
@endsection
