
<style>
    td,
    th {
        border: 1px solid #000;
    }
</style>

<table class="table table-striped table-hover table-bordered bg-white ">

    <tr height="30">
        <td colspan="9" style="word-wrap: break-word; text-align:center; margin:auto;">Customer Number for Promotion Name : {{$promotion->name}} , Total : {{$total}}</td>
    </tr>

    <tr>
        <td colspan="11"></td>
    </tr>
    <tr style="word-wrap: break-word; text-align:center;">
        
        <th width="20" style="text-align:center;">{{ $header[0]}}</th>
        <th width="5" style="text-align:center;">{{ $header[1]}}</th>
        @foreach($days as $d)
            <th width="15" style="text-align:center;"> {{ $d}}</th>
        @endforeach
    </tr>


    <tr>
        <td width="20" style="text-align:center;">{{ $lanthit[0]}}</td>
        <td width="5" style="text-align:center;">{{ $lanthit[1]}}</td>
        @php $i = 0; @endphp
        @foreach($lanthit[2] as $lan_day)
            <td width="15" style="text-align:center;">{{ $lan_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $satsan[0]}}</td>
        <td width="5" style="text-align:center;">{{ $satsan[1]}}</td>
        @php $i = 0; @endphp
        @foreach($satsan[2] as $sat_day)
            <td width="15" style="text-align:center;">{{ $sat_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $eastdagon[0]}}</td>
        <td width="5" style="text-align:center;">{{ $eastdagon[1]}}</td>
        @php $i = 0; @endphp
        @foreach($eastdagon[2] as $east_day)
            <td width="15" style="text-align:center;">{{ $east_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $hlaingthaya[0]}}</td>
        <td width="5" style="text-align:center;">{{ $hlaingthaya[1]}}</td>
        @php $i = 0; @endphp
        @foreach($hlaingthaya[2] as $hlaing_day)
            <td width="15" style="text-align:center;">{{ $hlaing_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $terminal_m[0]}}</td>
        <td width="5" style="text-align:center;">{{ $terminal_m[1]}}</td>
        @php $i = 0; @endphp
        @foreach($terminal_m[2] as $ter_day)
            <td width="15" style="text-align:center;">{{ $ter_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $theikpan[0]}}</td>
        <td width="5" style="text-align:center;">{{ $theikpan[1]}}</td>
        @php $i = 0; @endphp
        @foreach($theikpan[2] as $theik_day)
            <td width="15" style="text-align:center;">{{ $theik_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $tampawady[0]}}</td>
        <td width="5" style="text-align:center;">{{ $tampawady[1]}}</td>
        @php $i = 0; @endphp
        @foreach($tampawady[2] as $tam_day)
            <td width="15" style="text-align:center;">{{ $tam_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $aye_thayar[0]}}</td>
        <td width="5" style="text-align:center;">{{ $aye_thayar[1]}}</td>
        @php $i = 0; @endphp
        @foreach($aye_thayar[2] as $aye_day)
            <td width="15" style="text-align:center;">{{ $aye_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $mawlamyine[0]}}</td>
        <td width="5" style="text-align:center;">{{ $mawlamyine[1]}}</td>
        @php $i = 0; @endphp
        @foreach($mawlamyine[2] as $maw_day)
            <td width="15" style="text-align:center;">{{ $maw_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
    <tr>
        <td width="20" style="text-align:center;">{{ $southdagon[0]}}</td>
        <td width="5" style="text-align:center;">{{ $southdagon[1]}}</td>
        @php $i = 0; @endphp
        @foreach($southdagon[2] as $sd_day)
            <td width="15" style="text-align:center;">{{ $sd_day}}</td>
            @php $i++ @endphp
        @endforeach
    </tr>
</table>