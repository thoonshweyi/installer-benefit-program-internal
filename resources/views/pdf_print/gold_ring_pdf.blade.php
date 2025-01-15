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
        margin: 15px 15px 20px;
        background-image: url('/images/ticket_layout/gold_ring.png');
        /* background-image:url('../img/certificate_template.png'); */
        background-repeat:no-repeat;
        width:100%;
        height:100%;
        background-size: cover;
        }
        h4.date{
            text-align: right;
            line-height: 2.5em;
            padding-right: 24px;
            padding-bottom: 0px;
            font-size: 12px;
            margin-bottom: 0px;
            }
         h4.no{
            text-align: right;
            line-height: -88.2%;
            padding-right: 5px;
            padding-top: 0px;
            font-size: 8px;
        }
        h4.expire{
            text-align: right;
            padding-right: 22px;
            padding-top: -4px;
            font-size: 12px;
        }
        @font-face {
            font-family: impact;
            src: url({{ public_path('fonts/impact.ttf') }}) ;
            font-weight: normal;
        }
        h4.gp{
            text-align: right;
            padding-right: 42px;
            padding-top: 195px;
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
<body style="margin: 10px 50px 20px;">
    @for ($i = 0; $i < 2; $i++)
        <div class="upn">
                <h4 class="date">1/6/2022</h4>
                <h4 class="no">LAN1-220914-0001</h4>
                <h4 class="expire">1/8/2022</h4>
                <h4 class="gp">GP171229-000004</h4>
                <p class="barcode">
                    @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                    @endphp
                    <img class=""
                        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('ha', $generatorPNG::TYPE_CODE_128)) }}">
                </p>
        </div>
    @endfor
</body>
</html>
