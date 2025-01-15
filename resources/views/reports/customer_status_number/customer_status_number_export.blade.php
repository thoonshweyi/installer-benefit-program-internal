
<style>
    td,
    th {
        border: 1px solid #000;
    }
</style>

<table class="table table-striped table-hover table-bordered bg-white ">

    <tr height="30">
        <td colspan="4" style="word-wrap: break-word; text-align:center; margin:auto;">Customer Status Number for Promotion Name : {{$promotion->name}} </td>
    </tr>
     <tr>
        @foreach($header as $h)
        <th>{{$h}}</th>
        @endforeach
    </tr>
    @foreach ($result as $r)
    <tr class="ligth-data">
        <th scope="col">{{$r[0]}}</th>
        <th scope="col">{{$r[1]}}</th>
        <th scope="col">{{$r[2]}}</th>
        <th scope="col">{{$r[3]}}</th>
    </tr>
    @endforeach        
                                           
</table>