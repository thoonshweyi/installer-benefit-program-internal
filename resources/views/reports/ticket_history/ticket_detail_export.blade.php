
<style>
    td,
    th {
        border: 1px solid #000;
    }
</style>

<table class="table table-striped table-hover table-bordered bg-white ">

    <tr height="30">
        <td colspan="20" style="word-wrap: break-word; text-align:center; margin:auto;">Customer Status Number for Promotion Name : {{$promotion->name}} </td>
    </tr>
     <tr>
        <th>Campaign Name</th>
        <th>Day</th>
        <th>Month</th>
        <th>Year</th>
        <th>Branch Code</th>
        <th>Branch Name</th>
        <th>Invoice number</th>
        <th>Customer Name</th>
        <th>Phone number</th>
        <th>Phone number 2</th>
        <th>NRC</th>
        <th>Customer type</th>
        <th>Coupon Number</th>
        <th>Email</th>
        <th>Township</th>
        <th>Region</th>
        <th>Print by</th>
        <th>Print time</th>
        <th>Created by</th>
        <th>Created time</th>
    </tr>
    @foreach ($result as $r)
    <tr class="ligth-data">
        <th scope="col">{{$promotion->name}}</th>
        <th scope="col">{{$r->created_at->format('d')}}</th>
        <th scope="col">{{$r->created_at->format('m')}}</th>
        <th scope="col">{{$r->created_at->format('Y')}}</th>
        <th scope="col">{{$r->ticket_headers->branches->branch_code}}</th>
        <th scope="col">{{$r->ticket_headers->branches->branch_name_eng}}</th>
        <th scope="col">
            @php $invoices = $r->ticket_headers->invoices;
            $minvoice = '';
            foreach ($invoices as $invoice){
                $minvoice .= $invoice->invoice_no .',' ; 
            }
            $minvoice =  rtrim($minvoice, ", ");
            @endphp
            {{$minvoice}}</th>
        <th scope="col">{{$r->ticket_headers->customers->firstname . $r->ticket_headers->customers->lastname}}</th>
        <th scope="col">{{$r->ticket_headers->customers->phone_no}}</th>
        <th scope="col">{{$r->ticket_headers->customers->phone_no_2 ?? ''}}</th>
        <th scope="col">
            @php $nrc_number_name = isset($r->ticket_headers->customers->NRCNumbers) ? $r->ticket_headers->customers->NRCNumbers->nrc_number_name : '';
                $nrc_name = isset($r->ticket_headers->customers->NRCNames) ? $r->ticket_headers->customers->NRCNames->district : '';
                $nrc_naing = isset($r->ticket_headers->customers->NRCNaings) ? $r->ticket_headers->customers->NRCNaings->shortname : '';
                $nrc_number = isset($r->ticket_headers->customers->nrc_number) ? $r->ticket_headers->customers->nrc_number : '';
            @endphp
        {{$nrc_number_name . $nrc_name . $nrc_naing . $nrc_number}}</th>
        <th scope="col">{{$r->ticket_headers->customers->customer_type}}</th>
        <th scope="col">{{$r->ticket_no}}</th>
        <th scope="col">{{$r->ticket_headers->customers->email ?? ''}}</th>
        <th scope="col"> {{ isset($r->ticket_headers->customers->provinces) ? $r->ticket_headers->customers->provinces->province_name : ''}}</th>
        <th scope="col"> {{ isset($r->ticket_headers->customers->amphurs) ? $r->ticket_headers->customers->amphurs->amphur_name : ''}}</th>
        <th scope="col"> {{ isset($r->ticket_headers->printed_users) ? $r->ticket_headers->printed_users->name : ''}}</th>
        <th scope="col">   
            @php
             $printed_at = strtotime($r->ticket_headers->printed_at);
            @endphp
            {{ date('d-m-Y', $printed_at)}}</th>
        <th scope="col"> {{ isset($r->ticket_headers->created_users) ? $r->ticket_headers->created_users->name : ''}}</th>
        <th scope="col"> {{ $r->ticket_headers->created_at->format('d-m-Y')}}</th>
    </tr>
    @endforeach        
                                           
</table>