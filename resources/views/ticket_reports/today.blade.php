@extends('layouts.app')

@section('content')
<div class="content-page">
  <div class="container-fluid">
    {{-- <div class="row">
      <div class="col-lg-12">
        <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
          <div>
            <h4 class="mb-3">{{__('user.users')}}</h4>
          </div>
        </div>
      </div>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      @if (count($errors) > 0)
      <div class="alert alert-danger">
          <strong>Whoops!</strong> There were some problems with your input.<br><br>
          <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif
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
            <label>{{__('user.branch')}} </label>
            <select id="branch_id" class="form-control ">
                <option value="">All Branch</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->branch_id }}" {{ $branch->branch_id == old('document_branch') ? 'selected' : '' }}>
                        {{ $branch->branch_name_eng }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-row col-md-2">
          <label>{{__('user.name')}} </label>
          <input type="input" class="form-control" id="user_name" value="">
        </div>
        <div class="form-row col-md-2">
          <label>{{__('user.emp_id')}} </label>
          <input type="input" class="form-control" id="user_employee_id" value="">
        </div>
        <div class="form-row col-md-2">
          <label>{{__('user.email')}}</label>
          <input type="input" class="form-control" id="user_email" value="">
        </div>
        <div class="form-row col-md-2">
          <label>{{__('user.role')}}</label>
          <select id="user_role" class="form-control ">
            <option value="">All Role</option>
              @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ $role->name == old('user_role') ? 'selected' : '' }}>
                      {{ $role->name }}
                  </option>
              @endforeach
          </select>
        </div>
            <button id="user_search" class="btn btn-primary main_button mr-2">{{__('button.search')}}</button>
            <button id="user_add" class="btn btn-secondary main_button" onclick=location.href="{{ route('users.create') }}">{{__('button.add_new')}}</button>

        <!-- <button id="user_syn" class="btn btn-success document_search mr-2">Syn Member</button> -->
        </div>
    </div> --}}
  </div>
  <div class="col-lg-12">
    <div class="table-responsive rounded mb-3">
      <table class="table mb-0 tbl-server-info" id="user_list">
        <thead class="bg-white text-uppercase">
          <tr class="ligth ligth-data">
            <th>Promotion Name</th>
            <th>Ticket No</th>
            <th>Created At</th>

          </tr>
        </thead>
        <tbody class="ligth-body">
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{$ticket->promotions->name}}</td>
                <td>{{$ticket->ticket_no}}</td>
                <td>{{$ticket->created_at->format('Y-m-d')}}</td>
            </tr>
            @endforeach
        </tbody>
      </table>
      <div class="d-flex">
        {{-- {{$tickets->links();}} --}}

    </div>
      {{-- {{ $tickets->links();}} --}}
    </div>
  </div>
</div>
</div>

@endsection
@section('js')

@stop
