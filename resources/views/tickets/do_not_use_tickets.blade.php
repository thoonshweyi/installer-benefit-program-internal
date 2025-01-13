
<style>
    td,
    th {
        border: 1px solid #000;
    }
</style>

<table class="table table-striped table-hover table-bordered bg-white ">

    <tr height="30">
        <td colspan="20" style="word-wrap: break-word; text-align:center; margin:auto;">Do Not Use Ticket: {{$promotion->name}} </td>
    </tr>
     <tr>
        <th>Promotion Name</th>
        <th>Ticket Header No</th>
        <th>Ticket No</th>
        <th>Customer Name</th>
        <th>Customer Phone No</th>
        <th>Cancel At</th>
        <th>Cancel User</th>
    </tr>
    @foreach ($result as $r)
    <tr class="ligth-data">
        <th scope="col">{{$promotion->name}}</th>
        <th scope="col">{{$r->ticket_headers->ticket_header_no}}</th>
        <th scope="col">{{$r->ticket_no}}</th>
        <th scope="col">{{$r->ticket_headers->customers->firstname . $r->ticket_headers->customers->lastname}}</th>
        <th scope="col">{{$r->ticket_headers->customers->phone_no}}</th>
        <th scope="col">   
            @php
             $canceled_at = strtotime($r->ticket_headers->canceled_at);
            @endphp
            {{ date('d-m-Y', $canceled_at)}}</th>
        <th scope="col"> {{ isset($r->ticket_headers->canceled_users) ? $r->ticket_headers->canceled_users->name : ''}}</th>
    </tr>
    @endforeach        
                                           
</table>