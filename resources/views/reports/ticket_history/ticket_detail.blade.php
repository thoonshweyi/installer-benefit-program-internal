@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="mb-3">{{__('report.ticket_detail')}} : {{$ticket->ticket_no}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-lg-8">
                            <div class="table-responsive rounded mb-3">
                                <table class="table table-light">
                                    <thead>
                                        <tr>
                                            <th>{{__('report.promotion_name')}}</th>
                                            <th>{{ $ticket->ticket_headers->promotions->name}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.day')}}</th>
                                            <th>{{ date('d',strtotime($ticket->created_at))}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.month')}}</th>
                                            <th>{{ date('M',strtotime($ticket->created_at))}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.year')}}</th>
                                            <th>{{ date('Y',strtotime($ticket->created_at))}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.branch_code')}}</th>
                                            <th>{{ $ticket->ticket_headers->branches->branch_code}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.branch_name')}}</th>
                                            <th>{{ $ticket->ticket_headers->branches->branch_name_eng}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.invoice_no')}}</th>
                                            <th>
                                            @php $invoices = $ticket->ticket_headers->invoices;
                                                $minvoice = '';
                                                foreach ($invoices as $invoice){
                                                    $minvoice .= $invoice->invoice_no .',' ; 
                                                }
                                                $minvoice =  rtrim($minvoice, ", ");
                                            @endphp
                                            {{ $minvoice}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_name')}}</th>
                                            <th>{{ $ticket->ticket_headers->customers->firstname}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_phone_no')}}</th>
                                            <th>{{ $ticket->ticket_headers->customers->phone_no}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_nrc')}}</th>
                                            <th>{{ $ticket->ticket_headers->customers->phone_no}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_type')}}</th>
                                            <th>{{ $ticket->ticket_headers->customers->customer_type}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.ticket_no')}}</th>
                                            <th>{{ $ticket->ticket_no}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_email')}}</th>
                                            <th>{{ $ticket->ticket_headers->customers->email}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_township')}}</th>
                                            <th>{{ isset($ticket->ticket_headers->customers->amphurs) ? $ticket->ticket_headers->customers->amphurs->amphur_name : 'unknown township'}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.customer_region')}}</th>
                                            <th>{{ isset($ticket->ticket_headers->customers->provinces) ? $ticket->ticket_headers->customers->provinces->province_name : 'unknown division'}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.print_by')}}</th>
                                            <th>{{ isset($ticket->ticket_headers->printed_users) ? $ticket->ticket_headers->printed_users->name : ''}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.print_time')}}</th>
                                            @php
                                            $printed_at = strtotime($ticket->ticket_headers->printed_at);
                                            @endphp
                                            <th>{{ date('d-m-Y', $printed_at)}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.created_by')}}</th>
                                            <th>{{ isset($ticket->ticket_headers->created_users) ? $ticket->ticket_headers->created_users->name : ''}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('report.created_at')}}</th>
                                            <th>{{ isset($ticket->ticket_headers) ? $ticket->ticket_headers->created_at->format('d-m-Y') : ''}}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="pull-right">
                                    <a class="btn btn-light" href="{{ route('report.ticket_history_detail',$ticket->ticket_headers->promotion_uuid) }}"> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
