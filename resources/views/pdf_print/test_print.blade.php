<style>
*{margin:0;padding:0}
    body {
        width: 105mm;
        height: 148mm;
        margin: 30mm 45mm 30mm 45mm;
    }
    @page {
        size:105mm × 148mm;
        margin: 0mm 0mm 0mm 0mm;
    }
      .page {
        .page {
        page-break-after: always;
    }

    .last-page {
        width:105mm;
        height: 148mm;
        overflow: hidden;
        font-family: Arial, Helvetica;
        position: relative;
        color: #545554;
    }
        .title{
            line-height: 100px;
            font-size: 10px;
        }
        .sub-title{
            font-size: 50px;
        }
    @media print {
        body{
            width: 21cm;
            height: 29.7cm;
            margin: 30mm 45mm 30mm 45mm;
            /* change the margins as you want them to be. */
        }
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

