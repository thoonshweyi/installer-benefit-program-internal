<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet" />
<style>
    body {
        /* margin-top:812px; */
        /* background-image: url(https://images.fonearena.com/blog/wp-content/uploads/2013/11/Lenovo-p780-camera-sample-10.jpg); */
    }
    .upn {
        background-image: url('/images/ticket_layout/gold_coin.png');
        /* background-image:url('../img/certificate_template.png'); */
        background-repeat:no-repeat;
        width:100%;
        height:100%;
        background-size: cover;
        }
        h4.date{
            text-align: right;
            line-height: 3em;
            padding-right: 15px;
            padding-bottom: 0px;
            font-size: 14px;
            margin-bottom: 0px;
            }
         h4.no{
            text-align: right;
            line-height: -68.2%;
            padding-right: 16px;
            padding-top: -8px;
            font-size: 14px;
        }
        h4.expire{
            text-align: right;
            padding-right: 22px;
            padding-top: -8px;
            font-size: 14px;
        }
        @font-face {
            font-family: impact;
            src: url({{ public_path('fonts/impact.ttf') }}) ;
            font-weight: normal;
        }
        h4.gp{
            text-align: right;
            padding-right: 42px;
            padding-top: 109px;
            font-size: 9px;
            color:black;
        }
        p.barcode{
            text-align: right;
            padding-right: 22px;
            padding-top: -54px;
            font-size: 9px;
        }
</style>
</head>
<body style="margin: 10px 50px 10px;">
    @foreach ($data as $d)

        <div class="upn">
                <h4 class="date">{{ $d['ticket_date'] }}</h4>
                <h4 class="no">{{ $d['series_no'] }}</h4>
                <h4 class="expire">{{ $d['expire_date'] }}</h4>
                <h4 class="gp">{{ $d['gp_code'] }}</h4>
                <p class="barcode">
                    @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                    @endphp
                    <img class=""
                        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($d['gp_code'], $generatorPNG::TYPE_CODE_128)) }}">
                </p>
        </div>
    @endforeach
</body>
</html>
