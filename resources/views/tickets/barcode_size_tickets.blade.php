<style>
*{margin:0;padding:0}
    body { margin: 0px; }
      .page {
        margin-top: -20 ;
        margin-left: 5;
            /* page-break-after:always; */
        }
        .last-page{
          overflow: hidden;
          font-family: Arial, Helvetica;
          position: relative;
          color: #545554;
        }
        .title{
            line-height: 1;
            font-size: 0.5mm;
        }
        .sub-title{
            font-size: 0.4mm;
        }

</style>
<body>

    @php
      $numItems = count($data);
      $i = 0;
    @endphp
    @foreach($data as $d)
      <div class={{(++$i === $numItems) ? "last-page" : "page"}}>
        <h3 class="title">Lucky Draw System Version </h3>
        <h4 class="title">{{$d['name']}}</h3>
        <h4 class="sub-title">{{$d['name']}}</h3>
      </div>
    @endforeach
    </body>
</html>
{{-- <div class="last-page" >
    <table style=" border: 1px solid #000; height: 10">
        <tr style="width: 550px;">
            <td>
                <h3 style="font-size:20px;">Testing</h3>
            </td>
        </tr>
    </table>
</div> --}}

