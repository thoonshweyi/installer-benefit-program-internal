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
            line-height: -78.2%;
            padding-right: 6px;
            padding-top: 0px;
            font-size: 8px;
        }
        h4.expire{
            text-align: right;
            padding-right: 25px;
            padding-top: -2px;
            font-size: 12px;
        }
        @font-face {
            font-family: impact;
            src: url({{ public_path('fonts/impact.ttf') }}) ;
            font-weight: normal;
        }
        h4.amount{
            text-align: center;
            /* padding-right: 19px; */
            margin-top: 59px;
            margin-bottom: 0px;
            font-size: 63px;
            position: fixed;
            color:black;
            font-family:  impact ;

        }
        p.ks{
           font-size: 30px !important;

        }
        h4.gp{
            text-align: right;
            padding-right: 37px;
            padding-top: 7px;
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

<body>
    @for ($i = 0; $i < 2; $i++)
        <div class="upn">
                <h4 class="date">1/8/2022</h4>
                <h4 class="no">LAN1-220914-0001</h4>
                <h4 class="expire">1/8/2022</h4>
                <h4 class="amount">2,000 Ks</p></h4>
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
