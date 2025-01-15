
<style>
    td,
    th {
        border: 1px solid #000;
    }
</style>

<table class="table table-striped table-hover table-bordered bg-white ">

    <tr height="30">
        <td colspan="13" style="word-wrap: break-word; text-align:center; margin:auto; font-size:25px;">Cash Coupon Issued Data : {{ $promotion_name->name }}</td>
    </tr>
     <tr style="font-weight: bold; font-size:15px;">
        <th scope="col" style="font-weight: bold; font-size:15px;"> No </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Branch </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Date </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Code </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Name </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Customer Code </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Customer Name </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Qty </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Coupon Amt </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Total Amount </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> For Sale Invoice No</th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Promotion Date </th>
        <th scope="col" style="font-weight: bold; font-size:15px;"> Promotion Name </th>
    </tr>
    @php $i = 1; @endphp
    @foreach ($result as $r)
    <tr class="ligth-data">
        <th scope="col" style="width:40%;">{{ $i++ }}</th>
        <th scope="col" style="width:80%;">{{ $r->branches->branch_name_eng }}</th>
        <th scope="col" style="width:80%;">{{ $r->created_at }}</th>
        <th scope="col" style="width:200%;">{{ $r->prize_date }}</th>
        <th scope="col" style="width:200%;">'{{ $r->prize_code }}'</th>
        <th scope="col" style="width:200%;">{{ $r->customers->customer_no }}</th>
        <th scope="col" style="width:200%;">{{ $r->customers->firstname }}</th>
        <th scope="col" style="width:80%;">{{ $r->prize_qty }}</th>
        <th scope="col" style="width:100%;">{{ $r->prize_amount }}</th>
        <th scope="col" style="width:100%;">{{ $r->total_amount }}</th>
        <th scope="col" style="width:250%;">{{ $r->ticket_header_invoice->invoice_no }}</th>
        <th scope="col" style="width:350%;">{{ $r->promotions->start_date }}-{{ $r->promotions->end_date }}</th>
        <th scope="col" style="width:350%;">{{ $r->promotions->name }}</th>
    </tr>
    @endforeach

</table>
