@extends('layouts.app')

@section('content')
<div class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
          <div>
            <h4 class="mb-3">{{__('customer.customers')}}</h4>
          </div>
        </div>
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
      <div class="col-lg-12 d-flex mb-4">
        <div class="form-row col-md-2">
          <label>{{__('customer.name')}} </label>
          <input type="input" class="form-control" id="customer_name" value="">
        </div>
        <div class="form-row col-md-1">
          <label>{{__('customer.phone_no')}} </label>
          <input type="input" class="form-control" id="customer_phone_no" value="">
        </div>
            <button id="customer_search" class="btn btn-primary main_button mr-2">{{__('button.search')}}</button>
            <!-- <button id="user_add" class="btn btn-secondary main_button" onclick=location.href="{{ route('customers.create') }}">{{__('button.add_new')}}</button> -->

        <!-- <button id="user_syn" class="btn btn-success document_search mr-2">Syn Member</button> -->
        </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="table-responsive rounded mb-3">
      <table class="table mb-0 tbl-server-info" id="customer_list">
        <thead class="bg-white text-uppercase">
          <tr class="ligth ligth-data">
            <th>{{__('customer.name')}}</th>
            <th>{{__('customer.phone_no')}}</th>
            <th>{{__('customer.email')}}</th>
            <th>{{__('customer.action')}}</th>
          </tr>
        </thead>
        <tbody class="ligth-body">
            @foreach ($customers as $customer)
            <th>{{ $customer->id }}</th>
            <th></th>
            {{-- <th>{{__('customer.email')}}</th>
            <th>{{__('customer.action')}}</th> --}}
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

@endsection
@section('js')
<script>

</script>
@stop
