@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Card Number Generator List</h4>
                    </div>
                </div>
            </div>
            @can('create-installer-card')
            <div class="col-lg-12">
                <a id="createmodal-btn" href="{{ route('cardnumbergenerators.create') }}" class="btn btn-primary document_search mr-2 mb-2">Create New Card</a>
            </div>
            @endcan

            {{-- <div class="col-lg-12 mb-2">
                <form action="{{ route('cardnumbergenerators.search') }}" method="GET">
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
            </div> --}}
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
            <div class="col-lg-12 mb-2">
                <form action="{{ route('cardnumbergenerators.search') }}" method="GET">
                    <div class="row justify-content-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="" class="form-control form-control-sm" name="docno" placeholder="Enter Document No" value="{{ request()->get('docno') }}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="{{ request()->get('from_date') ? 'date' : 'text'  }}" name="from_date" id="from_date" class="from_date form-control form-control-sm" placeholder="From Date: mm/dd/yyyy" onfocus="(this.type='date')" onchange='changeHandler(this)' value="{{ request()->get('from_date')}}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="{{ request()->get('to_date') ? 'date' : 'text'  }}" name="to_date" id="to_date" class="to_date form-control form-control-sm" placeholder="To Date: mm/dd/yyyy" onfocus="(this.type='date')" onchange="changeHandler(this)" value="{{ request()->get('to_date') }}">
                            </div>
                        </div>

                        <div class="col-md-2 mb-md-0 mb-2">
                            <select name="querystatus" id="querystatus" class="form-control form-control-sm" value="{{ request()->get('querystatus')}}">
                                <option value="" selected>Choose Status</option>
                                <option value="pending" {{ request()->get('querystatus') === 'pending' ? "selected" : '' }}>Pending</option>
                                <option value="approved" {{ request()->get('querystatus') === 'approved' ? "selected" : '' }}>Approved</option>
                                <option value="rejected" {{ request()->get('querystatus') === 'rejected' ? "selected" : '' }}>Rejected</option>
                                <option value="exported" {{ request()->get('querystatus') === 'exported' ? "selected" : '' }}>Exported</option>
                            </select>
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
            <div class="col-lg-12 loader-container">
                <div class="rounded mb-3 table-container">
                    <table class="table mb-0 tbl-server-info" id="">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                {{-- @canany(['edit-installer-card', 'delete-installer-card', 'transfer-installer-card']) --}}
                                <th>Action</th>
                                {{-- @endcan --}}
                                <th>Issued Branch</th>
                                <th>Document No.</th>
                                <th>For Branch</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Prepare By</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach($cardnumbergenerators as $idx=>$cardnumbergenerator)
                                <tr>
                                    <td>
                                        {{ $idx + $cardnumbergenerators->firstItem() }}
                                    </td>
                                        {{-- <td><input type="checkbox" name="singlechecks" class="form-check-input singlechecks" value="{{$cardnumbergenerator->id}}" /></td> --}}
                                        {{-- @canany(['edit-installer-card', 'delete-installer-card', 'transfer-installer-card']) --}}
                                        <td class="">
                                            <div class="d-flex justify-content-start">
                                                {{-- @can('edit-installer-card') --}}
                                                    <a href="{{ route('cardnumbergenerators.edit',$cardnumbergenerator->uuid) }}" class="mr-2" title="Edit"><i class="fas fa-edit"></i></a>
                                                {{-- @endcan --}}

                                                {{-- @can('delete-installer-card') --}}
                                                {{-- <form action="{{ route('cardnumbergenerators.destroy',$cardnumbergenerator->card_number) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="text-danger delete-btns" title="Delete"><i class="fas fa-trash"></i></a>
                                                </form> --}}
                                                {{-- @endcan --}}
                                            </div>
                                        </td>
                                        {{-- @endcan --}}

                                        <td>{{ $cardnumbergenerator->branch->branch_name_eng }}</td>
                                        <td>{{ $cardnumbergenerator->document_no }}</td>
                                        <td>{{ $cardnumbergenerator->tobranch->branch_name_eng }}</td>
                                        <td>{{ $cardnumbergenerator->quantity }}</td>
                                        <td>
                                            {!! $cardnumbergenerator->status == "pending" ?"<span class='badge bg-warning'>$cardnumbergenerator->status</span>" :
                                            ($cardnumbergenerator->status == "approved" ? "<span class='badge bg-success'>$cardnumbergenerator->status</span>" :
                                            ($cardnumbergenerator->status == "rejected"? "<span class='badge bg-danger'>$cardnumbergenerator->status</span>" :
                                            ($cardnumbergenerator->status == "exported"? "<span class='badge bg-primary'>$cardnumbergenerator->status</span>" : ""
                                            ))) !!}
                                        </td>
                                        <td>{{ $cardnumbergenerator->prepareby->name  }}</td>
                                        <td>{{  \Carbon\Carbon::parse($cardnumbergenerator->created_at)->format('d-m-Y h:i:s A') }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $cardnumbergenerators->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                {{-- {{ dd($cardnumbergenerators) }} --}}
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
                           <form id="transferform" action="" method="POST">
                                {{ csrf_field() }}
                                <div class="row align-items-end">

                                        <div class="col-md-12 form-group mb-3">
                                            <label for="new_installer_card_card_number">Transfer Type<span class="text-danger">*</span></label>
                                            <select name="transfer_type" id="transfer_type" class="form-control form-control-sm rounded-0">
                                                <option value="" selected disabled>Choose Transfer Type</option>
                                                <option value="change">Change</option>
                                                <option value="lost">Lost</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="old_installer_card_card_number">Old Card Number<span class="text-danger">*</span></label>
                                            <input type="text" name="old_installer_card_card_number" id="old_installer_card_card_number" class="form-control form-control-sm rounded-0" value="{{ old('old_installer_card_card_number') }}" readonly/>
                                        </div>

                                     <div class="col-md-12 form-group mb-3">
                                          <label for="new_installer_card_card_number">New Card Number<span class="text-danger">*</span></label>
                                          <input type="text" name="new_installer_card_card_number" id="new_installer_card_card_number" class="form-control form-control-sm rounded-0" placeholder="Scan New Card" value="{{ old('new_installer_card_card_number') }}" readonly/>
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
                  url:"/cardnumbergeneratorsstatus",
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
            $('#transferform').attr('action',`/cardnumbergenerators/transfer/${getold_installer_card_card_number}`);

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
    });
</script>
@endsection
