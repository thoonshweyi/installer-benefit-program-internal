
<style>
    td,
    th {
        border: 1px solid #000;
    }
</style>

<table class="table table-striped table-hover table-bordered bg-white ">

    <tr height="30">
        <td colspan="4" style="word-wrap: break-word; text-align:center; margin:auto;">{{isset($title_name) ? $title_name : '' }} </td>
    </tr>
     <tr>
        @foreach($header as $h)
        <th>{{$h}}</th>
        @endforeach
    </tr>
                                            
    @foreach($result as $r)
    <tr>
        @foreach($r as $re)
        <th>{{$re}}</th>
        @endforeach
    </tr>
    @endforeach
                                           
</table>