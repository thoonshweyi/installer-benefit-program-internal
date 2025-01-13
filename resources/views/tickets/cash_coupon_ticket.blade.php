<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet" />
<style>
    body {
        /* background-image: url(https://images.fonearena.com/blog/wp-content/uploads/2013/11/Lenovo-p780-camera-sample-10.jpg); */
    }
    .upn {
            background-image: url('/images/ticket_layout/cash_coupon_A6.png');
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
            font-size: 16px;
            margin-bottom: 0px;
            }
         h4.no{
            text-align: right;
            line-height: -88.2%;
            padding-right: 16px;
            padding-top: -10px;
            font-size: 16px;
        }
        h4.expire{
            text-align: right;
            /* line-height: -88.2%; */
            padding-right: 22px;
            padding-top: -13px;
            font-size: 16px;
        }

        @font-face {
            font-family: impact;
            src: url({{ public_path('fonts/impact.ttf') }}) ;
            font-weight: normal;
        }
        h4.ks{
            text-align: right;
            padding-right: 29px;
            padding-top: -44px;
            font-size: 55px;
            color:white;
            font-family:  impact ;
        }
        h4.gp{
            text-align: right;
            padding-right: 52px;
            padding-top: 7px;
            font-size: 9px;
            color:black;

        }
        p.barcode{
            text-align: right;
            /* line-height: -88.2%; */
            padding-right: 22px;
            padding-top: -54px;
            font-size: 9px;
        }

</style>
</head>

<body>
    @for ($i = 0; $i < 2; $i++)
        <div class="upn">
                <h4 class="date">1/6/2022</h4>
                <h4 class="no">0822-0001</h4>
                <h4 class="expire">1/8/2022</h4>
                <h4 class="ks">10,000</h4>
                <h4 class="gp">GP171229-000004</h4>
                <p class="barcode">
                    @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                    @endphp
                    <img class=""
                        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('haha', $generatorPNG::TYPE_CODE_128)) }}">
                </p>
        </div>
    @endfor
</body>

</html>
