<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet" />
<style>
    .upn {
        margin: 15px 15px 20px;
        background-image: url('/images/ticket_layout/water_bottle.png');
        background-repeat:no-repeat;
        width:100%;
        height:100%;
        background-size: cover;
        }
        h4.date{
            text-align: right;
            line-height: 2.5em;
            padding-right: 20px;
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
            padding-right: 20px;
            padding-top: -4px;
            font-size: 12px;
        }
        @font-face {
            font-family: impact;
            src: url({{ public_path('fonts/impact.ttf') }}) ;
            font-weight: normal;
        }
        .image_div{
            text-align: center;
        }
        .image_div img{
            margin:0px;
            display: block;
        }
        .prize_img{
            margin:0px;
        }
        .word_div{
            padding-top:-10px;
        }
        .prize_word{
            text-align: center;
            margin: 0px;
            padding:0px;
            font-size: 35px;
            padding-bottom: 0px;
            color: red;
        }
        .barcode{
            text-align: center;
            font-size: 9px;
            margin:0px;
            padding-top: -10px;
        }
        .gp{
            text-align: center;
            font-size: 9px;
            color: red;
            padding-top: -10px;
        }
</style>
</head>
<body style="margin: 10px 50px 20px;">
    @for ($i = 0; $i < 2; $i++)
        <div class="upn">
                <h4 class="date">1/6/2022</h4>
                <h4 class="no">LAN1-220914-0001</h4>
                <h4 class="expire">1/8/2022</h4>
                <div class="image_div">
                    <img class="prize_img" style=" margin-top:0px;height:150px;" src="{{ asset('/images/ticket_layout/5.png') }}">
                </div>
                <div class="word_div">
                    <h4 class="prize_word">Water Bottle</h4>
                    <!-- <p class="barcode">
                        @php
                        $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                        @endphp
                        <img class=""
                        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('ha', $generatorPNG::TYPE_CODE_128)) }}">
                    </p>
                    <h4 class="gp">GP171229-000004</h4> -->

                </div>
        </div>

    @endfor

</body>
</html>
