<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
       .page {
          page-break-after:always;
        }
        .last-page{
          overflow: hidden;
          font-family: Arial, Helvetica;
          position: relative;
          color: #545554;
        }
        .title {
          padding: 10px;
          border: 2px dashed #000;
        }
        .text-center {
            text-align: center !important;
            padding-top:10px;
            font-weight: strong;
        }
        .product-image{
            display:table-cell; vertical-align:middle; text-align:center;
        }
        .product-name{
            display:table-cell; vertical-align:middle; text-align:center;
            margin-top: 0px;
        }
        .product-barcode{
            display:table-cell; vertical-align:middle; text-align:center;
            margin-top: 2px;
        }
        .text-center h6{
           color:red;
        }
        .barcode{
          display:table-cell; vertical-align:middle; text-align:center;
            margin-top: 0px;
        }
        h6 {
          font-weight: normal;
        }
        h5 {
          font-weight: normal;
        }
        h4 {
          font-weight: normal;
          font-size: 10px;
          font-family: "Arial", Times, serif;
        }
        h3{
          font-weight: bold;
          font-size: 30px;
          font-family: "Arial", Times, serif;
        }
        .desciption-en {
          font-size: 15px;
        }
        .desciption-mm {
          font-size: 15px;
        }
        .logo-image{
          height: 50px;
          width: 100px;
        }
    </style>
  </head>
  <body>

    @php
      $numItems = count($data);
      $i = 0;
    @endphp
    @foreach($data as $d)
        <div class={{(++$i === $numItems) ? "last-page" : "page"}}>
            <div class="title row">
                {{-- Company Logo --}}
                <div class="logo-image">
                    <img src="{{\App\Http\Controllers\TicketController::imagenABase64('images/logo_black.jpg')}}" >
                </div>
                {{-- Product Image --}}
                <div class="product-image">
                    <img src="{{\App\Http\Controllers\TicketController::imagenABase64('images/prize_items/' . $d['price_cc_check_uuid'].'.png')}}"
                    height="200" style="max-width: 120px" >
                </div>
                {{-- Product Name --}}
                <h3  class="product-name">{{$d['name']}}</h3>
                {{-- Product Barcode --}}
                <!-- @if($d['gp_code'])
                <div class="product-barcode">
                    @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                    @endphp
                    <img class="barcode"
                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($d['gp_code'], $generatorPNG::TYPE_CODE_128)) }}"
                    height="20"
                    >
                </div>
                <h4 class="product-barcode">{{$d['gp_code']}}</h4>
                @endif -->
            </div>
        </div>
    @endforeach
    </body>
</html>
