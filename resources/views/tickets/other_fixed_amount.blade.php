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
        padding-right: 13px;
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
    /* ////cash coupon//// */
    .cash_coupon_upn {
        background-image: url('/images/ticket_layout/cash_coupon_A6.png');
        background-repeat:no-repeat;
        width:100%;
        height:100%;
        background-size: cover;
        }
        /* h4.cash_coupon_date{
            text-align: right;
            line-height: 2.5em;
            padding-right: 24px;
            padding-bottom: 0px;
            font-size: 12px;
            margin-bottom: 0px;
            }
         h4.cash_coupon_no{
            text-align: right;
            line-height: -78.2%;
            padding-right: 6px;
            padding-top: 0px;
            font-size: 8px;
        }
        h4.cash_coupon_expire{
            text-align: right;
            padding-right: 25px;
            padding-top: -2px;
            font-size: 12px;
        } */
        @font-face {
            font-family: impact;
            src: url({{ public_path('fonts/impact.ttf') }}) ;
            font-weight: normal;
        }
        h4.cash_coupon_amount{
            text-align: center;
            /* padding-right: 19px; */
            margin-top: 59px;
            margin-bottom: 0px;
            font-size: 63px;
            position: fixed;
            color:black;
            font-family:  impact ;

        }
        p.cash_coupon_ks{
           font-size: 30px !important;

        }
        h4.cash_coupon_gp{
            text-align: right;
            padding-right: 37px;
            padding-top: 7px;
            font-size: 9px;
            color:black;

        }
        p.cash_coupon_barcode{
            text-align: right;
            padding-right: 22px;
            padding-top: -54px;
            font-size: 9px;
        }
</style>
</head>
<body style="margin: 10px 50px 20px;">

@foreach ($data as $d)
   @if($d['prize_type'] == 2)
        <div class="upn">
               <h4 class="date">{{ $d['ticket_date'] }}</h4>
                <h4 class="no">{{ $d['series_no'] }}</h4>
                <h4 class="expire">{{ $d['expire_date'] }}</h4>
                <div class="image_div">
                    <img class="prize_img" style=" margin-top:0px;height:150px;" src="{{ asset('/images/promotion_images')}}/{{ $d['promotion_uuid'] }}/{{ $d['sub_promotion_uuid'] }}/{{ $d['image'] }}">
                </div>
                <div class="word_div">
                    <h4 class="prize_word">{{ $d['name'] }}</h4>
                    <p class="barcode">
                        @php
                        $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                        @endphp
                       <img class="" style="width:40%; height:7%;"
                       src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($d['gp_code'], $generatorPNG::TYPE_CODE_128)) }}">
                    </p>
                    <h4 class="gp">{{ $d['gp_code'] }}</h4>
                </div>

        </div>
        @endif
        @if($d['prize_type'] == 1)
            <div class="cash_coupon_upn">
                    <h4 class="date">{{ $d['ticket_date'] }}</h4>
                    <h4 class="no">{{ $d['series_no'] }}</h4>
                    <h4 class="expire">{{ $d['expire_date'] }}</h4>
                    <h4 class="cash_coupon_amount">{{ $d['name'] }}Ks</p></h4>
                    <h4 class="cash_coupon_gp">{{ $d['gp_code'] }}</h4>
                    <p class="cash_coupon_barcode">
                        @php
                        $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                        @endphp
                        <img class=""
                            src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('haha', $generatorPNG::TYPE_CODE_128)) }}">
                    </p>
            </div>
        @endif
    @endforeach
</body>
</html>
